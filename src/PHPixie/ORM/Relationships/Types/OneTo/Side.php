<?php

namespace PHPixie\ORM\Relationships\Types\OneTo;

abstract class Side extends PHPixie\ORM\Relationships\Relationship\Side
{
    public function modelName()
    {
        if ($this->type === 'owner') {
            return $this->config->itemModel;

        return $this->config->ownerModel;
    }

    public function propertyName()
    {
        if ($this->type === 'owner') {
            return $this->config->ownerProperty;

        return $this->config->itemProperty;
    }

    public function handleDeletions()
    {
        return $this->type !== 'owner';
    }
}