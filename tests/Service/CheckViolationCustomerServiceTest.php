<?php


namespace App\Tests\Service;


use App\Service\CheckViolationCustomerService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;

class CheckViolationCustomerServiceTest extends TestCase
{
    /**
     * @var CheckViolationCustomerService
     */
    private CheckViolationCustomerService $checkViolationCustomerService;
    private $violationList;

    public function setUp()
    {
        $this->checkViolationCustomerService = new CheckViolationCustomerService();

        $violation = $this->createMock(ConstraintViolation::class);
        $this->violationList = $this->createMock(ConstraintViolationList::class);
        $this->violationList->method('count')
            ->willReturn(1);

        return $this->violationList;
    }

    public function testCheckViolation()
    {

//        $this->expectException('App\Exception\ResourceValidationException');
        $this->expectException('\Exception');
        $this->checkViolationCustomerService->checkViolation($this->violationList);

    }
}