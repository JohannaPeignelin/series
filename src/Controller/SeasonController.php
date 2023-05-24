<?php

namespace App\Controller;

use App\Entity\Season;
use App\Form\SeasonType;
use App\Repository\SeasonRepository;
use App\Repository\SerieRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/season',name : 'season_')]
class SeasonController extends AbstractController
{
    #[Route('/add/{id}', name: 'add', requirements: ['id' => '\d+'])]
    public function add(SeasonRepository $seasonRepository,
                        SerieRepository $serieRepository,
                        Request $request,
                        EntityManagerInterface $entityManager,
                        int $id): Response
    {
        //récupération de l'instance de la série
        $serie = $serieRepository->find($id);


        $season = new Season();
        //association de l'instance de season avec la bonne série
        $season->setSerie($serie);
        $seasonForm = $this->createForm(SeasonType::class, $season);

        $seasonForm->handleRequest($request);

        if($seasonForm->isSubmitted() && $seasonForm->isValid()){
            /**
             * @var UploadedFile $file
             */
            $file = $seasonForm->get('poster')->getData();


            if($file){
                //on pourrait utiliser un slugger qui sert a nettoyer le lien
                $newFileName = $season->getSerie()->getName().
                    " - ".$season->getNumber()."-".uniqid().".".$file->guessExtension();

                $file->move('img/posters/seasons',$newFileName);
                $season->setPoster($newFileName);

            }


            $seasonRepository->save($season,true);

//            $entityManager->persist($season);
//            $entityManager->flush();

            $this->addFlash('success', 'Season added on '.$season->getSerie()->getName());
            return $this->redirectToRoute('serie_show', ['id'=>$season->getSerie()->getId()]);
        }

        return $this->render('season/add.html.twig', [
                'seasonForm'=>$seasonForm->createView()
        ]);
    }


}
