<?php

namespace Ext\DirectBundle\Router;

class CallForm extends Call
{
    
    /**
     * Initialize the call properties from a form call.
     * 
     * @param array $call
     */
    private function initialize($call)
    {

        $this->action   = $call['extAction']; unset($call['extAction']);
        $this->method   = $call['extMethod']; unset($call['extMethod']);
        $this->type     = $call['extType']; unset($call['extType']);
        $this->tid      = $call['extTID']; unset($call['extTID']);
        $this->upload   = $call['extUpload']; unset($call['extUpload']);

        foreach ($call as $key => $value) {
            $this->data[$key] = $value;
        }
    }
    
}
