DirectBundle
============

DirectBundle -- это реализация ExtDirect спецификации для symfony2

Установка
----------

Лучший способ установки, добавить git репозиторий в ваш проект, это добавив субмодуль.

Добавьте в файл deps
~~~~~~~~~~~~~~~~~~~~

::

    [ExtDirectBundle]
    git=git://github.com/ghua/DirectBundle.git
    target=/bundles/Ext/DirectBundle
    
Либо добавив субмодуль в вашу текущий проект
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

::

    $ git submodule add git://github.com/ghua/DirectBundle.git vendors/bundles/Ext/DirectBundle
    
Добавляем namespace в autoloader
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

::

    <?php
    // app/autoload.php
    $loader->registerNamespaces(array(
        // ...
        'Ext'           => __DIR__.'/../vendor/bundles',
        // ...
    ));
    
Регистрируем DirectBundle в AppKernel
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

::

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
    
Пример конфигурации
~~~~~~~~~~~~~~~~~~~

    getCustomers - произвольное имя правила
    defaults - основные параметры
    _controller - ИмяУзла:Контроллер:метод
    params - метод принимает параметры
    form - метод formHandler
    reader - аналог store.reader в extjs, поддерживается: root, successProperty, totalProperty

::
    # app/config.yml
    ext_direct:
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

Пример использования
~~~~~~~~~~~~~~~~~~~~