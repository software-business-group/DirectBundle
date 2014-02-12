<?php

namespace Ext\DirectBundle\Response;

use Symfony\Component\Validator\ConstraintViolationList;

/**
 * Class ValidatorError
 *
 * @package Ext\DirectBundle\Response
 *
 * @author  Semyon Velichko <semyon@velichko.net>
 */
class ValidatorError extends Error implements ResponseInterface
{

    /**
     * @param mixed $errors
     *
     * @return $this|mixed
     * @throws \InvalidArgumentException
     */
    public function setContent($errors)
    {
        if (!($errors instanceof ConstraintViolationList)) {
            throw new \InvalidArgumentException('setContent($errors) must be instance of Symfony\Component\Validator\ConstraintViolationList');
        }


        foreach ($errors as $error) {
            $this->data[] = $error;
        }

        return $this;
    }
}
