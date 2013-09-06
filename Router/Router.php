<?php


namespace Ext\DirectBundle\Router;
use Ext\DirectBundle\Exception\RouteNotFoundException;

/**
 * Class Router
 * @package Ext\DirectBundle\Router
 * @author Semyon Velichko <semyon@velichko.net>
 */
class Router
{

    /**
     * @var array
     */
    private $rules = array();

    /**
     * @param $alias
     * @return bool
     */
    public function has($alias)
    {
        return isset($this->rules[$alias]);
    }

    /**
     * @param $alias
     * @param Rule $Rule
     */
    public function set($alias, Rule $Rule)
    {
        $this->rules[$alias] = $Rule;
    }

    /**
     * @param Rule $Rule
     */
    public function add(Rule $Rule)
    {
        $this->set($Rule->getAlias(), $Rule);
    }

    /**
     * @param $alias
     * @return mixed
     * @throws \Ext\DirectBundle\Exception\RouteNotFoundException
     */
    public function get($alias)
    {
        if(!$this->has($alias))
            throw new RouteNotFoundException();

        return $this->rules[$alias];
    }

    public function all()
    {
        return $this->rules;
    }

}