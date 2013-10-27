<?php


namespace Ext\DirectBundle\Router;
use Ext\DirectBundle\Exception\RouteNotFoundException;

/**
 * Class Router
 * @package Ext\DirectBundle\Router
 * @author Semyon Velichko <semyon@velichko.net>
 */
class RouteCollection implements \ArrayAccess, \Iterator, \Countable
{

    /**
     * @var int
     */
    private $position = 0;

    /**
     * @var array
     */
    private $rules = array();

    /**
     * @return Rule
     */
    public function current()
    {
        $keys = array_keys($this->rules);
        return $this->rules[$keys[$this->position]];
    }

    public function rewind()
    {
        $this->position = 0;
    }

    public function key()
    {
        return $this->position;
    }

    public function next()
    {
        $this->position++;
    }

    public function valid()
    {
        $keys = array_keys($this->rules);
        return (array_key_exists($this->position, $keys) && array_key_exists($keys[$this->position], $this->rules));
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->rules);
    }

    /**
     * @param $alias
     * @return bool
     */
    public function has($alias)
    {
        return isset($this->rules[$alias]);
    }

    /**
     * @param string $alias
     * @param Rule $Rule
     */
    public function set($alias, Rule $Rule)
    {
        $this->rules[$alias] = $Rule;
    }

    public function offsetSet($alias, $Rule)
    {
        if(is_null($alias))
        {
            $this->add($Rule);
        } else {
            $this->set($alias, $Rule);
        }
    }

    /**
     * @param mixed $alias
     * @return bool
     */
    public function offsetExists($alias)
    {
        return $this->has($alias);
    }

    public function offsetUnset($alias)
    {
        $this->remove($alias);
    }

    /**
     * @param mixed $alias
     * @return Rule|mixed
     */
    public function offsetGet($alias)
    {
        return $this->get($alias);
    }

    /**
     * @param string|Rule $rule
     */
    public function remove($rule)
    {
        if($rule instanceof Rule)
            $rule = $rule->getAlias();

        unset($this->rules[$rule]);
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
     * @return Rule
     * @throws \Ext\DirectBundle\Exception\RouteNotFoundException
     */
    public function get($alias)
    {
        if(!$this->has($alias))
            throw new RouteNotFoundException();

        return $this->rules[$alias];
    }

}