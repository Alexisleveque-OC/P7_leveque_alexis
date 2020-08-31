<?php

namespace App\Controller;

use App\Entity\Phone;
use App\Service\PhoneSearchService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PhoneController
 * @package App\Controller
 * @Route("/api/Phones")
 */
class PhoneController extends AbstractFOSRestController
{
    /**
     * @Rest\Get(
     *     name="app_list_phones"
     * )
     * @param PhoneSearchService $phoneSearchService
     * @return Phone[]
     * @Rest\View(serializerGroups={"phones_list"})
     */
    public function listPhones(PhoneSearchService $phoneSearchService)
    {
        return $phoneSearchService->listPhones();
    }

    /**
     * @Rest\Get(
     *     path="/{id<\d+>}",
     *     name="app_phone_show"
     * )
     * @Rest\View(serializerGroups={"phone_show"})
     * @param Phone $phone
     * @param PhoneSearchService $phoneSearchService
     * @return Phone|null
     */
    public function showPhone(Phone $phone, PhoneSearchService $phoneSearchService)
    {
        return $phoneSearchService->searchPhone($phone);
    }


//    /**
//     * @Rest\Get(
//     *     path="/{id<\d+>}",
//     *     name="app_phone_show"
//     * )
//     * @Rest\View(serializerGroups={"phone_show"})
//     * @ParamConverter("phone",converter="fos_rest.request_body")
//     * @param Phone $phone
//     * @param PhoneSearchService $phoneSearchService
//     * @return Phone|null
//     */
//    public function showPhone(Phone $phone)
//    {
//        return $phone;
//    }
}
