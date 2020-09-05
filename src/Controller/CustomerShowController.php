<?php


namespace App\Controller;

use App\Entity\Customer;
use App\Exception\CustomerLinkToUserException;
use App\Exception\CustomerNotFoundException;
use App\Service\CustomerSearchService;
use App\Service\UserCheckLoginService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\Route;
use Nelmio\ApiDocBundle\Annotation\Security;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;

/**
 * Class CustomerShowController
 * @package App\Controller
 * @Route("/api")
 */
class CustomerShowController extends AbstractFOSRestController
{
    /**
     * @Rest\Get(
     *     name="app_customer_show",
     *     path="/customers/{id<\d+>}"
     * )
     * @Rest\View()
     * @SWG\Response(
     *     response = 200,
     *     description="Customer show",
     *     @SWG\Schema(
     *          type="array",
     *          @SWG\Items(ref=@Model(type=Customer::class, groups={"customer_show"}))
     *     )
     * )
     * @SWG\Response(
     *     response=404,
     *     description="No customer was found"
     * )
     * @SWG\Response(
     *     response=401,
     *     description="Token was expired or not found"
     * )
     * @SWG\Tag (name="customers")
     * @Security(name="Bearer")
     * @param $id
     * @param UserCheckLoginService $checkLogin
     * @param CustomerSearchService $customerSearchService
     * @return Customer
     * @throws CustomerLinkToUserException
     * @throws CustomerNotFoundException
     */
    public function showCustomer($id, UserCheckLoginService $checkLogin, CustomerSearchService $customerSearchService)
    {
        $customer = $customerSearchService->findCustomer($id);

        $user = $this->getUser();

        $checkLogin->checkLoginForCustomer($user, $customer);

        return $customer;
    }
}