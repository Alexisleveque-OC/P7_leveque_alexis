<?php


namespace App\Service;


use App\Exception\CustomerNotFoundException;
use App\Repository\CustomerRepository;

class CustomerSearchService
{
    /**
     * @var CustomerRepository
     */
    private CustomerRepository $customerRepository;

    public function __construct(CustomerRepository $customerRepository)
    {

        $this->customerRepository = $customerRepository;
    }

    public function findCustomer($id)
    {
        $customer = $this->customerRepository->findOneBy(['id'=>$id]);
        if(empty($customer)){
            throw new CustomerNotFoundException("Le client que vous rechercher n'existe pas",404);
        }
        return $customer;
    }
}