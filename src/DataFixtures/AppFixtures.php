<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $product = new User();
        $product->setName("Emmanuel Macron");
        $product->setRoles(['ROLE_ADMIN']);
        $product->setPassword("1234");
        $product->setEmail("manu.croncron@gmail.com");
        $manager->persist($product);

        $product = new User();
        $product->setName("Sardoche");
        $product->setRoles(['ROLE_USER']);
        $product->setPassword("sel");
        $product->setEmail("stuckiron@gmail.com");
        $manager->persist($product);

        $manager->flush();
    }
}
