<?php


namespace App\Service;


use App\Entity\Customer;
use App\Entity\User;
use App\Exception\CustomerLinkToUserException;

class UserCheckLoginService
{
    public function checkLoginForcustomer(User $user, Customer $customer)
    {
        if ($customer->getUser() !== $user) {
            $message = "Le client que vous rechercher n'existe pas.";
            throw new CustomerLinkToUserException($message);
        }
    }
}