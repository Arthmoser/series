<?php

namespace App\Controller;

use App\Entity\Serie;
use App\Form\SerieType;
use App\Repository\SerieRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/serie', name: 'serie_')]
class SerieController extends AbstractController
{
    #[Route('/list', name: 'list')]
    public function list(SerieRepository $serieRepository): Response
    {

//        $series = $serieRepository->findAll();

//        $series = $serieRepository->findBy([], ['vote' => 'desc']);

        $series = $serieRepository->findBestSeries();

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
    public function add(
        SerieRepository $serieRepository,
        Request $request
    ): Response
    {
        $serie = new Serie();
        //création d'une intance de form lié à une instance de série
        $serieForm = $this->createForm(SerieType::class, $serie);

        //methode qui extrait les éléments du formulaire de la requete
        $serieForm->handleRequest($request);

        if ($serieForm->isSubmitted()){

            $serieRepository->save($serie, true);

            $this->addFlash("success", "Serie added !");

            return $this->redirectToRoute('serie_show', ['id' => $serie->getId()]);

        }

        dump($serie);

        //TODO Créer un formulaire d'ajout de série
        return $this->render('serie/add.html.twig', [
            'serieForm' => $serieForm->createView()
        ]);
    }


}
