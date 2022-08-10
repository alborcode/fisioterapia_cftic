<?php

namespace App\Controller;

use App\Entity\Citas;
use App\Form\CitasType;
use App\Repository\CitasRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/citas')]
class CitasController extends AbstractController
{
    #[Route('/', name: 'app_citas_index', methods: ['GET'])]
    public function index(CitasRepository $citasRepository): Response
    {
        return $this->render('citas/index.html.twig', [
            'citas' => $citasRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_citas_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        CitasRepository $citasRepository
    ): Response {
        $cita = new Citas();
        $form = $this->createForm(CitasType::class, $cita);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $citasRepository->add($cita, true);

            return $this->redirectToRoute(
                'app_citas_index',
                [],
                Response::HTTP_SEE_OTHER
            );
        }

        return $this->renderForm('citas/new.html.twig', [
            'cita' => $cita,
            'form' => $form,
        ]);
    }

    #[Route('/{idcita}', name: 'app_citas_show', methods: ['GET'])]
    public function show(Citas $cita): Response
    {
        return $this->render('citas/show.html.twig', [
            'cita' => $cita,
        ]);
    }

    #[Route('/{idcita}/edit', name: 'app_citas_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        Citas $cita,
        CitasRepository $citasRepository
    ): Response {
        $form = $this->createForm(CitasType::class, $cita);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $citasRepository->add($cita, true);

            return $this->redirectToRoute(
                'app_citas_index',
                [],
                Response::HTTP_SEE_OTHER
            );
        }

        return $this->renderForm('citas/edit.html.twig', [
            'cita' => $cita,
            'form' => $form,
        ]);
    }

    #[Route('/{idcita}', name: 'app_citas_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        Citas $cita,
        CitasRepository $citasRepository
    ): Response {
        if (
            $this->isCsrfTokenValid(
                'delete' . $cita->getIdcita(),
                $request->request->get('_token')
            )
        ) {
            $citasRepository->remove($cita, true);
        }

        return $this->redirectToRoute(
            'app_citas_index',
            [],
            Response::HTTP_SEE_OTHER
        );
    }

    // Añadimos funcion para generar el justificante de la cita
    #[Route('/justificante', name: 'app_citas_justificante')]
    public function citas_justificante(
        Request $request,
        EntityManagerInterface $em
    ) {
        // Se recoge con get el paciente y la fecha de la cita
        $paciente = $request->query->get('paciente');
        $fecha = $request->query->get('fecha');
        dump($paciente);
        dump($fecha);
        // Busco por codigo de Paciente en las Citas para sacar la hora
        $datoscitas = $em
            ->getRepository(Citas::class)
            ->findbyIdPaciente($paciente);
        // Recuperamos la hora de la cita con esa fecha
        //$hora = $datoscitas->getHora();

        // Preparamos la página HTML para generar PDF generando un objeto renderview
        $html = $this->renderView('JustificantePDF.html.twig', [
            'datosInforme' => $datos,
        ]);

        // Existen muchas configuraciones para Dompdf. Incluimos una de las muchas que tiene por ejemplo asignar el tipo de letra
        $pdfOptions = new Options();
        // Tipo de Letra con la que escribir
        $pdfOptions->set('defaultFont', 'Arial');
        // Crea una instancia de Dompdf con nuestras opciones
        $dompdf = new Dompdf($pdfOptions);

        // Ahora se carga la página HTML en Dompdf
        $html .=
            '<link type="text/css" href="/public/bootstrap/css/bootstrap.min.css" rel="stylesheet" />';
        $dompdf->loadHtml($html);

        // También podemos de forma opcional indicar el tamaño del papel y la orientación
        $dompdf->setPaper('A4', 'portrait');

        // Renderiza el HTML como PDF
        $dompdf->render();

        // Podemos generar el pdf y visualizarlo en el navegador si modificamos la propiedad Attachment al valor false.
        $dompdf->stream('JustificanteCita.pdf', [
            'Attachment' => false,
        ]);
    }
}
