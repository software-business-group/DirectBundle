<?php

namespace Ext\DirectBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Ext\DirectBundle\Response\Response;
use Ext\DirectBundle\Response\FormError;
use Ext\DirectBundle\Response\ValidatorError;
use Ext\DirectBundle\Entity\Test;
use Ext\DirectBundle\Form\Type\TestType;

class ForTestingController extends Controller
{
    
    public function testArrayResponseAction($_data)
    {
        return $_data;
    }

    public function testObjectResponseAction($_data)
    {
        return $this->get('ext_direct')->createResponse(new Response(), $_data);
    }
    
    public function testResponseWithConfiguredReaderAction($_data)
    {
        return $this->get('ext_direct')
            ->createResponse(new Response(), $_data)
            ->setSuccess(true)
            ->setTotal(100);
    }
    
    public function testFormHandlerResponseAction($_data)
    {
        return $this->get('ext_direct')
            ->createResponse(new Response(), $_data)
            ->setSuccess(true);
    }
    
    public function testFormValidationResponseAction($_data)
    {
        $Entity = new Test();

        $form = $this->createForm(new TestType(), $Entity);
        $_data = array_intersect_key($_data, $form->getChildren());
        $form->bind($_data);

        if($form->isValid())
        {
            return $this->get('ext_direct')
              ->createResponse(new Response())
              ->setSuccess(true);
        } else {
            return $this->get('ext_direct')
                ->createResponse(new FormError(), $form);
        }        
    }
    
    public function testFormEntityValidationResponseAction($_data)
    {
        $Entity = new Test();

        $form = $this->createForm(new TestType(), $Entity);
        $_data = array_intersect_key($_data, $form->getChildren());
        $form->bind($_data);
        
        $errors = $this->get('validator')->validate($Entity);

        if(count($errors) === 0)
        {
            return $this->get('ext_direct')
              ->createResponse(new Response())
              ->setSuccess(true);
        } else {
            return $this->get('ext_direct')
                ->createResponse(new ValidatorError(), $errors);
        }      
    }
    
    public function testExceptionAction()
    {
        throw new \Exception('Exception from testExceptionAction');
    }

}
