<?php

namespace PHPixie\ORM\Wrapper\Type\Database;

class Repository implements \PHPixie\ORM\Models\Type\Database\Repository
{
    protected $repository;
    
    public function __construct($repository)
    {
        $this->repository = $repository;
    }
    
    public function config()
    {
        return $this->repository->config();
    }
    
    public function modelName()
    {
        return $this->repository->modelName();
    }
    
    public function save($entity)
    {
        $this->repository->save($entity);
        return $this;
    }
    
    public function delete($entity)
    {
        $this->repository->delete($entity);
        return $this;
    }
    
    
    public function load($data)
    {
        return $this->repository->load($data);
    }
    
    public function create()
    {
        return $this->repository->create();
    }
    
    public function query()
    {
        return $this->repository->query();
    }
    
    public function connection()
    {
        return $this->repository->connection();
    }
    
    public function databaseSelectQuery()
    {
        return $this->repository->databaseSelectQuery();
    }
    
    public function databaseUpdateQuery()
    {
        return $this->repository->databaseUpdateQuery();
    }
    
    public function databaseDeleteQuery()
    {
        return $this->repository->databaseDeleteQuery();
    }
        
    public function databaseInsertQuery()
    {
        return $this->repository->databaseInsertQuery();
    }
    
    public function databaseCountQuery()
    {
        return $this->repository->databaseCountQuery();
    }
    
    
}