<?php

namespace App\DataFixtures;

use App\Entity\Civility;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Faker;


class UserFixtures extends Fixture
{


    public function __construct(){}

    public function load(ObjectManager $manager): void
    {
        $admin = new User();
        $admin->setEmail('thierrydothee@protonmail.com')
            ->setRoles(['ROLE_USER', 'ROLE_ADMIN'])
            ->setPlainPassword('ArethiA75!')
            ->setCreateAt(new \DateTimeImmutable())
            ->setVerified(false)
            ->setCompleted(false);

            $manager->persist($admin);
            $manager->flush();
        $faker = Faker\Factory::create('fr_FR');
        $nettoyage= ['+33',' ','(',')'];
        for ($i = 0; $i < 20; $i++) {
            $user = new User();
            $user->setEmail($faker->email())
                ->setRoles(['ROLE_USER'])
                ->setPlainPassword('ArethiA75!')
                ->setCreateAt(new \DateTimeImmutable())
                ->setVerified(mt_rand(0,1) === 1 ? true : false)
                ->setCompleted(mt_rand(0,1) === 1 ? true : false);
                        $manager->persist($user);
                        $manager->flush();
                        if($user->isCompleted() == true ){
                        $civility = new Civility();
                        $civility->setClient($user)
                        ->setCreatedAt(new \DateTimeImmutable())
                        ->setPrenom(mt_rand(0,1) === 1 ? $faker->firstNameFemale() : $faker->firstNameMale())
                        ->setNom($faker->lastName())
                      
                        ->setTelephone(str_replace($nettoyage,'',$faker->serviceNumber()))
                        ->setNumero(mt_rand(1,1000))
                        ->setAdresse($faker->streetName())
                        ->setCodePostal(str_replace(' ','',$faker->postcode()))
                        ->setVille($faker->city());
                        $manager->persist($civility);

                    }
    
            $manager->persist($user);
            $manager->flush();
   
        }

     
    }
}
