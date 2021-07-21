<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\Category;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategoryFixture extends Fixture
{
    
    protected $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    

    public function load(ObjectManager $manager)
    {
        $categoryNames = [
            'straight air',
            'grab',
            'spin',
            'flips and inverted rotations',
            'inverted hand plants',
            'slide',
            'stall',
            'tweaks and variations',
            'other'
        ];

        foreach( $categoryNames as $name){

            $category = new Category;
            $category->setName($name)
                ->setSlug($this->slugger->slug($category->getName()))
                ->setCreatedAt(new DateTime('NOW'))
            ;
            $manager->persist($category);
        }

        $manager->flush();
    }
    
}
