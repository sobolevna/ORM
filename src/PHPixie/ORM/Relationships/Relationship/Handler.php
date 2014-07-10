<?php

namespace PHPixie\ORM\Relationships\Relationship;

class Handler
{
    protected $ormBuilder;
    protected $relationship;
    protected $repositories;
    protected $planners;
    protected $steps;
    protected $loaders;
    protected $groupMapper;
    protected $cascadeMapper;

    public function __construct($ormBuilder, $relationship, $repositories, $planners, $steps, $loaders, $groupMapper, $cascadeMapper)
    {
        $this->ormBuilder         = $ormBuilder;
        $this->relationship       = $relationship;
        $this->repositories = $repositories;
        $this->planners           = $planners;
        $this->steps              = $steps;
        $this->loaders            = $loaders;
        $this->groupMapper        = $groupMapper;
        $this->cascadeMapper      = $cascadeMapper;
    }

    protected function buildRelatedQuery($modelName, $property, $related)
    {
        return $this->orm->query($modelName)
                                ->related($property, $relatedModel);
    }
    
    protected function getLoadedProperty($model, $propertyName)
    {
        if ($model === null)
            return null;

        $property = $model->relationshipProperty($propertyName, false);
        if ($property === null || !$property->loaded())
            return null;

        return $property;
    }

    protected function assertModelName($model, $requiredModel)
    {
        if ($model->modelName() !== $requiredModel)
            throw new \PHPixie\ORM\Exception\Mappper("Only '$requiredModel' models can be used for this relationship.");
    }

    protected function deletePlanResultStep($plan)
    {
        if (($resultStep = $plan->resultStep()) !== null)
            return $resultStep;

        $resultStep = $this->steps->result(null);
        $plan->setResultStep($resultStep);

        return $resultStep;
    }

    public function handleDeletion($modelName, $side, $resultStep, $plan)
    {

    }

}
