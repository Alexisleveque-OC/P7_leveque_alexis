<?php


namespace App\Tests\Service;


use App\Service\CheckViolationService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\ConstraintViolationList;

class CheckViolationCustomerServiceTest extends TestCase
{
    /**
     * @var CheckViolationService
     */
    private CheckViolationService $checkViolationCustomerService;
    private $violationList;

    public function setUp()
    {
        $this->checkViolationCustomerService = new CheckViolationService();

        $this->violationList = $this->createMock(ConstraintViolationList::class);
        $this->violationList->method('count')
            ->willReturn(1);

        return $this->violationList;
    }

    public function testCheckViolation()
    {

        $this->expectException('\Exception');
        $this->checkViolationCustomerService->checkViolation($this->violationList);

    }
}