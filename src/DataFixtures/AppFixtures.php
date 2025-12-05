<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Status;
use App\Entity\Responsable;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        // Catégories
        $categories = ['Incident', 'Panne', 'Évolution', 'Anomalie', 'Information'];
        foreach ($categories as $catName) {
            $category = new Category();
            $category->setName($catName);
            $manager->persist($category);
        }

        // Statuts
        $statuses = ['Nouveau', 'Ouvert', 'Résolu', 'Fermé'];
        foreach ($statuses as $statusName) {
            $status = new Status();
            $status->setName($statusName);
            $manager->persist($status);
        }

        // Responsables
        $responsables = ['Alice Dupont', 'Bob Martin', 'Charlie Durand'];
        foreach ($responsables as $respName) {
            $responsable = new Responsable();
            $responsable->setName($respName);
            $manager->persist($responsable);
        }

        // USER ADMIN
        $admin = new User();
        $admin->setEmail('admin@site.local');
        $hashedPassword = $this->passwordHasher->hashPassword($admin, 'admin123');
        $admin->setPassword($hashedPassword);
        $admin->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);

        // USER STAFF
        $staff = new User();
        $staff->setEmail('staff@site.local');
        $hashedPassword = $this->passwordHasher->hashPassword($staff, 'staff123');
        $staff->setPassword($hashedPassword);
        $staff->setRoles(['ROLE_STAFF']);
        $manager->persist($staff);

        $manager->flush();
    }
}
