<?php


namespace App\Controller;

use App\Representation\CustomersRepresentation;
use App\Entity\Customer;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\Route;
use FOS\RestBundle\Request\ParamFetcher;
use JMS\Serializer\SerializerInterface;
use Nelmio\ApiDocBundle\Annotation\Security;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class CustomerListController
 * @package App\Controller
 * @Route ("/api")
 */
class CustomerListController extends AbstractFOSRestController
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
}