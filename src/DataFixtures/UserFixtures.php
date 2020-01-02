<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

     public function __construct(UserPasswordEncoderInterface $passwordEncoder)
     {
         $this->passwordEncoder = $passwordEncoder;
     }

    public function load(ObjectManager $manager)
    {
        // Creation of a user type “subscriber”
        for ($i=0; $i < 3; $i++)
        $subscriber = new User();
        $subscriber>setEmail('subscriber'.$i.'subscriber@monsite.com');
        $subscriber>setRoles(['ROLE_SUBSCRIBER']);
        $subscriber>setPassword($this->passwordEncoder->encodePassword(
            $subscriber,
            'subscriberpassword'.$i
        ));

        $manager->persist($subscriber);

        // Creation of a user type “admin”
        $admin = new User();
        $admin->setEmail('admin@monsite.com');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->passwordEncoder->encodePassword(
            $admin,
            'adminpassword'
        ));

        $manager->persist($admin);

        // Backup of the new users :
        $manager->flush();
    }
}
