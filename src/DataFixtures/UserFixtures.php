<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;


class UserFixtures extends Fixture
{
    private $userPasswordEncoder;

    public function  __construct(UserPasswordEncoderInterface $userPasswordEncoder){
        $this->userPasswordEncoder = $userPasswordEncoder;
    }


    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);
        $user = new User();
        $user->setPrenom('Obiwan');
        $user->setNom('Kenobi');
        $user->setBio('Je suis un chevalier Jedi');
        $user->setGithub('BenjaminLoubradou');
        $user->setRoles(['ROLE_USER','ROLE_ADMIN']);
        $password = '123456789';
        $user->setPassword($this->userPasswordEncoder->encodePassword($user,$password));
        $user->setEmail('obi@gmail.com');
        $manager->persist($user);

        $user2 = new User();
        $user2->setPrenom('anakin');
        $user2->setNom('Skywalker');
        $user2->setBio('Je suis un Padawan');
        $user2->setGithub('BenjaminLoubradou');
        $user2->setRoles(['ROLE_USER','ROLE_ADMIN']);
        $password2 = '987654321';
        $user2->setPassword($this->userPasswordEncoder->encodePassword($user2,$password2));
        $user2->setEmail('ani@gmail.com');
        $manager->persist($user2);

        $manager->flush();
    }
}
