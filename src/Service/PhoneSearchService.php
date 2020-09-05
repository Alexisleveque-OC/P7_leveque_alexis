<?php


namespace App\Service;


use App\Exception\PhoneNotFoundException;
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

    public function findPhone($id)
    {
        $phone = $this->phoneRepository->findOneBy(['id'=>$id]);
        if(empty($phone)){
            throw new PhoneNotFoundException("Le téléphone que vous rechercher n'existe pas",404);
        }
        return $phone;
    }

}