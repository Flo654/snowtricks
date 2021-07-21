<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\Trick;
use App\DataFixtures\CategoryFixture;
use App\Repository\CategoryRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\String\Slugger\SluggerInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class AppFixtures extends Fixture 
{
    
    
    
    
    public function load(ObjectManager $manager)
    {
        
    }

   
}
