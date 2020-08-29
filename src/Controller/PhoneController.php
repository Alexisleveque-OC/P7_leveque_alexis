<?php

namespace App\Controller;

use App\Entity\Phone;
use App\Repository\PhoneRepository;
use App\Service\PhoneSearchService;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
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
     *     name="app_phones_list"
     * )
     * @param PhoneRepository $phoneRepository
     * @return Phone[]
     * @Rest\View(serializerGroups={"list"})
     */
    public function listPhones(PhoneRepository $phoneRepository)
    {
        return $phoneRepository->findAll();
    }

    /**
     * @Rest\Get(
     *     path="/{id<\d+>}",
     *     name="app_phone_show"
     * )
     * @Rest\View(serializerGroups={"show_phone"})
     * @param Phone $phone
     * @param PhoneSearchService $phoneSearchService
     * @return Phone|null
     */
    public function showPhone(Phone $phone, PhoneSearchService $phoneSearchService)
    {
        $phone = $phoneSearchService->searchPhone($phone);

        return $phone;
    }
}
