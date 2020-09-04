<?php

namespace App\Tests\Service;

use App\Entity\Customer;
use App\Entity\User;
use App\Service\CustomerCreateService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class CustomerCreateServiceTest extends TestCase
{
    /**
     * @var CustomerCreateService
     */
    private CustomerCreateService $CustomerCreateService;

    public function setUp()
    {
        $manager = $this->createMock(EntityManagerInterface::class);
        $this->CustomerCreateService = new CustomerCreateService($manager);
    }

    public function customerProvider()
    {
        $customer = new Customer();
        $customer->setFullName('test test')
            ->setEmail('test@test.com')
            ->setStreet('1, rue des tests')
            ->setCity('test')
            ->setZipCode(17220)
            ->setCountry('TEST');

        return [[$customer]];
    }

    /**
     * @dataProvider customerProvider
     * @param Customer $customer
     */
    public function testCreateCustomer(Customer $customer)
    {
        $user = new User();

        $test = $this->CustomerCreateService->createCustomer($customer,$user);

        $this->assertEquals($test->getUser(),$customer->getUser());
        $this->assertInstanceOf(Customer::class,$customer);
        $this->assertInstanceOf(User::class,$customer->getUser());
        $this->assertInstanceOf(\DateTimeImmutable::class,$customer->getCreatedAt());

    }
}
