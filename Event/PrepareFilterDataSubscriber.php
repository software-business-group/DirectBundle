<?php


namespace Ext\DirectBundle\Event;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * Class PrepareFilterDataSubscriber
 *
 * @package Ext\DirectBundle\Event
 *
 * @author  Semyon Velichko <semyon@velichko.net>
 */
class PrepareFilterDataSubscriber implements  EventSubscriberInterface
{

    private $filterKeys = array('property', 'value');

    const PARTS_REGEXP = '/[\w\d_]+/';

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * array('eventName' => 'methodName')
     *  * array('eventName' => array('methodName', $priority))
     *  * array('eventName' => array(array('methodName1', $priority), array('methodName2'))
     *
     * @return array The event names to listen to
     *
     * @api
     */
    public static function getSubscribedEvents()
    {
        return array(FormEvents::PRE_SUBMIT => 'preSubmit');
    }


    /**
     * @param FormEvent $event
     *
     * @return array
     */
    public function preSubmit(FormEvent $event)
    {
        $event->setData(
            $this->prepare(
                $event->getData()
            )
        );
    }

    /**
     * @param array $filter
     *
     * @return array
     */
    public function prepare($filter)
    {
        $result = array();
        foreach ($filter as $rule) {
            $this->checkFilterKeys($rule);
            $value = $rule['value'];

            if (is_string($value) and in_array($value, array("true", "false"))) {
                $value = filter_var($value, FILTER_VALIDATE_BOOLEAN);
            }

            if (preg_match_all(self::PARTS_REGEXP, $rule['property'], $parts)) {
                $parts = $parts[0];
                foreach ($parts as $part) {
                    $value = array(array_pop($parts) => $value);
                }
            }

            $result = array_merge_recursive($result, $value);
        }

        return $result;
    }

    /**
     * @param array $rule
     *
     * @return bool
     * @throws \InvalidArgumentException
     */
    private function checkFilterKeys(array $rule)
    {
        $diff = array_diff($this->filterKeys, array_keys($rule));
        if (!empty($diff)) {
            throw new \InvalidArgumentException('The request does not contains a required keys: ' . implode(', ', $diff));
        }

        return true;
    }

} 