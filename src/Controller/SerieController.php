<?php

namespace App\Controller;

use App\Entity\Serie;
use App\Form\SerieType;
use App\Repository\SerieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;

#[Route('/series', name: 'serie_')]
class SerieController extends AbstractController
{
    #[Route('/{page}', name: 'list', requirements: ["page" => "\d+"])]
    public function list(SerieRepository $serieRepository, int $page = 1): Response
    {
        //TODO : renvoyer la liste des series
//        $series = $serieRepository->findAll();


        $nbSeries = $serieRepository->count([]);
        //convertit au dixième pres au plus haut
        $maxPage = ceil($nbSeries / Serie::MAX_RESULT);

        //gestion page inferieure à 1
        if ($page <1){
            return $this->redirectToRoute('serie_list');
        }elseif ($page > $maxPage){
            return $this->redirectToRoute('serie_list', ['page' => $maxPage]);
        }else{
            $series = $serieRepository->findSeriesWithPagination($page);
            return $this->render('serie/list.html.twig',[
                'series' => $series,
                'currentPage' => $page,
                'maxPage' => $maxPage
            ]);
        }

    }

    #[Route('/detail/{id}', name: 'show', requirements: ["id" => "\d+"])]
    public function show(int $id, SerieRepository $serieRepository): Response
    {

        $serie = $serieRepository->find($id);

        if(!$serie){
            throw $this->createNotFoundException("Oops ! Serie not found ! ");
        }

        return $this->render('serie/show.html.twig', [
            'serie' => $serie,

        ]);
    }

    #[Route('/add', name: 'add')]
    public function add(Request $request, SerieRepository $serieRepository): Response
    {
        //TODO : Renvoyer un formulaire pour ajouter une nouvelle série
        $serie = new Serie();
        //instanciation du formulaire en lui passant l'interface de série
        $serieForm = $this->createForm(SerieType::class,$serie);

        //permet d'extraire les données de la requête
        $serieForm ->handleRequest($request);

        //on rentre dans le if si appui sur submit et si les asserts sont valides
        if ($serieForm->isSubmitted() && $serieForm->isValid()){
            //traitement de la donnée
            //récupération des champs non mapped
            $genres = $serieForm->get('genres')->getData();
            $serie->setGenres(implode('/',$genres));
            $serie->setDateCreated(new \DateTime());

            //enregistre la serie en BDD
            $serieRepository->save($serie, true);
            //redirige vers la page de détails
            $this->addFlash('success','Serie added !');
            return $this->redirectToRoute('serie_show', ['id' => $serie->getId()]);
        }

        return $this->render('serie/add.html.twig', [
            'serieForm2' => $serieForm->createView()
        ]);
    }
    #[Route('/update/{id}', name: 'update', requirements: ['id' => '\d+'])]
    public function update(int $id, SerieRepository $serieRepository){
        $serie = $serieRepository->find($id);
        $serieForm = $this->createForm(SerieType::class, $serie);

        return $this->render('serie/update.html.twig', [
            'serieForm' => $serieForm->createView()
        ]);
    }

    #[Route('/delete/{id}', name: 'delete', requirements: ['id' => '\d+'])]
    public function delete(int $id, SerieRepository $serieRepository){
        $serie = $serieRepository->find($id);


        //suppression de la série
        $serieRepository->remove($serie, true);


        $this->addFlash('success', $serie->getName()."has been removed ! ");

        return $this->redirectToRoute('main_home');
    }
}
