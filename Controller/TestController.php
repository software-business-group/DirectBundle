<?php

namespace Ext\DirectBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Ext\DirectBundle\Response\Response;
use Ext\DirectBundle\Response\FormError;
use Ext\DirectBundle\Response\ValidatorError;
use Ext\DirectBundle\Model\Test;
use Ext\DirectBundle\Annotation\Route;
use Ext\DirectBundle\Annotation\Reader;
use Ext\DirectBundle\Annotation\Writer;

/**
 * Class TestController
 *
 * @package Ext\DirectBundle\Controller
 *
 * @author  Semyon Velichko <semyon@velichko.net>
 */
class TestController extends Controller
{

    /**
     * @var
     */
    protected $container;

    /**
     * @param null $container
     */
    public function __construct($container = null)
    {
        $this->container = $container;
    }

    /**
     * @param array $_data
     *
     * @return mixed
     */
    public function testArrayResponseAction($_data)
    {
        return $_data;
    }

    /**
     * @param array $_data
     *
     * @return mixed
     */
    public function testObjectResponseAction($_data)
    {
        return $this->get('ext_direct')->createResponse(new Response(), $_data);
    }

    /**
     * @param array $_data
     *
     * @return mixed
     */
    public function testResponseWithConfiguredReaderAction($_data)
    {
        return $this->get('ext_direct')
            ->createResponse(new Response(), $_data)
            ->setSuccess(true)
            ->setTotal(100);
    }

    /**
     * @param array $_data
     *
     * @return mixed
     */
    public function testFormHandlerResponseAction($_data)
    {
        return $this->get('ext_direct')
            ->createResponse(new Response(), $_data)
            ->setSuccess(true);
    }

    /**
     * @param array $_data
     *
     * @return mixed
     */
    public function testFormValidationResponseAction($_data)
    {
        $entity = new Test();

        $form = $this->createFormBuilder($entity, array('csrf_protection' => false))
            ->add('name')
            ->add('count')
            ->getForm();
        $all = $form->all();
        $_data = array_intersect_key($_data, $all);
        $form->bind($_data);

        if ($form->isValid()) {
            return $this->get('ext_direct')
              ->createResponse(new Response())
              ->setSuccess(true);
        } else {
            return $this->get('ext_direct')
                ->createResponse(new FormError(), $form);
        }
    }

    /**
     * @param array $_data
     *
     * @return mixed
     */
    public function testFormEntityValidationResponseAction($_data)
    {
        $entity = new Test();

        $form = $this->createFormBuilder($entity, array('csrf_protection' => false))
            ->add('name')
            ->add('count')
            ->getForm();
        $_data = array_intersect_key($_data, $form->all());
        $form->bind($_data);

        $errors = $this->get('validator')->validate($entity);

        if (count($errors) === 0) {
            return $this->get('ext_direct')
              ->createResponse(new Response())
              ->setSuccess(true);
        } else {
            return $this->get('ext_direct')
                ->createResponse(new ValidatorError(), $errors);
        }
    }

    /**
     * @throws \Exception
     */
    public function testExceptionAction()
    {
        throw new \Exception('Exception from testExceptionAction');
    }

    /**
     * @param array $_data
     *
     * @return mixed
     */
    public function testActionAsService($_data)
    {
        return $this->container->get('ext_direct')
            ->createResponse(new Response(), $_data)
            ->setSuccess(true);
    }

    /**
     * @param array $_data
     *
     * @Reader(type = "xml", root = "read", successProperty = "successProperty", totalProperty = "totalProperty")
     * @Writer(type = "xml", root = "write")
     * @Route(name="annotation_action_with_name", isWithParams = true)
     */
    public function annotationWithNameAction($_data)
    {

    }

    /**
     * @param array $_data
     *
     * @Writer(type = "xml", root = "write")
     * @Route()
     */
    public function annotationWithoutNameAction($_data)
    {

    }

}
