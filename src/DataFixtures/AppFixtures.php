<?php

namespace App\DataFixtures;

use App\Entity\Experience;
use App\Entity\Technologies;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 10; $i++) {
            $technologie = new Technologies();
            $technologie->setNom('Technologie ' . $i);
            $technologie->setImage('TechnologieImage' . $i );
            $manager->persist($technologie);
        }

        for ($i = 0; $i < 10; $i++) {
            $experience = new Experience();
            $experience->setNom('Experience ' . $i);
            $experience->setImage('ExperienceImage' . $i );
            $experience->setPetiteDescription('PetiteDescription' . $i );
            $experience->setContenu('Contenu' . $i );
            $manager->persist($experience);
        }

        $manager->flush();
    }
}
