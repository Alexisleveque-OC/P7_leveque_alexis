<?php

namespace App\Tests\Service;

use App\Entity\Customer;
use App\Entity\User;
use App\Service\UserCheckLoginService;
use PHPUnit\Framework\TestCase;

class UserCheckLoginServiceTest extends TestCase
{
    /**
     * @var UserCheckLoginService
     */
    private UserCheckLoginService $userCheckLoginService;

    public function setUp()
    {
        $this->userCheckLoginService = new UserCheckLoginService();
}
    public function testCheckLoginForCustomer()
    {
        $user1 = new User();
        $user2 = new User();
        $customer = new Customer();
        $customer->setUser($user1);

        $this->expectException('App\Exception\CustomerLinkToUserException');

        $this->userCheckLoginService->checkLoginForCustomer($user2,$customer);


    }
}
