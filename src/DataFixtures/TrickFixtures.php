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

class TrickFixtures extends Fixture implements DependentFixtureInterface
{
    
    protected $slugger;
    protected $category;

    public function __construct(SluggerInterface $slugger, CategoryRepository $category)
    {
        $this->slugger = $slugger;
        $this->category = $category;
    }
    

    private function newTrick(string $name, string $categorie)
    {
        $trick = new Trick;
        $trick->setName($name)
            ->setDescription("Lorem ipsum dolor sit amet consectetur adipisicing elit. Provident harum ratione maxime debitis dignissimos fuga ad delectus veniam recusandae eos.")
            ->setSlug($this->slugger->slug($trick->getName()))
            ->setCreatedAt(new DateTime('NOW'))
            ->setUpdatedAt(new DateTime('NOW'))
            ->setCategory($this->category->findOneBy(['name' => $categorie]))
        ;
        
        return $trick;
    }
        
    public function load(ObjectManager $manager)
    {
        $trick = $this->newTrick('switch ollie', 'straight air');
        $manager->persist($trick);

        $trick1 = $this->newTrick('air to fakie', 'straight air');
        $manager->persist($trick1);

        $trick2 = $this->newTrick('one two', 'grab');
        $manager->persist($trick2);

        $trick3 = $this->newTrick('nose grab', 'grab');
        $manager->persist($trick3);

        $trick4 = $this->newTrick('front flip', 'flips and inverted rotations');
        $manager->persist($trick4);

        $trick5 = $this->newTrick('wildcat', 'flips and inverted rotations');
        $manager->persist($trick5);

        $trick6 = $this->newTrick('handplant', 'inverted hand plants');
        $manager->persist($trick6);

        $trick7 = $this->newTrick('eggplant', 'inverted hand plants');
        $manager->persist($trick7);

        $trick8 = $this->newTrick('lipslide', 'slide');
        $manager->persist($trick8);

        $trick9 = $this->newTrick('nose pick', 'stall');
        $manager->persist($trick9);

       

        $manager->flush();

        
    }

    public function getDependencies()
    {        
        return [
            CategoryFixture::class
        ];        
    }
}
