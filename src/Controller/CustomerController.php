<?php


namespace App\Controller;


use App\Entity\Customer;
use App\Entity\User;
use App\Exception\CustomerLinkToUserException;
use App\Service\CustomerCreateService;
use App\Service\CustomerSearchService;
use App\Service\UserSearchService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\View\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

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
     *     path="/{user_id}/Customers",
     *     name="app_list_customers"
     * )
     * @Rest\View(serializerGroups={"customers_list"})
     * @ParamConverter(name="user", options={"id" = "user_id"})
     * @param User $user
     * @param CustomerSearchService $customerSearchService
     * @param UserSearchService $userSearchService
     * @return Customer[]
     */
    public function listCustomers(User $user, CustomerSearchService $customerSearchService, UserSearchService $userSearchService)
    {
//        $user = $userSearchService->searchUser($user);
        return $customerSearchService->searchCustomersByClient($user);
    }

    /**
     * @Rest\Get(
     *     path="/{user_id}/Customers/{customer_id}"
     * )
     * @Route(name="app_customer_show")
     * @Rest\View(serializerGroups={"customer_show"})
     * @ParamConverter(name="customer", options={"id" = "customer_id"})
     * @ParamConverter(name="user", options={"id" = "user_id"})
     * @param User $user
     * @param Customer $customer
     * @param CustomerSearchService $customerSearchService
     * @param UserSearchService $userSearchService
     * @return Customer|null
     * @throws CustomerLinkToUserException
     */
    public function listCustomer(User $user, Customer $customer, CustomerSearchService $customerSearchService, UserSearchService $userSearchService)
    {
        $customer = $customerSearchService->searchCustomerById($customer);

        if ($customer->getUser() !== $user) {
            $message = "Le client que vous rechercher n'appartient pas à cette utilisateur. Il vous est impossible de voir ses informations.";
            throw new CustomerLinkToUserException($message);
        }

        return $customer;
    }

    /**
     * @Rest\Post(
     *     path="/{user_id}/Customers",
     *     name="app_customer_create"
     * )
     * @ParamConverter(name="customer",
     *     converter="fos_rest.request_body")
     * @ParamConverter (name="user", options={"id" = "user_id"})
     * @Rest\View (statusCode=201)
     * @param Customer $customer
     * @param CustomerCreateService $customerCreate
     * @return View
     */
    public function createCustomer(Customer $customer,User $user ,CustomerCreateService $customerCreate)
    {
        $customer = $customerCreate->createCustomer($customer,$user);

        return $this->view(
            $customer,
            Response::HTTP_CREATED,
            ['Location' => $this->generateUrl(
                'app_customer_show',
                [
                    'user_id' => $customer->getUser()->getId(),
                    'customer_id' => $customer->getId(),
                    UrlGeneratorInterface::ABSOLUTE_PATH
                ]
            )
            ]
        );
    }
}