<?php

namespace App\Controller;

use App\Entity\Serie;
use App\Repository\SerieRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/serie', name: 'serie_')]
class SerieController extends AbstractController
{
    #[Route('/list', name: 'list')]
    public function list(SerieRepository $serieRepository): Response
    {
        //TODO Récupérer la liste des séries en BDD
//        $series = $serieRepository->findAll();

        $series = $serieRepository->findBy(["status" => "ended"], ["popularity" => "DESC"]);

        dump($series);
        return $this->render('serie/list.html.twig', [
            "series" => $series
        ]);
    }

    #[Route('/{id}', name: 'show', requirements: ['id' => '\d+'])]
    public function show(int $id, SerieRepository $serieRepository): Response
    {
        dump($id);
        //TODO Récupéreration des infos de la série
        $serie = $serieRepository->find($id);

        if (!$serie){
            throw $this->createNotFoundException("oops ! Serie not found !");
        }

        dump($serie);

        return $this->render('serie/show.html.twig', [
            "serie" => $serie
        ]);
    }

    #[Route('/add', name: 'add')]
    public function add(SerieRepository $serieRepository, EntityManager $entityManager): Response
    {
//        $serie = new Serie();
//
//        $serie
//            ->setName("The Office")
//            ->setBackdrop("backdrop.png")
//            ->setDateCreated(new \DateTime())
//            ->setGenres("Commedy")
//            ->setFirstAirDate(new \DateTime('2022-02-02'))
//            ->setLastAirDate(new \DateTime('- 6 month'))
//            ->setPopularity(850.92)
//            ->setPoster("poster.png")
//            ->setTmdbId(123456)
//            ->setVote(8.5)
//            ->setStatus("ended");
//
//        $serie2 = new Serie();
//        $serie2
//            ->setName("Le bureau des légendes")
//            ->setBackdrop("backdrop.png")
//            ->setDateCreated(new \DateTime())
//            ->setGenres("Commedy")
//            ->setFirstAirDate(new \DateTime('2022-02-02'))
//            ->setLastAirDate(new \DateTime('- 6 month'))
//            ->setPopularity(850.92)
//            ->setPoster("poster.png")
//            ->setTmdbId(123456)
//            ->setVote(8.5)
//            ->setStatus("ended");
//
//
//        $entityManager->persist($serie);
//        $entityManager->persist($serie2);
//        $entityManager->flush();

//        dump($serie);
//
//        //enrefistrement en BDD
//        $serieRepository->save($serie);
//
//        dump($serie);
//
//        $serie->setName("The last of us");
//        $serieRepository->save($serie, true);
//
//        dump($serie);
//



        return $this->render('serie/add.html.twig');


    }


}
