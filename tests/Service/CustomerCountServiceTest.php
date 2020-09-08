<?php


namespace App\Tests\Service;


use App\Entity\Customer;
use App\Entity\User;
use App\Repository\CustomerRepository;
use App\Repository\UserRepository;
use App\Service\CustomerCountService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CustomerCountServiceTest extends TestCase
{
    /**
     * @var CustomerCountService
     */
    private CustomerCountService $CustomerCountService;

//    private UserRepository $userRepository;


    public function setUp()
    {
        $customerRepository = $this->createMock(CustomerRepository::class);
        $this->CustomerCountService = new CustomerCountService($customerRepository);

    }

    public function testCountCustomer()
    {
        $user = new User();
        $user->setUsername("test")
            ->setEmail("test@test.com")
            ->setPassword('coucou')
            ->setRoles(["ROLE_USER"]);

        $customer = new Customer();
        $customer->setFullName('test test')
            ->setEmail('test@test.com')
            ->setStreet('1, rue des tests')
            ->setCity('test')
            ->setZipCode(17220)
            ->setCountry('TEST')
            ->setUser($user);

//        $user->addCustomer($customer);
        $test = $this->CustomerCountService->countCustomer($user);
        dd($test);
        $this->assertEquals(1,$test);

    }
}