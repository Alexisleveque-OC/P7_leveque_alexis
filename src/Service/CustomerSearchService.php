<?php


namespace App\Service;


use App\Entity\Customer;
use App\Entity\User;
use App\Repository\CustomerRepository;
use App\Repository\UserRepository;

class CustomerSearchService
{
    /**
     * @var CustomerRepository
     */
    private CustomerRepository $customerRepository;
    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    public function __construct(CustomerRepository $customerRepository, UserRepository $userRepository)
    {

        $this->customerRepository = $customerRepository;
        $this->userRepository = $userRepository;
    }

    public function searchCustomersByClient(User $user)
    {
        //TODO : refactoriser la date pour quelle apparaisse en format fr
        return $this->customerRepository->findBy(['user'=> $user]);
    }

    public function searchCustomerById(Customer $customer)
    {
        return $this->customerRepository->findOneBy(['id'=>$customer->getId()]);
    }
}