<?php

namespace Ext\DirectBundle\Response;

/**
 * @author Semyon Velichko <semyon@velichko.net>
 */
class Error extends Response implements ResponseInterface
{
    
    protected $success = false;
    
    public function formatResponse(array $data)
    {
        $msg = $this->getFactory()
            ->getTemplating()
            ->render($this->getFactory()->getErrorTemplate(), array('errors' => $data));
        return array($this->getFactory()->getSuccessProperty() => $this->getSuccess(), 'msg' => $msg);
    }
    
}
