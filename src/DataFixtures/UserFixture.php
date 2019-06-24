<?php

namespace App\DataFixtures;

use App\DataFixtures\BaseFixture;
use App\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixture extends BaseFixture
{

    //declare $passwordEncoder
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {

        //initialize passwordEncoder
        $this->passwordEncoder = $passwordEncoder;
    }

    public function loadData(ObjectManager $manager)
    {

        //create test users
        $this->createMany(10,'main_users', function($i){
            $user = new User();
            $user->setEmail(sprintf('user%d@hospitaladmin.com', $i));
            $user->setFirstName($this->faker->firstName);

            //set password and encode it with encodePassword method...
            // requires the user and plain text pw to be passed
            $user->setPassword($this->passwordEncoder->encodePassword(
                $user,
                'admin12345'
            ));
            return $user;

        });

        $manager->flush();
    }
}
