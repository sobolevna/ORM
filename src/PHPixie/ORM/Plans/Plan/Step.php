<?php

namespace PHPixie\ORM\Plans\Plan;

class Step extends \PHPixie\ORM\Plans\Plan
{
    protected $steps = array();

    public function add($step)
    {
        $this->steps[] = $step;
    }

    public function appendPlan($plan)
    {
        $this->steps = array_merge($this->steps, $plan->steps());
    }

    public function steps()
    {
        return $this->steps;
    }

    public function execute()
    {
        foreach ($this->steps as $step) {
            $step->execute();
        }
    }

}
