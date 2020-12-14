<?php

namespace App\DataFixtures;

use App\Entity\Film;
use App\Entity\Categorie;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class FilmFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);


        $faker = \Faker\Factory::create("fr_FR");
        /*         for ($g = 0; $g < mt_rand(2, 3); $g++) {
            $categorie = new Categorie();
            $contenu = "<p>" . join($faker->paragraphs(5), "</p><p>") . "</p>";
            $categorie->setTitre($faker->title())
                ->setDescription($contenu)
                ->setCreateAt($faker->dateTimeBetween($startDate = '-10 years', $endDate = 'now'));

            $manager->persist($categorie);

 */

        for ($i = 0; $i < mt_rand(6, 8); $i++) {
            $film = new Film();
            $contenu = "<p>" . join($faker->paragraphs(5), "</p><p>") . "</p>";
            $film->setTitre($faker->title())
                ->setDescription($contenu)
                //->setcategorie($categorie)
                ->setImageFilm($faker->imageUrl($width = 200, $height = 200, 'people'))
                ->setDateDeSortieAt($faker->dateTimeBetween($startDate = '-30 years', $endDate = 'now'))
                ->setAjouter(true)
                ->setVideo($faker->mimeType());

            $manager->persist($film);
        }
        // }


        $manager->flush();
    }
}
