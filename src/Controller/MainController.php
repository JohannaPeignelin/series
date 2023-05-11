<?php

namespace App\Controller;

use App\Entity\Serie;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'main_home')]
    /**
     * @Route("/home", name="main_home2")
     */
    public function home(): Response
    {
        return $this->render("main/home.html.twig");
    }

    #[Route('/test', name: 'main_test')]
        public function test(): Response
    {
        //fonction save selon les deux méthodes a voir pour lECF
        //TODO : Renvoyer un formulaire pour ajouter une nouvelle série
        $serie = new Serie();
        $serie->setBackdrop("backdrop.png")
            ->setDateCreated(new \DateTime())
            ->setGenres("Thriller/Drama")
            ->setName("Utopia")
            ->setFirstAirDate(new \DateTime("-2year"))
            ->setLastAirDate(new \DateTime("-2 month"))
            ->setPopularity(500)
            ->setPoster("poster.png")
            ->setStatus("Canceled")
            ->setTmdbId(123456)
            ->setVote(5);

//        dump($serie);
//
//
//        //sauvegarde de mon instance grace à l'EntityManager
//        $entityManager->persist($serie);
//        $entityManager->flush();
//        dump($serie);
//
//        //si j'ai un id j'update
//        $serie->setName("Code Quantum");
//        $entityManager->persist($serie);
//        $entityManager->flush();
//        dump($serie);
//
//        //je supprime
//        $entityManager->remove($serie);
//        $entityManager->flush();



        $username = "<h2>Johanna</h2>";
        $serie =  ["title" => "The Witcher", "year" => 2019];

        return $this->render("main/test.html.twig", [
            "nameOfUser" => $username,
            "mySerie" => $serie
    ]);

    }


}
