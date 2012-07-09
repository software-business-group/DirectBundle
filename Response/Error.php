<?php

namespace Ext\DirectBundle\Response;

use Symfony\Component\Form\Form;

class FormError implements ResponseInterface
{
    
    protected $errors = array();
    
    public function setContent(Form $form)
    {        
        if(!($errors instanceof Form))
            throw new \InvalidArgumentException('setContent($form) must be instance of Form');
        
            
        
        return $this;
    }
}
