<?php


namespace App\Service;


use App\Exception\ResourceValidationException;
use Symfony\Component\Validator\ConstraintViolationList;

class CheckViolationCustomerService
{

    public function checkViolation(ConstraintViolationList $violationList)
    {
        if (count($violationList)) {
            $message = "Il y a des champs qui contiennent des informations invalides : ";
            foreach ($violationList as $violation) {
                $message .= sprintf("champ %s : %s", $violation->getPropertyPath(), $violation->getMessage()) . ". ";
            }
            throw new ResourceValidationException($message,400);
        }
    }

}