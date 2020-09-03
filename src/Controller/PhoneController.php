<?php

namespace App\Controller;

use App\Entity\Phone;
use App\Representation\PhonesRepresentation;
use App\Service\PhoneSearchService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use Hateoas\Representation\PaginatedRepresentation;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PhoneController
 * @package App\Controller
 * @Route("/api/phones")
 */
class PhoneController extends AbstractFOSRestController
{
    /**
     * @Rest\Get(
     *     name="app_list_phones"
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
     * @param SerializerInterface $serializer
     * @param ParamFetcher $paramFetcher
     * @param PhoneSearchService $phoneSearchService
     * @param PhonesRepresentation $phones
     * @return JsonResponse
     * @Rest\View()
     */
    public function listPhones(SerializerInterface $serializer,ParamFetcher $paramFetcher, PhoneSearchService $phoneSearchService, PhonesRepresentation $phones)
    {
        $order = $paramFetcher->get('order');
        $limit = $paramFetcher->get('limit');
        $page = $paramFetcher->get('page');
        $phones = $phones->constructPhoneRepresentation($order,$limit,$page);
        $phones = $serializer->serialize($phones,'json');
//dd($phones);

        return new JsonResponse($phones,Response::HTTP_OK,[],true);
    }

    /**
     * @Rest\Get(
     *     path="/{id<\d+>}",
     *     name="app_phone_show"
     * )
     * @Rest\View(serializerGroups={"phone_show"})
     * @param Phone $phone
     * @return Phone|null
     */
    public function showPhone(Phone $phone)
    {
        return $phone;
    }

}
