<?php

namespace Ext\DirectBundle\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Ext\DirectBundle\Response\Response;
use Ext\DirectBundle\Response\FormError;
use Ext\DirectBundle\Response\ValidatorError;
use Ext\DirectBundle\Entity\Test;
use Ext\DirectBundle\Annotation\Route;
use Ext\DirectBundle\Annotation\Reader;
use Ext\DirectBundle\Annotation\Writer;

class TestController extends Controller
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

        $form = $this->createFormBuilder($Entity, array('csrf_protection' => false))
            ->add('name')
            ->add('count')
            ->getForm();
        $all = $form->all();
        $_data = array_intersect_key($_data, $all);
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

        $form = $this->createFormBuilder($Entity, array('csrf_protection' => false))
            ->add('name')
            ->add('count')
            ->getForm();
        $_data = array_intersect_key($_data, $form->all());
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

    public function __construct($container = null)
    {
        if($container instanceof ContainerInterface)
            $this->container = $container;
    }
    
    public function testActionAsService($_data)
    {
        return $this->container->get('ext_direct')
            ->createResponse(new Response(), $_data)
            ->setSuccess(true);
    }

    /**
     * @Route(name="annotation_action_with_name", isWithParams = true)
     * @Reader(type = "xml", root = "read", successProperty = "successProperty", totalProperty = "totalProperty")
     * @Writer(type = "xml", root = "write")
     * @param $_data
     */
    public function annotationWithNameAction($_data)
    {

    }

    /**
     * @Route()
     * @param $_data
     */
    public function annotationWithoutNameAction($_data)
    {

    }

    
}
