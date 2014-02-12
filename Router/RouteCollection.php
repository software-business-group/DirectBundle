<?php

namespace Ext\DirectBundle\Router;
use Ext\DirectBundle\Exception\RouteNotFoundException;

/**
 * Class Router
 *
 * @package Ext\DirectBundle\Router
 *
 * @author  Semyon Velichko <semyon@velichko.net>
 */
class RouteCollection extends Serializable implements \ArrayAccess, \Iterator, \Countable
{

    /**
     * @var int
     */
    protected $position = 0;

    /**
     * @var array
     */
    protected $rules = array();

    /**
     * @return Rule
     */
    public function current()
    {
        $keys = array_keys($this->rules);

        return $this->rules[$keys[$this->position]];
    }

    /**
     * {@inheritdoc}
     */
    public function rewind()
    {
        $this->position = 0;
    }

    /**
     * {@inheritdoc}
     */
    public function key()
    {
        return $this->position;
    }

    /**
     * {@inheritdoc}
     */
    public function next()
    {
        $this->position++;
    }

    /**
     * @return bool
     */
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
     * @param string $alias
     *
     * @return bool
     */
    public function has($alias)
    {
        return isset($this->rules[$alias]);
    }

    /**
     * @param string $alias
     * @param Rule   $rule
     */
    public function set($alias, Rule $rule)
    {
        $this->rules[$alias] = $rule;
    }

    /**
     * @param mixed $alias
     * @param mixed $rule
     */
    public function offsetSet($alias, $rule)
    {
        if (is_null($alias)) {
            $this->add($rule);
        } else {
            $this->set($alias, $rule);
        }
    }

    /**
     * @param string $alias
     *
     * @return bool
     */
    public function offsetExists($alias)
    {
        return $this->has($alias);
    }

    /**
     * @param mixed $alias
     */
    public function offsetUnset($alias)
    {
        $this->remove($alias);
    }

    /**
     * @param mixed $alias
     *
     * @return Rule|mixed
     */
    public function offsetGet($alias)
    {
        return $this->get($alias);
    }

    /**
     * @param Rule|string $rule
     */
    public function remove($rule)
    {
        if ($rule instanceof Rule) {
            $rule = $rule->getAlias();
        }

        unset($this->rules[$rule]);
    }

    /**
     * @param Rule $rule
     */
    public function add(Rule $rule)
    {
        $this->set($rule->getAlias(), $rule);
    }

    /**
     * @param string $alias
     *
     * @return mixed
     * @throws \Ext\DirectBundle\Exception\RouteNotFoundException
     */
    public function get($alias)
    {
        if (!$this->has($alias)) {
            throw new RouteNotFoundException();
        }

        return $this->rules[$alias];
    }

}