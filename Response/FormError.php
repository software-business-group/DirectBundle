<?php

namespace Ext\DirectBundle\Response;

use Symfony\Component\Form\Form;

class FormError extends Error implements ResponseInterface
{   
    public function setContent($form)
    {        
        if(!($form instanceof Form))
            throw new \InvalidArgumentException('setContent($form) must be instance of Form');
        
        foreach($form->getChildren() as $children)
        {
            foreach($children->getErrors() as $error)
            {
                $this->data[] = array('message' => str_replace(array_keys($error->getMessageParameters()), array_values($error->getMessageParameters()),
                    $error->getMessageTemplate()));
            }
        }
        return $this;
    }
}
