<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $categories = ['projet exo','projet école','projet entreprise','test recrutement'];

        foreach ($categories as $category){
            $c = new Category();
            $c->setNom($category);
            $manager->persist($c);
        }

        $manager->flush();
    }
}
