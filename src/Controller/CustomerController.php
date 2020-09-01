<?php


namespace App\Controller;


use App\Entity\Customer;
use App\Entity\User;
use App\Exception\CustomerLinkToUserException;
use App\Exception\ResourceValidationException;
use App\Service\CustomerCreateService;
use App\Service\CustomerDeleteService;
use App\Service\CustomerSearchService;
use App\Service\UserSearchService;
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
 * @Route("/api/Clients")
 */
class CustomerController extends AbstractFOSRestController
{
    /**
     * @Rest\Get(
     *     path="/{user_id<\d+>}/Customers",
     *     name="app_list_customers"
     * )
     * @ParamConverter(name="user", options={"id" = "user_id"})
     * @Rest\View(serializerGroups={"customers_list"})
     * @param User $user
     * @return Customer[]
     */
    public function listCustomers(User $user)
    {
        return $user->getCustomers();
    }

    /**
     * @Rest\Get(
     *     path="/{user_id<\d+>}/Customers/{customer_id<\d+>}"
     * )
     * @Route(name="app_customer_show")
     * @Rest\View(serializerGroups={"customer_show"})
     * @ParamConverter(name="customer", options={"id" = "customer_id"})
     * @ParamConverter(name="user", options={"id" = "user_id"})
     * @param User $user
     * @param Customer $customer
     * @return Customer|null
     * @throws CustomerLinkToUserException
     */
    public function listCustomer(User $user, Customer $customer)
    {
        if ($customer->getUser() !== $user) {
            $message = "Le client que vous rechercher n'appartient pas à cette utilisateur. Il vous est impossible de voir ses informations.";
            throw new CustomerLinkToUserException($message);
        }

        return $customer;
    }

    /**
     * @Rest\Post(
     *     path="/{user_id<\d+>}/Customers",
     *     name="app_customer_create"
     * )
     * @ParamConverter(name="customer",
     *     converter="fos_rest.request_body",
     *     options={"validator"={"groups" = "Create"}}
     *     )
     * @ParamConverter (name="user", options={"id" = "user_id"})
     * @Rest\View (statusCode=201, serializerGroups={"after_creation"})
     * @param Customer $customer
     * @param User $user
     * @param CustomerCreateService $customerCreate
     * @param ConstraintViolationList $violationList
     * @return View
     * @throws ResourceValidationException
     */
    public function createCustomer(Customer $customer,
                                   User $user,
                                   CustomerCreateService $customerCreate,
                                   ConstraintViolationList $violationList)
    {
        if (count($violationList)){
            $message = "Il y à des champs qui contiennent des informations invalide : ";
            foreach ($violationList as $violation){
                $message .= sprintf("champ %s : %s",$violation->getPropertyPath(),$violation->getMessage()).". ";
            }
            throw new ResourceValidationException($message);
        }
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
     *     path="/{user_id<\d+>}/Customers/{customer_id<\d+>}"
     * )
     * @Route(name="app_customer_delete")
     * @Rest\View(statusCode=204)
     * @ParamConverter(name="customer", options={"id" = "customer_id"})
     * @ParamConverter(name="user", options={"id" = "user_id"})
     * @param Customer $customer
     * @param User $user
     * @param CustomerDeleteService $customerDelete
     * @return View
     * @throws CustomerLinkToUserException
     */
    public function deleteCustomer(Customer $customer, User $user, CustomerDeleteService $customerDelete)
    {
        if ($customer->getUser() !== $user) {
            $message = "Le client que vous rechercher n'appartient pas à cette utilisateur. Il vous est impossible de le supprimer.";
            throw new CustomerLinkToUserException($message);
        }
        $customerDelete->deleteCustomer($customer);

        return $this->view(null,Response::HTTP_NO_CONTENT);
    }
}