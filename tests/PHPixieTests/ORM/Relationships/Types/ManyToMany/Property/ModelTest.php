<?php

namespace PHPixieTests\ORM\Relationships\Types\ManyToMany\Property;

/**
 * @coversDefaultClass \PHPixie\ORM\Relationships\Types\ManyToMany\Property\Model
 */
class ModelTest extends \PHPixieTests\ORM\Relationships\Relationship\Property\ModelTest
{
    protected $config;
    protected $model;

    public function setUp()
    {
        $this->model  = $this->getModel();
        $this->config = $this->config();
        parent::setUp();
    }

    /**
     * @covers ::query
     * @covers ::<protected>
     */
    public function testQuery()
    {
        $query = $this->getQuery();
        $this->method($this->handler, 'query', $query, array($this->side, $this->model), 0);
        $this->assertEquals($query, $this->propertty->query());
    }

    /**
     * @covers ::add
     * @covers ::remove
     * @covers ::<protected>
     */
    public function testAddItems()
    {
        $this->modifyLinkTest('add', 'link', 'left');
    }


    public function modifyLinkTest($method, $action, $type)
    {
        $this->method($this->side, 'type', $type, array());
        $item = $this->getModel();
        $plan = $this->getPlan();

        $args = $this->reorderArgs(array($this->config, $this->model, $item), $type);

        $this->method($this->handler, $action.'Plan', $plan, $args, 0);
        $this->method($this->handler, 'execute', null, array(), 0);
        $this->method($this->handler, $action.'Properties', null, $args, 0);

        $this->assertEquals($this, $this->property->$method($item));
    }

    protected function reorderArgs($params, $type)
    {
        if($type === 'right') {
            $count = count($params);
            $param = $params[$count - 1];
            $params[$count - 1] = $params[$count - 2];
            $params[$count - 2] = $param;
        }

        return $params;
    }

    protected function value()
    {
        return $this->getValue();
    }

    protected function getValue()
    {
        return $this->quickMock('\PHPixie\ORM\Loaders\Loader\Proxy\Editable');
    }

    protected function getPlan()
    {
        return $this->quickMock('\PHPixie\ORM\Plans\Plan\Step');
    }

    protected function getQuery()
    {
        return $this->quickMock('\PHPixie\ORM\Query');
    }

    protected function handler()
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Types\ManyToMany\Handler');
    }

    protected function side()
    {
        $side = $this->quickMock('\PHPixie\ORM\Relationships\Types\ManyToMany\Side');
        $this->method($side, 'config', $this->config, array());
        return $side;
    }

    protected function config()
    {
        return $this->quickMock('\PHPixie\ORM\Relationships\Types\ManyToMany\Side\Config');
    }

    protected function property()
    {
        return new \PHPixie\ORM\Relationships\Types\ManyToMany\Property\Model($this->handler, $this->side, $this->model);
    }

}
