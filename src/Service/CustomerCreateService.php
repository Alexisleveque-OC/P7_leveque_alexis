<?php


namespace App\Service;


use App\Entity\Customer;
use App\Entity\User;
use App\Repository\UserRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;

class CustomerCreateService
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $manager;
    /**
     * @var UserRepository
     */
    private UserRepository $userRepository;

    public function __construct(EntityManagerInterface $manager, UserRepository $userRepository)
    {
        $this->manager = $manager;
        $this->userRepository = $userRepository;
    }

    public function createCustomer(Customer $customer, User $user)
    {
        $customer->setUser($user);
        $customer->setCreatedAt(new DateTimeImmutable());

        $this->manager->persist($customer);
        $this->manager->flush();

        return $customer;
    }
}