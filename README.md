DirectBundle
============

DirectBundle is an implementation of ExtDirect specification for symfony2.

[![Build Status](https://travis-ci.org/ghua/DirectBundle.png?branch=master)](https://travis-ci.org/ghua/DirectBundle)

Installation
---------

#### Using composer #####
    {
        require: {
            "ghua/ext-direct-bundle": "v2.4.0"
        }
    }

### Register DirectBundle in AppKernel ###

    <?php
    // app/AppKernel.php
    public function registerBundles()
    {
        $bundles = array(
        // ...
            new Ext\DirectBundle\ExtDirectBundle(),
        // ...
        );
    
        // ...
        return $bundles;
    }

### Modify app/config/routing.yml ###

    ext_direct:
        resource: "@ExtDirectBundle/Resources/config/routing.yml"
    
### Configuration Example ###

* error_template - template of validation errors array;
* resource -  routing configuration file, example:
    `resource: "%kernel.root_dir%/config/extdirect_routing.yml"`

#### extdirect_routing.yml ####

    getCustomers:
        defaults: { _controller: AcmeDemoBundle:Demo:getCustomers, params: true }
        reader: { root: root }

    getCountries:
        defaults: { _controller: AcmeDemoBundle:Demo:getCountries }

    getRoles:
        defaults: { _controller: AcmeDemoBundle:Demo:getRoles }

    updateCustomer:
        defaults: { _controller: AcmeDemoBundle:Demo:updateCustomer, params: true }

    createCustomer:
        defaults: { _controller: AcmeDemoBundle:Demo:createCustomer, params: true, form: true }

    chat:
        defaults: { _controller: chat_service:chat, params: true, form: true }

In additional, you can use a controller annotation:

    testClassLoader:
        resource: "@AcmeTestBundle/Controller/TestController.php"

    testDirectoryLoader:
        resource: "@AcmeTestBundle/Controller"

##### AcmeController.php #####

    namespace Acme\TestBundle\Controller;

    use Symfony\Bundle\FrameworkBundle\Controller\Controller;

    use Ext\DirectBundle\Annotation\Route;
    use Ext\DirectBundle\Annotation\Reader;
    use Ext\DirectBundle\Annotation\Writer;

    class TestController extends Controller
    {
        /**
         * @Route(name="acmeTest", isWithParams = true)
         */
         public function testAction($_data)
         {
             // code
         }

Annotation parameters:

* Route - name, isWithParams, isFormHandler
* Reader - root, successProperty, totalProperty, type
* Writer - root, type

### Add to the template ###

        <script type="text/javascript" src="{{ url('ExtDirectBundle_api')}}"></script>

Add a extdirect provider in your ExtJS application:

        Ext.direct.Manager.addProvider(Ext.app.REMOTING_API);


Example of Use
--------------------

#### Simple Version ####

To consider basic example of use, consider the problem of data extraction, for example, to fill the repository (Ext.data.Store).

###### Controller (Symfony2) ######

    <?php
    namespace Acme\DemoBundle\Controller;
    
    use Symfony\Bundle\FrameworkBundle\Controller\Controller;
  
    class DemoController extends Controller
    {
      public function getRolesAction()
      {
        $data = $this->getDoctrine()
            ->getRepository('AcmeDemoBundle:Role')
            ->createQueryBuilder('role')
            ->getQuery()
            ->getArrayResult();
            
        return $data;
      }
    }

###### Model and Repository (ExtJS) #######

    Ext.define('ACME.model.Role', {
      extend: 'Ext.data.Model',
      fields: ['id', 'code', 'name', 'customer_id'],
    
      proxy: {
        type: 'direct',
        api: {
            read: Actions.AcmeDemo_Demo.getRoles
        }
      }
    });

    Ext.define('ACME.store.Role', {
      extend: 'Ext.data.Store',
      model: 'ACME.model.Role',
      autoLoad: true
    });

#### Extended Versions #####

##### AbstractQuery #####

You can do a little differently and transfer to DirectBundle the result from getQuery () (AbstractQuery)



###### Controller (Symfony2) ######
    <?php
    namespace Acme\DemoBundle\Controller;
    
    use Symfony\Bundle\FrameworkBundle\Controller\Controller;
    use Ext\DirectBundle\Response\AbstractQuery;
    class DemoController extends Controller
    {
      public function getCountriesAction()
      {
        $query = $this->getDoctrine()
            ->getRepository('AcmeDemoBundle:Country')
            ->createQueryBuilder('country')
            ->getQuery();
            
        return $this->get('ext_direct')
            ->createResponse(new AbstractQuery(), $query);
      }
    }

##### KnpPaginator and receipt of parameters #####

All data indiscriminately is extracted and transferred rarely.
Pagination, filtering, sorting are the common tasks.

Of course, pagination can be implemented independently and DirectBundle is not a trouble.
But in my project [KnpPaginator] is used for this task (https://github.com/KnpLabs/KnpPaginatorBundle).

###### Controller (Symfony2) ######
    <?php
    namespace Acme\DemoBundle\Controller;
    
    use Acme\DemoBundle\Direct\EventListener\CompactCustomerRolesSubscriber;
    use Symfony\Bundle\FrameworkBundle\Controller\Controller;
    use Ext\DirectBundle\Response\KnpPaginator;
    class DemoController extends Controller
    {
      public function getCustomersAction($page = 1, $limit = 10, $filter = array(), $sort = array())
      {
        $query = $this->getDoctrine()
            ->getEntityManager()
            ->getRepository('AcmeDemoBundle:Customer')
            ->findCustomers($filter, $sort);
            
        $paginator = $this->get('knp_paginator')->paginate($query, $page, $limit);

        return $this->get('ext_direct')
            ->createResponse(new KnpPaginator(), $paginator)
            ->addEventSubscriber(new CompactCustomerRolesSubscriber());
      }
    }
    
Let’s consider carefully the parameters of this method.
They are not mandatory, because method call is carried out via preliminary ReflectionMethod::getParameters.
This means that if the parameter is defined and it can be sent, it will be sent.

**_Addition!_ AbstractQuery returned from findCustomers should have HydrationMode equal to HYDRATE_ARRAY.
This is done by calling setHydrationMode().** method.


###### CustomerRepository ######
    <?php
    namespace Acme\DemoBundle\Repository;

    use Doctrine\ORM\EntityRepository;
    use Doctrine\ORM\Query;
    class CustomerRepository extends EntityRepository
    {
      public function findCustomers($filters = array(), $sorts = array())
      {
            
        $query = $this->createQueryBuilder('customer')
          // ...
        ->getQuery();
            
        return $query->setHydrationMode(Query::HYDRATE_ARRAY);
      }
    }

###### Example of request from ExtJS (JSON) ######
    {
      "action":"AcmeDemo_Demo",
      "method":"getCustomers",
      "data":[{"page":1, "start":0, "limit":28,
        "sort":[
          {"property":"id","direction":"ASC"}
        ],
        "filter":[
          {"property":"roles","value":[4]},
          {"property":"country","value":225}
        ]
      }],  
      "type":"rpc",
      "tid":1
    }
    
Accordingly, any key from data array can be sent as a parameter of the method.

###### Additional Parameters #######
There are several possible parameters:

* Request $request – original of Symfony\Component\HttpFoundation\Request object, for this request;
* $\_data – all original array of sent parameters;
* $\_list – the same $\_data but for batch processing, for example changing several lines in grid, $_list will have an array from several $\_data.

##### Events #####
It is possible to add event handling. At this moment handler Ext\DirectBundle\Response\AbstractQuery supports: PRE\_QUERY\_EXECUTE and POST\_QUERY\_EXECUTE, and based on ot Ext\DirectBundle\Response\KnpPaginator supports only the latter.
_See additional information on events in the source code of Ext\DirectBundle\Response\AbstractQuery::execute()._


The example below changes already extracted data before passing them to the network.

###### Event Example #######
    <?php
    namespace Acme\DemoBundle\Direct\EventListener;

    use Symfony\Component\EventDispatcher\EventSubscriberInterface;
    use Ext\DirectBundle\Event\DirectEvents;
    use Ext\DirectBundle\Event\ResponseEvent;

    class CompactCustomerRolesSubscriber implements EventSubscriberInterface
    {

      public static function getSubscribedEvents()
      {
          return array(DirectEvents::POST_QUERY_EXECUTE => 'callbackFunction');
      }
    
      public function callbackFunction(ResponseEvent $event)
      {
          $data = $event->getData();
        
          foreach($data as $n => $customer)
          {
              if(isset($data[$n]['role_ids']))
                  $data[$n]['role_ids'] = array();
            
                  foreach($customer['roles'] as $role)
                  {
                      $data[$n]['role_ids'][] = $role['id'];
                  }
          }
        
          $event->setData($data);
      }
    }


##### Handling of form submit and return of errors from the form #####

Let’s consider the task of handling submit from Ext.form.Panel.
In code sample for extjs, a window of form displaying and the form itself with the elements is defined.

###### Form (ExtJS) ######
    Ext.define('ACME.view.customer.New', {
      extend: 'Ext.window.Window',
      alias : 'widget.customernewwindow',
    
      autoShow: true,
      title : 'New Customer',
      layout: 'fit',
    
      items: [{
        xtype: 'customerform',
        api: {
            submit: Actions.AcmeDemo_Demo.createCustomer
        },
        paramsAsHash: true
      }],
    
      buttons: [{
        text: 'Save',
        action: 'submit'
      }]
    });
    
    Ext.define('ACME.view.customer.Form', {
      extend: 'Ext.form.Panel',
      alias : 'widget.customerform',

      layout: 'vbox',
      frame: true,
      items: [{
        xtype: 'textfield',
        name: 'name',
        fieldLabel: 'Name',
      },{
        xtype: 'combobox',
        name: 'country_id',
        fieldLabel: 'Country',
        valueField: 'id',
        displayField: 'name',
        store: 'Country',
        forceSelection: true
      },{
        xtype: 'combobox',
        name: 'role_ids',
        fieldLabel: 'Roles',
        valueField: 'id',
        displayField: 'name',
        store: 'Role',
        multiSelect: true
      }]
    });

###### Example of submit request (POST) ######
    country_id  5
    extAction  AcmeDemo_Demo
    extMethod	createCustomer
    extTID	11
    extType	rpc
    extUpload	false
    id	
    name	Admin
    role_ids[]	3
    role_ids[]	1

###### Conroller (Symfony2) ######
    <?php
    namespace Acme\DemoBundle\Controller;
    
    use Symfony\Bundle\FrameworkBundle\Controller\Controller;
    use Ext\DirectBundle\Response\FormError;
    use Acme\DemoBundle\Entity\Customer;
    class DemoController extends Controller
    {
      public function createCustomerAction($_data)
      {
          $Customer = new Customer();
        
          $form = $this->createForm($this->get('acme_demo.updatecustomer'), $Customer);
          $_data = array_intersect_key($_data, $form->all());
          $form->bind($_data);
                
          if($form->isValid())
          {
              $em = $this->getDoctrine()
                  ->getEntityManager();
              $em->persist($Customer);
              $em->flush();
          } else {
              return $this->get('ext_direct')
                  ->createResponse(new FormError(), $form);
          }
        
          return $this->get('ext_direct')
                  ->createResponse(new Response())
                  ->setSuccess(true);
      }
    }

Sent parameters except supporting ones will be sent to $\_data. This array can be directly passed to $form-> bind(), to handle the form.
The form is defined as a service in the example. This is necessary for operation of [Transformers] (http://symfony.com/doc/current/cookbook/form/data_transformers.html).


If form validation is successful, the response is sent: success: true.
    
    [
      {"type":"rpc",
       "tid":"11",
       "action":"AcmeDemo_Demo",
       "method":"createCustomer",
       "result":{"success":true}}
    ]

In case of errors, you can send a response containing success: false and msg with the text of error.
  
    [
      {"type":"rpc",
       "tid":"18",
       "action":"AcmeDemo_Demo",
       "method":"createCustomer",
       "result":{"success":false,
                 "msg":"<ul>\n<li>This value should not be blank<\/li>\n<li>This value is not valid<\/li>\n<\/ul>"}}
    ]

##### Storage synchronization and return of errors of Validator service #####

There is a task of storage synchronization; it is associated with a change of several lines at once.
Similar task can also be solved using DirectBundle.

###### Controller (Symfony2) ######
    <?php
    namespace Acme\DemoBundle\Controller;
    
    use Symfony\Bundle\FrameworkBundle\Controller\Controller;
    use Symfony\Component\HttpFoundation\Request;
    use Ext\DirectBundle\Response\Response;
    use Ext\DirectBundle\Response\ValidatorError;
    class DemoController extends Controller
    {
    public function updateCustomerAction(Request $request, $_list)
    {
        $repository = $this->getDoctrine()
                ->getRepository('AcmeDemoBundle:Customer');
        
        if($request->getMethod() === "POST")
        {   
            foreach($_list as $customer)
            {
                if(!isset($customer['id']))
                    throw new \InvalidArgumentException();
                
                $Customer = $repository->findOneById($customer['id']);

                $form = $this->createForm($this->get('acme_demo.updatecustomer'), $Customer);
                $form->bind(array_intersect_key($customer, $form->all()));
        
                if($form->isValid())
                {
                    $this->getDoctrine()
                        ->getEntityManager()
                        ->flush();
                } else {
                    return $this->get('ext_direct')
                        ->createResponse(new ValidatorError(), $this->get('validator')->validate($Customer));
                }
            }
            
            return $this->get('ext_direct')
                ->createResponse(new Response())
                ->setSuccess(true);
        }
        
        return new Response(502);
    }

In this example, the errors are specially retrieved from validator service, the response format will be similar to the response of the previous section.

##### Exceptions #####

For assist in the development, router can catch exceptions from symfony2 controller.
Example:

###### Conroller (Symfony2) ######

    public function testExceptionAction()
    {
        throw new \Exception('Exception from testExceptionAction');
    }
    
###### ExtJS application ######

    Ext.Direct.on('exception', function(e) {
        Ext.Msg.show({
            title: 'Exception!',
            msg: e.message + ' ' + e.where,
            buttons: Ext.Msg.OK,
            icon: Ext.MessageBox.ERROR
        });
    });
    
Result of calling testException method will be ejection exception:

    [
        {
         "message":"exception 'Exception' with message 'Exception from testExceptionAction'",
         "where":"in \/home\/gh\/dev\/symfony2sandbox\/vendor\/bundles\/Ext\/DirectBundle\/Controller\/ForTestingController.php: 81",
         "type":"exception",
         "tid":3,
         "action":
         "ExtDirect_ForTesting",
         "method":"testException"
        }
    ]
    
ExtJS can display an error message or do something else.

_Warning! This mode can use only in the develop. In production mode, exceptions are handled by symfony.
By default response is HTTP code 500, with the message: Internal Server Error._

Development
---------

#### Testing ####

    composer.phar install
    phpunit
