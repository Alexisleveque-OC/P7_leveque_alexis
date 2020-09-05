<?php


namespace App\Controller;


use App\Exception\CustomerLinkToUserException;
use App\Exception\CustomerNotFoundException;
use App\Service\CustomerDeleteService;
use App\Service\CustomerSearchService;
use App\Service\UserCheckLoginService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Security;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class CustomerDeleteController
 * @package App\Controller
 * @Route("/api")
 */
class CustomerDeleteController extends AbstractFOSRestController
{
    /** @Rest\Delete(
     *     name="app_customer_delete",
     *     path="/customers/{id<\d+>}"
     * )
     * @SWG\Response(
     *     response=204,
     *     description="Customer was correctly deleted"
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Customer was not found"
     * )
     * @SWG\Response(
     *     response=401,
     *     description="Token was expired or not found"
     * )
     * @SWG\Tag (name="customers")
     * @Security(name="Bearer")
     * @Rest\View(statusCode=204)
     * @param $id
     * @param CustomerDeleteService $customerDelete
     * @param UserCheckLoginService $checkLogin
     * @param CustomerSearchService $customerSearchService
     * @return View
     * @throws CustomerLinkToUserException
     * @throws CustomerNotFoundException
     */
    public function deleteCustomer($id,
                                   CustomerDeleteService $customerDelete,
                                   UserCheckLoginService $checkLogin,
                                   CustomerSearchService $customerSearchService)
    {
        $customer = $customerSearchService->findCustomer($id);
        $user = $this->getUser();

        $checkLogin->checkLoginForCustomer($user, $customer);

        $customerDelete->deleteCustomer($customer);

        return $this->view(null, Response::HTTP_NO_CONTENT);
    }
}