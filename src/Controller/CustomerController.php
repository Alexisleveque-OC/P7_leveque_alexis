<?php


namespace App\Controller;


use App\Entity\Customer;
use App\Entity\User;
use App\Service\CustomerSearchService;
use App\Service\UserSearchService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Class CustomerController
 * @package App\Controller
 * @Route("/api/Clients")
 */
class CustomerController extends AbstractFOSRestController
{
//     * @ParamConverter(name="user", converter="api.converter")
    /**
     * @Rest\Get(
     *     path="/{id}/Customers",
     *     name="app_list_customers"
     * )
     * @Rest\View(serializerGroups={"customers_list"})
     * @param $id
     * @param CustomerSearchService $customerSearchService
     * @param UserSearchService $userSearchService
     * @return Customer[]
     */
    public function listCustomers($id, CustomerSearchService $customerSearchService, UserSearchService $userSearchService)
    {
        $user = $userSearchService->searchUser($id);
        $customers = $customerSearchService->searchCustomersByClient($user);

        return $customers;
    }
}