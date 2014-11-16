<?php

namespace PHPixie\ORM;

class Mapper
{
    protected $orm;
    protected $loaders;
    protected $repositoryRegistry;
    protected $groupMapper;
    protected $cascadeMapper;

    public function __construct($orm, $loaders, $repositoryRegistry, $groupMapper, $cascadeMapper)
    {
        $this->orm = $orm;
        $this->loaders = $loaders;
        $this->repositoryRegistry = $repositoryRegistry;
        $this->groupMapper = $groupMapper;
        $this->cascadeMapper = $cascadeMapper;
    }

    public function mapDelete($query)
    {
        $plan = $this->plans->delete();
        $modelName = $query->modelName();
        $repository = $this->repositoryRegistry->get($modelName);

        $handledSides = $this->cascadeMapper->deletionSides($modelName);
        $hasHandledSides = !empty($handledSides);
        $dbQuery = $repository->query($hasHandledSides? 'select' : 'delete');
        $this->groupMapper->mapConditions($dbQuery, $query->conditions(), $modelName, $plan);

        if ($hasHandledSides)
            $query = $this->cascadeMapper->deletion($query, $handledSides, $repository, $plan);

        $deleteStep = $this->steps->query($query);
        $plan->add($deleteStep);

        $plan->push($this->steps->query($dbQuery));

        return $plan;
    }
    
    public function mapCount($query)
    {
    
    }
    
    public function mapUpdate($query, $data)
    {
        $plan = $this->orm->plan();
        $modelName = $query->modelName();
        $repository = $this->repositoryRegistry->get($modelName);

        $dbQuery = $repository->query('update');
        $db->query->data($data);
        $this->groupMapper->mapConditions($dbQuery, $query->conditions(), $modelName, $plan);
        $plan->push($this->steps->query($dbQuery));

        return $plan;
    }

    public function mapFind($query, $preload)
    {
        $modelName = $query->modelName();
        $resultPlan = $this->orm->resultPlan($modelName);
        $repository = $this->repositoryRegistry->get($modelName);

        $dbQuery = $repository->query('select');
        $this->groupMapper->mapConditions($dbQuery, $query->conditions(), $modelName, $resultPlan->requiredPlan());

        $resultStep = $this->steps->reusableResult($dbQuery);
        $plan->setResultStep($resultStep);

        $loader = $this->loaders->reusableResult($resultStep);
        $plan->setLoader($loader);

        foreach($preload as $relationship)
            $this->addPreloaders($model, $relationship, $loader, $plan->preloadPlan());

        return $plan;
    }

    protected function addPreloaders($model, $relationship, $loader, $plan)
    {
        $path = explode('.', $relationship);
        foreach ($path as $rel) {
            $preloader = $loader->getPreloader($relationship);
            if ($preloader === null) {
                $preloader = $this->buildPreloader($model, $relationship, $loader, $plan);
                $loader->setPreloader($relationship, $preloader);
            }
            $model = $preloader->modelName();
            $resultLoader = $preloader->loader();
        }
    }

    protected function buildPreloader($model, $relationship, $loader, $plan)
    {
        $side = $this->relationshipRegistry->getSide($model, $relationship);
        $handler = $this->orm->relationshipType($side->relationshipType())->handler();

        return $handler->preloader($side, $loader, $plan);
    }

}
