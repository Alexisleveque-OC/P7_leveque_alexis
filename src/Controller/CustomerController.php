<?php


namespace App\Controller;


use App\Entity\Customer;
use App\Exception\CustomerLinkToUserException;
use App\Exception\ResourceValidationException;
use App\Representation\CustomersRepresentation;
use App\Service\CheckViolationCustomerService;
use App\Service\CustomerCreateService;
use App\Service\CustomerDeleteService;
use App\Service\UserCheckLoginService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\View\View;
use JMS\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\ConstraintViolationList;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;

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
     * @Rest\QueryParam(
     *     name="order",
     *     requirements="asc|desc",
     *     default="asc",
     *     description="Sort of order (asc or desc)"
     * )
     * @Rest\QueryParam(
     *     name="limit",
     *     requirements="\d+",
     *     default="10",
     *     description="Max number of phone per page"
     * )
     * @Rest\QueryParam(
     *     name="page",
     *     requirements="\d+",
     *     default="1",
     *     description="number of the page want to see"
     * )
     * @SWG\Response(
     *     response = 200,
     *     description="Customers List",
     *      @SWG\Schema(
     *          type="array",
     *          @SWG\Items(ref=@Model(type=Customer::class, groups={"customers_list"}))
     *     )
     *)
     * @SWG\Response(
     *     response=401,
     *     description="Token was expired or not found"
     * )
     * @SWG\Tag(name="customers")
     * @Security(name="Bearer")
     * @Rest\View()
     * @param SerializerInterface $serializer
     * @param ParamFetcher $paramFetcher
     * @param CustomersRepresentation $customersRepresentation
     * @return JsonResponse
     */
    public function listCustomers(SerializerInterface $serializer,
                                  ParamFetcher $paramFetcher,
                                  CustomersRepresentation $customersRepresentation)
    {
        $order = $paramFetcher->get('order');
        $limit = $paramFetcher->get('limit');
        $page = $paramFetcher->get('page');
        $user = $this->getUser();

        $customers = $customersRepresentation->constructPhoneRepresentation($user,$order,$limit,$page);


        $customers = $serializer->serialize($customers,'json');

        return new JsonResponse($customers,Response::HTTP_OK,[],true);
    }

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
     * @param Customer $customer
     * @param UserCheckLoginService $checkLogin
     * @return Customer|null
     * @throws CustomerLinkToUserException
     */
    public function showCustomer(Customer $customer, UserCheckLoginService $checkLogin)
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
     * )
     * @SWG\Response(
     *     response=201,
     *     description="Customer has been created",
     *     @SWG\Schema(
     *          type="array",
     *          @SWG\Items(ref=@Model(type=Customer::class, groups={"after_creation"}))
     *     )
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Some(s) field(s) are not valide"
     * )
     * @SWG\Response(
     *     response=401,
     *     description="Token was expired or not found"
     * )
     * @SWG\Parameter(
     *     name="body",
     *     in="body",
     *     @SWG\Schema(
     *          type="array",
     *          @SWG\Items(ref=@Model(type=Customer::class, groups={"Create"}))
     *      )
     * )
     * @SWG\Tag (name="customers")
     * @Security(name="Bearer")
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