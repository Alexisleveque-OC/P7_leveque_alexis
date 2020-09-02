<?php


namespace App\Controller;


use App\Entity\Customer;
use App\Exception\CustomerLinkToUserException;
use App\Exception\ResourceValidationException;
use App\Service\CheckViolationCustomerService;
use App\Service\CustomerCreateService;
use App\Service\CustomerDeleteService;
use App\Service\UserCheckLoginService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\View\View;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\ConstraintViolationList;

/**
 * Class CustomerController
 * @package App\Controller
 * @Route("/api")
 */
class CustomerController extends AbstractFOSRestController
{
    /**
     * @Rest\Get(
     *     path="/customers",
     *     name="app_list_customers"
     * )
     * @ParamConverter(name="user", options={"id" = "user_id"})
     * @Rest\View(serializerGroups={"customers_list"})
     * @return Customer[]
     */
    public function listCustomers()
    {
//        dd($this->getUser());
        $user = $this->getUser();
        return $user->getCustomers();
    }

    /**
     * @Rest\Get(
     *     path="/customers/{customer_id<\d+>}"
     * )
     * @Route(name="app_customer_show")
     * @Rest\View(serializerGroups={"customer_show"})
     * @ParamConverter(name="customer", options={"id" = "customer_id"})
     * @param Customer $customer
     * @param UserCheckLoginService $checkLogin
     * @return Customer|null
     * @throws CustomerLinkToUserException
     */
    public function listCustomer(Customer $customer, UserCheckLoginService $checkLogin)
    {
        $user = $this->getUser();

        $checkLogin->checkLoginForCustomer($user, $customer);

        return $customer;
    }

    /**
     * @Rest\Post(
     *     path="/customers",
     *     name="app_customer_create"
     * )
     * @ParamConverter(name="customer",
     *     converter="fos_rest.request_body",
     *     options={"validator"={"groups" = "Create"}}
     *     )
     * @ParamConverter (name="user", options={"id" = "user_id"})
     * @Rest\View (statusCode=201, serializerGroups={"after_creation"})
     * @param Customer $customer
     * @param CustomerCreateService $customerCreate
     * @param ConstraintViolationList $violationList
     * @param CheckViolationCustomerService $checkViolationCustomer
     * @return View
     * @throws ResourceValidationException
     */
    public function createCustomer(Customer $customer,
                                   CustomerCreateService $customerCreate,
                                   ConstraintViolationList $violationList,
                                   CheckViolationCustomerService $checkViolationCustomer)
    {
        $checkViolationCustomer->checkViolation($violationList);

        $user = $this->getUser();
        $customer = $customerCreate->createCustomer($customer, $user);

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

    /** @Rest\Delete(
     *     path="/customers/{customer_id<\d+>}"
     * )
     * @Route(name="app_customer_delete")
     * @Rest\View(statusCode=204)
     * @ParamConverter(name="customer", options={"id" = "customer_id"})
     * @param Customer $customer
     * @param CustomerDeleteService $customerDelete
     * @param UserCheckLoginService $checkLogin
     * @return View
     * @throws CustomerLinkToUserException
     */
    public function deleteCustomer(Customer $customer,
                                   CustomerDeleteService $customerDelete,
                                   UserCheckLoginService $checkLogin)
    {
        $user = $this->getUser();

        $checkLogin->checkLoginForCustomer($user, $customer);

        $customerDelete->deleteCustomer($customer);

        return $this->view(null, Response::HTTP_NO_CONTENT);
    }
}