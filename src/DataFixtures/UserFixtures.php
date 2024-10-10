<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use Faker\Generator;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;


class UserFixtures extends Fixture
{

    /**
     * @var Generator
     */
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create('fr_Fr');
    }

    public function load(ObjectManager $manager): void
    {
        $admin = new User();
        $admin->setEmail('thierrydothee@protonmail.com')
            ->setRoles(['ROLE_USER', 'ROLE_ADMIN'])
            ->setPlainPassword('ArethiA75!')
            ->setCreateAt(new \DateTimeImmutable());

        $users[] = $admin;
        $manager->persist($admin);
        for ($i = 0; $i < 4; $i++) {
            $user = new User();
            $user->setEmail($this->faker->email())
                ->setRoles(['ROLE_USER'])
                ->setPlainPassword('ArethiA75!')
                ->setCreateAt(new \DateTimeImmutable());
            $users[] = $user;
            $manager->persist($user);
        }

        $manager->flush();
    }
}
