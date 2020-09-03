<?php

namespace App\DataFixtures;

use App\Entity\Brand;
use App\Entity\Customer;
use App\Entity\Phone;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private UserPasswordEncoderInterface $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }
    
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        $phoneNumber = 1;

        for ($i = 1; $i <= 5; $i++) {
            $brand = new Brand();
            $brand->setName(sprintf('Brand %d', $i));

            $manager->persist($brand);

            for ($j = 1; $j <= 9; $j++) {

                $price = floatval(rand(50,1500).'.'.rand(0,99));

                $phone = new Phone();
                $phone->setName(sprintf('Phone %d', $phoneNumber))
                    ->setDescription($faker->sentence(50))
                    ->setPrice($price)
                    ->setCharacteristic([
                        "size" => mt_rand(5, 10),
                        "autonomy" => mt_rand(20, 48) . 'hours',
                        "weight" => mt_rand(200, 500) . 'grams'
                    ])
                    ->setBrand($brand);
                $phoneNumber++;

                $manager->persist($phone);
            }
        }

        for ($k = 1; $k <= 2; $k++) {
            $user = new User();
            $user->setUsername(sprintf('User%d', $k))
                ->setEmail(sprintf('User%d@gmail.com', $k))
                ->setRoles(['ROLE_USER'])
                ->setPassword('coucou');
            $hash = $this->encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);

            $manager->persist($user);

            for ($l = 1; $l <= 15; $l++){
                $customer = new Customer();
                $zipCode = mt_rand(01000, 97600);
                $customer->setFullName($faker->name)
                    ->setEmail($faker->email)
                    ->setStreet($faker->streetAddress)
                    ->setCity($faker->city)
                    ->setCountry($faker->country)
                    ->setZipCode($zipCode)
                    ->setCreatedAt(new \DateTimeImmutable())
                    ->setUser($user);
                $manager->persist($customer);
            }
        }

        $manager->flush();
    }
}
