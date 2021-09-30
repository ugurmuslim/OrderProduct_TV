<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        /*
         * Logic to create api and secret keys
         *
        * $user->setApiKey(implode('-', str_split(substr(strtolower(md5(microtime().rand(1000, 9999))), 0, 30), 6)));
       $user->setSecretKey(sha1(base64_encode(rand(1,100))));
       */

        $user1 = new User();
        $user1->setEmail('user1@email.com');

        $user1->setApiKey('6f9b85-a74513-594c51-51e92d-0a63d4');
        $user1->setSecretKey('f6b72c7be14396f4bfed83a7fd678ae81e724900');

        $user2 = new User();
        $user2->setEmail('user1@email.com');

        $user2->setApiKey('71eda0-543f5d-c119df-ed1e39-6d910b');
        $user2->setSecretKey('9b070af65673354cf20a9c7760102ae1aba96f8b');

        $manager->persist($user1);
        $manager->persist($user2);

        $manager->flush();
    }
}
