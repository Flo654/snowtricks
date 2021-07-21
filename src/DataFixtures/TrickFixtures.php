<?php

namespace App\DataFixtures;


use DateTime;
use App\Entity\Trick;
use App\Entity\Category;
use App\DataFixtures\CategoryFixture;
use App\Repository\CategoryRepository;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\String\Slugger\SluggerInterface;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class TrickFixture extends Fixture implements DependentFixtureInterface
{

    protected $slugger;
    protected $category;

    public function __construct(SluggerInterface $slugger, CategoryRepository $category)
    {
        $this->slugger = $slugger;
        $this->category = $category;
    }

    

    public function load(ObjectManager $manager)
    {
        $trick1 = new Trick;
        $trick1->setName('figure 1')
            ->setDescription("Lorem ipsum dolor sit amet consectetur adipisicing elit. Provident harum ratione maxime debitis dignissimos fuga ad delectus veniam recusandae eos.")
            ->setSlug($this->slugger->slug('figure 1'))
            ->setCreatedAt(new DateTime('NOW'))
            ->setUpdatedAt(new DateTime('NOW'))
            ->setCategory($this->category->findOneBy(['name' => 'straight air'])->getName())
        ;

        $manager->persist($trick1);
        $manager->flush();
        
    }

    public function getDependencies()
    {
        return [
            CategoryFixture::class
        ];
    }
}
