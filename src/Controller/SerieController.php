<?php

namespace App\Controller;

use App\Entity\Serie;
use App\Form\SerieType;
use App\Repository\SerieRepository;
use App\Utils\Uploader;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/serie', name: 'serie_')]
class SerieController extends AbstractController
{
    #[Route('/list/{page}', name: 'list', requirements: ['page' => '\d+'])]
    public function list(SerieRepository $serieRepository, int $page = 1): Response
    {

        $nbSerieMax = $serieRepository->count([]);
        $maxPage = ceil($nbSerieMax / SerieRepository::SERIE_LIMIT);

        if ($page >= 1 && $page <= $maxPage) {

            $series = $serieRepository->findBestSeries($page);

        } else {
            throw $this->createNotFoundException("Oops ! Page not found !");
        }


        return $this->render('serie/list.html.twig', [
            'series' => $series,
            'currentPage' => $page,
            'maxPage' => $maxPage
        ]);
    }

    #[Route('/{id}', name: 'show', requirements: ['id' => '\d+'])]
    public function show(int $id, SerieRepository $serieRepository): Response
    {


        $serie = $serieRepository->find($id);

        if (!$serie) {
            throw $this->createNotFoundException("oops ! Serie not found !");
        }



        return $this->render('serie/show.html.twig', [
            "serie" => $serie
        ]);
    }

    #[Route('/add', name: 'add')]
    #[IsGranted("ROLE_USER")]
    public function add(
        SerieRepository $serieRepository,
        Request $request,
        Uploader $uploader
    ): Response
    {
        $serie = new Serie();
        //création d'une intance de form lié à une instance de série
        $serieForm = $this->createForm(SerieType::class, $serie);

        //methode qui extrait les éléments du formulaire de la requete
        $serieForm->handleRequest($request);

        if ($serieForm->isSubmitted() && $serieForm->isValid()) {

            /**
             * @var UploadedFile $file
             */
            $file = $serieForm->get('poster')->getData();

            //appel de l'uploader
            $newFileName = $uploader->upload(
                $file,
                $this->getParameter('upload_serie_poster'),
                $serie->getName());

            //set le nouveau nom de fichier dans la série
            $serie->setPoster($newFileName);


            $serieRepository->save($serie, true);

            $this->addFlash("success", "Serie added !");

            return $this->redirectToRoute('serie_show', ['id' => $serie->getId()]);

        }


        return $this->render('serie/add.html.twig', [
            'serieForm' => $serieForm->createView()
        ]);
    }

    #[Route('/remove/{id}', name: 'remove')]
    public function remove(int $id, SerieRepository $serieRepository)
    {

        $serie = $serieRepository->find($id);

        if ($serie) {
            $serieRepository->remove($serie, true);
            $this->addFlash('warning', 'Serie deleted !');
        } else {
            throw $this->createNotFoundException('This serie can\'t be deleted !');
        }

        return $this->redirectToRoute('serie_list');

    }
}
