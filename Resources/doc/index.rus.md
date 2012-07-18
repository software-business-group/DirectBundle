DirectBundle
============

DirectBundle -- это реализация ExtDirect спецификации для symfony2.
Тестировалось на: symfony 2.0.16, doctrine 2.2.2.

Установка
---------

Лучший способ установки это добавив субмодуль, в ваш git репозиторий.

##### Добавьте в файл deps #####

    [ExtDirectBundle]
    git=git://github.com/ghua/DirectBundle.git
    target=/bundles/Ext/DirectBundle
    
##### Либо добавив субмодуль в ваш текущий проект #####

    $ git submodule add git://github.com/ghua/DirectBundle.git vendor/bundles/Ext/DirectBundle
    
### Добавляем namespace в autoloader ###

    <?php
    // app/autoload.php
    $loader->registerNamespaces(array(
        // ...
        'Ext'           => __DIR__.'/../vendor/bundles',
        // ...
    ));
    
### Регистрируем DirectBundle в AppKernel ###

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
    
### Пример конфигурации ###

  * basic - базовые параметры (необязательно);
    * error_template - шаблон оформления массива ошибок валидации;
  * defaults - основные параметры;
    * _controller - ИмяУзла:Контроллер:метод;
    * params - метод принимает параметры;
    * form - метод formHandler;
  * reader - аналог store.reader в extjs, поддерживается:
      * root, 
      * successProperty,
      * totalProperty.

<pre>
    ext_direct:
        basic:
          error_template: ExtDirectBundle::extjs_errors.html.twig
    router:
        rules:
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
</pre>

Пример использования
--------------------

#### Простой вариант ####

Для рассмотрения базового примера использования, рассмотрим задачу извлечения данных, допустим, чтобы заполнить хранилище (Ext.data.Store).

###### Контроллер (Symfony2) ######

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

###### Модель и хранилище (ExtJS) #######

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

#### Расширенные варианты #####

##### AbstractQuery #####

Можно обойтись несколько иначе и передать в DirectBundle результат из getQuery() (AbstractQuery)

###### Контроллер (Symfony2) ######
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

##### KnpPaginator и прием параметров #####

Редко когда извлекаются и передаются все данные, без разбора. 
Обычной задачей является пагинация, фильтрация, сортировка.

Конечно, разбитие на страницы можно реализовать самостоятельно и DirectBundle нисколько в этом не помеха.
Но в моём проекте, для этой задачи, используется [KnpPaginator](https://github.com/KnpLabs/KnpPaginatorBundle).

###### Контроллер (Symfony2) ######
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
    
Рассмотрим внимательно параметры данного метода.
Они являются не обязательными, т.к. вызов метода происходит через предварительный ReflectionMethod::getParameters.
Это значит, что если параметр определен и его возможно передать, он будет передан.

**_Дополнение!_ Возвращаемый из findCustomers AbstractQuery должен быть с установленным HydrationMode равным HYDRATE_ARRAY.
Выполняется это путем вызова метода setHydrationMode().**

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

###### Пример запроса из ExtJS (JSON) ######
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
    
Соответственно любой ключ из массива data может быть передан как параметр метода.

###### Дополнительные параметры #######
Существуют еще несколько возможных параметров:

* Request $request -- оригинал объекта Symfony\Component\HttpFoundation\Request, для данного запроса;
* $\_data - весь оригинальный массив переданных параметров;
* $\_list - тот же самый $\_data только для пакетной обработки, к примеру изменение нескольких строк в grid, $_list будет содержать массив из нескольких $\_data.

##### События #####
Есть возможность добавить обработку событий. На данный момент обработчик Ext\DirectBundle\Response\AbstractQuery поддерживает: PRE\_QUERY\_EXECUTE и POST\_QUERY\_EXECUTE, а основанный на нем Ext\DirectBundle\Response\KnpPaginator, поддерживает только последний.
_Дополнительную информацию по событиям лучше смотреть непосредственно в исходном коде Ext\DirectBundle\Response\AbstractQuery::execute()._

Ниже приведенный пример изменяет, уже извлеченные данные, перед передачей их в сеть.

###### Пример события #######
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


##### Обработка form submit и возврат ошибок из формы #####

Рассмотрим задачу обработки submit из Ext.form.Panel.
В примере кода, для extjs, определено окно отображения формы и сама форма с элементами.

###### Форма (ExtJS) ######
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

###### Пример submit запроса (POST) ######
    country_id  5
    extAction	AcmeDemo_Demo
    extMethod	createCustomer
    extTID	11
    extType	rpc
    extUpload	false
    id	
    name	Амин
    role_ids[]	3
    role_ids[]	1

###### Контроллер (Symfony2) ######
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
          $_data = array_intersect_key($_data, $form->getChildren());
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

Переданные параметры, кроме служебных, будут переданы в $\_data. Этот массив можно прямо передать в $form->bind(), для обработки формы.
В примере форма определена как служба. Это необходимо для работы [трансформеров](http://symfony.com/doc/current/cookbook/form/data_transformers.html).

Если валидация формы прошла успешно, производится ответ передающий success: true.
    
    [
      {"type":"rpc",
       "tid":"11",
       "action":"AcmeDemo_Demo",
       "method":"createCustomer",
       "result":{"success":true}}
    ]

В случае наличия ошибок, можно передать ответ содержащий success: false и msg с текстом ошибки.
    
    [
      {"type":"rpc",
       "tid":"18",
       "action":"AcmeDemo_Demo",
       "method":"createCustomer",
       "result":{"success":false,
                 "msg":"<ul>\n<li>This value should not be blank<\/li>\n<li>This value is not valid<\/li>\n<\/ul>"}}
    ]

##### Синхронизация хранилища и возврат ошибок из сервиса Validator #####

Существует задача синхронизации хранилища это бывает связано с изменением сразу нескольких строк.
Подобную задачу тоже можно решить, используя DirectBundle.

###### Контроллер (Symfony2) ######
    <?php
    namespace Acme\DemoBundle\Controller;
    
    use Symfony\Bundle\FrameworkBundle\Controller\Controller;
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
                $form->bind(array_intersect_key($customer, $form->getChildren()));
        
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

В данном примере специально ошибки извлекаются из сервиса validator, формат ответа будет аналогичен ответу из предыдущего раздела.
