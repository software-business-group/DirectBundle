<?php

namespace Ext\DirectBundle\Response;

use Symfony\Component\Validator\ConstraintViolationList;

class ValidatorError extends Error implements ResponseInterface
{   
    public function setContent($errors)
    {
        if(!($errors instanceof ConstraintViolationList))
            throw new \InvalidArgumentException('setContent($errors) must be instance of Symfony\Component\Validator\ConstraintViolationList');
        
        foreach($errors as $error)
        {
            $this->data[] = $error;
        }
        
        return $this;
    }
}
