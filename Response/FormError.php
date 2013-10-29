<?php

namespace Ext\DirectBundle\Response;

use Symfony\Component\Form\Form;

/**
 * @author Semyon Velichko <semyon@velichko.net>
 */
class FormError extends Error implements ResponseInterface
{   
    public function setContent($form)
    {        
        if(!($form instanceof Form))
            throw new \InvalidArgumentException('setContent($form) must be instance of Form');
        
        $this->data = $this->compileError($form->getErrors());
        
        foreach($form->all() as $children)
        {
            $this->data = array_merge($this->data, $this->compileError($children->getErrors()));
        }
        return $this;
    }
    
    public function compileError($errors)
    {
        $return = array();
        foreach($errors as $error)
        {
            $return[] = array('message' =>
                str_replace(array_keys($error->getMessageParameters()), array_values($error->getMessageParameters()), $error->getMessageTemplate()));
        }
        return $return;
    }
}
