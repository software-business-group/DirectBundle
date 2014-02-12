<?php

namespace Ext\DirectBundle\Response;

/**
 * Class Exception
 *
 * @package Ext\DirectBundle\Response
 *
 * @author  Semyon Velichko <semyon@velichko.net>
 */
class Exception extends Response implements ResponseInterface
{

    /**
     * @var \Exception
     */
    protected $exception;

    /**
     * @param \Exception $e
     */
    public function __construct(\Exception $e)
    {
        $this->setException($e);
    }

    /**
     * @param \Exception $e
     *
     * @return $this
     */
    public function setException(\Exception $e)
    {
        $this->exception = $e;

        return $this;
    }

    /**
     * @return \Exception
     */
    public function getException()
    {
        return $this->exception;
    }

    /**
     * @param array $data
     *
     * @return array
     */
    public function formatResponse(array $data)
    {
        return array('message' => sprintf("exception '%s' with message '%s'", get_class($this->getException()), $this->getException()->getMessage()),
                     'where' => sprintf('in %s: %d', $this->getException()->getFile(), $this->getException()->getLine()));
    }
}
