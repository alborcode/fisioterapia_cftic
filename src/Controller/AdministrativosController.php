<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

///** * @Security("is_granted('ROLE_ADMINISTRATIVO'), statusCode=404, message="No tiene acceso a este recurso.") */  
class FacultativosController extends AbstractController
{

    // Formulario de Conexion
    #[Route('/loginadministrativo', name: 'loginadministrativo')]
    public function loginadministrativo()
    { 
        return $this->render('administrativo.html.twig');
    }

    // Registrar nuevo usuario Administrativo
    #[Route('/newadministrativo', name: 'altaAdministrativo')]
    public function altaFacultativo(Request $request, EntityManagerInterface $em)
    { 
         // Recupero los datos de usuario del formulario
         $email = $request->request->get('txtEmail');
         $password = $request->request->get('txtPassword');
 
         // Nueva instancia de Usuario      
         $usuario = new User();
         $usuario->setEmail($email);
         $usuario->setPassword($password);
         // Formulario para dar de Alta como usuario Administrativo
         // $usuario->setTipousuario('ADMINISTRATIVO');
                 
         $em->persist($usuario);
         $em->flush();
        
        // Redirigimos conexion a Pagina Inicial de Administrativo
        return $this->redirectToRoute("administrativo.html.twig");
            
    }

}