<?php


namespace App\Service;


use App\Entity\Phone;
use App\Repository\PhoneRepository;

class PhoneSearchService
{
    /**
     * @var PhoneRepository
     */
    private PhoneRepository $phoneRepository;

    public function __construct(PhoneRepository $phoneRepository)
    {
        $this->phoneRepository = $phoneRepository;
    }

    public function listPhones()
    {
        return $this->phoneRepository->findAll();
    }
}