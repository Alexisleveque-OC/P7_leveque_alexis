<?php


namespace App\Service;


use App\Entity\User;
use App\Repository\CustomerRepository;

class CustomerCountService
{
    /**
     * @var CustomerRepository
     */
    private CustomerRepository $repository;

    public function __construct(CustomerRepository $repository)
    {

        $this->repository = $repository;
    }

    public function countCustomer(User $user)
    {
        return $this->repository->count(['user'=>$user]);
    }
}