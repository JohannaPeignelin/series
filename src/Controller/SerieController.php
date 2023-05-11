<?php

namespace App\Controller;

use App\Entity\Serie;
use App\Repository\SerieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;

#[Route('/series', name: 'serie_')]
class SerieController extends AbstractController
{
    #[Route('', name: 'list')]
    public function list(SerieRepository $serieRepository): Response
    {
        //TODO : renvoyer la liste des series
//        $series = $serieRepository->findAll();

        $series = $serieRepository->findBy([],["vote"=>"DESC"],50);
        dump($series);
        return $this->render('serie/list.html.twig',[
            'series' => $series
        ]);
    }

    #[Route('/{id}', name: 'show', requirements: ["id" => "\d+"])]
    public function show(int $id, SerieRepository $serieRepository): Response
    {

        //TODO : renvoyer le detail d'une serie
        $serie = $serieRepository->find($id);

        return $this->render('serie/show.html.twig', [
            'serie' => $serie
        ]);
    }

    #[Route('/add', name: 'add')]
    public function add(EntityManagerInterface $entityManager, SerieRepository $serieRepository): Response
    {
        //TODO : Renvoyer un formulaire pour ajouter une nouvelle série


        return $this->render('serie/add.html.twig');
    }



}
