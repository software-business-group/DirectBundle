<?php
namespace Ext\DirectBundle\Router;

use Ext\DirectBundle\Response\ResponseInterface;

/**
 * Call encapsule an single ExtDirect call.
 *
 * @author Otavio Fernandes <otavio@neton.com.br>
 */
class Call
{
    /**
     * The ExtDirect action called. With reference to Bundle via underscore '_'.
     * 
     * @var string
     */
    protected $action;

    /**
     * The ExtDirect method called.
     * 
     * @var string
     */
    protected $method;

    /**
     * The ExtDirect request type.
     * 
     * @var string
     */
    protected $type;

    /**
     * The ExtDirect transaction id.
     * 
     * @var integer
     */
    protected $tid;

    /**
     * The ExtDirect call params.
     * 
     * @var array
     */
    protected $data;

    /**
     * The ExtDirect request type. Where values in ('form','single').
     * 
     * @var string
     */
    protected $callType;

    /**
     * The ExtDirect upload reference.
     * 
     * @var boolean
     */
    protected $upload;
    
    protected $bundle;

    /**
     * Initialize an ExtDirect call.
     * 
     * @param array  $call
     * @param string $type
     */
    public function __construct($call, Request $request)
    {
        $this->request = $request;
        $this->initialize($call);
    }
    
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Get the requested action.
     *
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Get the requested method.
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Get the request method params.
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }


    /**
     * Return a result wrapper to ExtDirect method call.
     * 
     * @param  array $result
     * @return array
     */
    public function getResponse($result)
    {
        $return = 
            array('type' => 'rpc',
                  'tid' => $this->tid,
                  'action' => $this->action,
                  'method' => $this->method);
            
        if($result instanceof ResponseInterface)
        {
            $return['result'] = $result->extract();
        } else {
            $return['result'] = $result;
        }
        
        return $return;
    }
    
    /**
     * Initialize the call properties from a single call.
     * 
     * @param array $call
     */
    public function initialize($call)
    {
        $this->action = $call['action'];
        $this->method = $call['method'];
        $this->type   = $call['type'];
        $this->tid    = $call['tid'];
        $this->data   = array();
        
        if(is_array($call['data']) && !empty($call['data']))
            $this->data   = array_shift($call['data']);
    }
    
}
