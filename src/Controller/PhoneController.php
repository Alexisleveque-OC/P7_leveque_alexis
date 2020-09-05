<?php

namespace App\Controller;

use App\Entity\Phone;
use App\Representation\PhonesRepresentation;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use JMS\Serializer\SerializerInterface;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;

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
     * @SWG\Response(
     *     response = 200,
     *     description="Phones List",
     *      @SWG\Schema(
     *          type="array",
     *          @SWG\Items(ref=@Model(type=Phone::class, groups={"phones_list"}))
     *     )
     *)
     * @SWG\Response(
     *     response=401,
     *     description="Token was expired or not found"
     * )
     * @SWG\Tag(name="phones")
     * @Security(name="Bearer")
     * @param SerializerInterface $serializer
     * @param ParamFetcher $paramFetcher
     * @param PhonesRepresentation $phones
     * @return JsonResponse
     * @Rest\View()
     */
    public function listPhones(SerializerInterface $serializer,
                               ParamFetcher $paramFetcher,
                               PhonesRepresentation $phones)
    {
        $order = $paramFetcher->get('order');
        $limit = $paramFetcher->get('limit');
        $page = $paramFetcher->get('page');

        $phones = $phones->constructPhoneRepresentation($order,$limit,$page);

        $phones = $serializer->serialize($phones,'json');

        $response = new JsonResponse($phones,Response::HTTP_OK,[],true);

        $response->setPublic();
        $response->setMaxAge(3600);

        return $response;
    }

    /**
     * @Rest\Get(
     *     path="/{id<\d+>}",
     *     name="app_phone_show"
     * )
     * @Rest\View(serializerGroups={"phone_show"})
     * @param Phone $phone
     * @param SerializerInterface $serializer
     * @return JsonResponse
     * @SWG\Response(
     *     response = 200,
     *     description="Phone show",
     *     @SWG\Schema(
     *          type="array",
     *          @SWG\Items(ref=@Model(type=Phone::class, groups={"phone_show"}))
     *     )
     * )
     * @SWG\Response(
     *     response=404,
     *     description="No phone was found"
     * )
     * @SWG\Response(
     *     response=401,
     *     description="Token was expired or not found"
     * )
     * @SWG\Tag (name="phones")
     * @Security(name="Bearer")
     */
    public function showPhone(Phone $phone, SerializerInterface $serializer)
    {
        $phone = $serializer->serialize($phone,'json');

        $response = new JsonResponse($phone,Response::HTTP_OK,[],true);

        $response->setPublic();
        $response->setMaxAge(3600);

        return $response;
    }
}
