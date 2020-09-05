<?php


namespace App\Controller;


use App\Entity\Customer;
use App\Exception\ResourceValidationException;
use App\Service\CheckViolationCustomerService;
use App\Service\CustomerCreateService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use Nelmio\ApiDocBundle\Annotation\Security;
use Nelmio\ApiDocBundle\Annotation\Model;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Validator\ConstraintViolationList;

/**
 * Class CustomerCreateController
 * @package App\Controller
 * @Rest\Route("/api")
 */
class CustomerCreateController extends AbstractFOSRestController
{
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
                    'id' => $customer->getId(),
                    UrlGeneratorInterface::ABSOLUTE_PATH
                ]
            )
            ]
        );
    }
}