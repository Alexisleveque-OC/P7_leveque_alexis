<?php

namespace App\Controller;

use App\Entity\Phone;
use App\Repository\PhoneRepository;
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
     * @Rest\View(serializerGroups={"List"})
     */
    public function listPhones(PhoneRepository $phoneRepository)
    {
        $phones = $phoneRepository->findAll();

        return $phones;
    }
}
