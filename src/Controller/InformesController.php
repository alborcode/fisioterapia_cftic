<?php

namespace App\Controller;

use App\Entity\Informes;
use App\Form\InformesType;
use App\Repository\InformesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/informes')]
class InformesController extends AbstractController
{
    #[Route('/', name: 'app_informes_index', methods: ['GET'])]
    public function index(
        EntityManagerInterface $em,
        InformesRepository $informesRepository,
        PaginatorInterface $paginator,
        Request $request
    ): Response {
        $em = $this->getDoctrine()->getManager();
        $query = $em->getRepository(Informes::class)->getAllPost();

        $pagination = $paginator->paginate(
            $query /* query NOT result */,
            $request->query->getInt('page', 1) /*page number*/,
            10 /*limit per page*/
        );

        // parameters to template
        return $this->render('informes/index.html.twig', [
            'informes' => $pagination,
        ]);

        // return $this->render('informes/index.html.twig', [
        //     'informes' => $informesRepository->findAll(),
        // ]);
    }

    #[Route('/new', name: 'app_informes_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        InformesRepository $informesRepository,
        SluggerInterface $slugger
    ): Response {
        //$em = $this->getDoctrine()->getManager();
        $informe = new Informes();
        $form = $this->createForm(InformesType::class, $informe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $fichero = $form->get('anexo')->getData();
            if ($fichero) {
                $originalFilename = pathinfo(
                    $fichero->getClientOriginalName(),
                    PATHINFO_FILENAME
                );
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename =
                    $safeFilename .
                    '-' .
                    uniqid() .
                    '.' .
                    $fichero->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $fichero->move(
                        $this->getParameter('informe_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    throw new \Exception('Upsss, Something is wrong');
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $informe->setAnexo($newFilename);
            }

            // $em->persist($informe)
            // $em->flush();
            // $this->addFlash('sucess', 'Informe creado');
            $informesRepository->add($informe, true);

            return $this->redirectToRoute(
                'app_informes_index',
                [],
                Response::HTTP_SEE_OTHER
            );
        }

        return $this->renderForm('informes/new.html.twig', [
            'informe' => $informe,
            'form' => $form,
        ]);
    }

    #[Route('/{idinforme}', name: 'app_informes_show', methods: ['GET'])]
    public function show(Informes $informe): Response
    {
        return $this->render('informes/show.html.twig', [
            'informe' => $informe,
        ]);
    }

    #[Route('/{idinforme}/edit', name: 'app_informes_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        Informes $informe,
        InformesRepository $informesRepository
    ): Response {
        $form = $this->createForm(InformesType::class, $informe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $informesRepository->add($informe, true);

            return $this->redirectToRoute(
                'app_informes_index',
                [],
                Response::HTTP_SEE_OTHER
            );
        }

        return $this->renderForm('informes/edit.html.twig', [
            'informe' => $informe,
            'form' => $form,
        ]);
    }

    #[Route('/{idinforme}', name: 'app_informes_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        Informes $informe,
        InformesRepository $informesRepository
    ): Response {
        if (
            $this->isCsrfTokenValid(
                'delete' . $informe->getIdinforme(),
                $request->request->get('_token')
            )
        ) {
            $informesRepository->remove($informe, true);
        }

        return $this->redirectToRoute(
            'app_informes_index',
            [],
            Response::HTTP_SEE_OTHER
        );
    }
}
