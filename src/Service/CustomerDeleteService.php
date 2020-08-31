<?php


namespace App\Service;


use App\Entity\Customer;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class CustomerDeleteService
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    public function deleteCustomer(Customer $customer)
    {
        $this->manager->remove($customer);
        $this->manager->flush();
    }
}