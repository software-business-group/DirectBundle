<?php
if (!@include __DIR__ . '/../vendor/autoload.php') {
    die("You must set up the project dependencies, run the following commands:
wget http://getcomposer.org/composer.phar
php composer.phar install --dev
");
}

use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;

AnnotationRegistry::registerFile( __DIR__ . "/../Annotation/Reader.php");
AnnotationRegistry::registerFile( __DIR__ . "/../Annotation/Writer.php");
AnnotationRegistry::registerFile( __DIR__ . "/../Annotation/Route.php");
AnnotationRegistry::registerFile( __DIR__ . "/../vendor/symfony/symfony/src/Symfony/Component/Validator/Constraints/NotBlank.php");
AnnotationRegistry::registerFile( __DIR__ . "/../vendor/symfony/symfony/src/Symfony/Component/Validator/Constraints/Range.php");
AnnotationRegistry::registerFile( __DIR__ . "/../vendor/sensio/framework-extra-bundle/Configuration/ParamConverter.php");
AnnotationRegistry::registerFile( __DIR__ . "/../vendor/jms/serializer/src/JMS/Serializer/Annotation/ExclusionPolicy.php");
AnnotationRegistry::registerFile( __DIR__ . "/../vendor/jms/serializer/src/JMS/Serializer/Annotation/Expose.php");
AnnotationRegistry::registerFile( __DIR__ . "/../vendor/jms/serializer/src/JMS/Serializer/Annotation/ReadOnly.php");
