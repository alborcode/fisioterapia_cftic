<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Usuarios;
use Doctrine\ORM\EntityManagerInterface;

class UsuariosController extends AbstractController
{

    // Formulario de Conexion
    #[Route('/login', name: 'login')]
    public function conexion()
    { 
        return $this->render('login.html.twig');
    }

    // Buscar Datos Usuario
    #[Route('/user', name: 'buscarUsuario')]
    public function buscarUsuario(Request $request, EntityManagerInterface $em)
    { 
        // Recupero los datos de usuario del formulario
        $email = $request->request->get('txtEmail');
        $password = $request->request->get('txtPassword');
      
        // Recupero los datos del usuario que se conecta de la BBDD
        $datosUsuario = $em->getRepository(Usuarios::class)->findOneBy([
            'Email' => $email,
            'Password' => $password,
        ]);

        // Si no existen datos del usuario/password
        if(!$datosUsuario) {
            // Establece mensaje Flash
            $this->addFlash(
                'notice',
                'Error datos de Usuario no existen'
            );
            // Redirige de Nuevo a Pagina de Login
            return $this->redirectToRoute("login.html.twig");
            
        }else{
            // Establece mensaje Flash
            $this->addFlash(
                'notice',
                'Usuario conectado correctamente'
            );
            
            //***** */
            switch ($datosUsuario->$em->getTipousuario()) {
                // Si es Paciente se redirige a Pagina Inicial de Pacientes
                case 'PACIENTE':
                    return $this->redirectToRoute("paciente.html.twig");
                    break;
                // Si es Facultativo se redirige a Pagina Inicial de Pacientes
                case 'FACULTATIVO':
                    return $this->redirectToRoute("facultativo.html.twig");
                    break;
                // Si es Administrativo se redirige a Pagina Inicial de Pacientes
                case 'ADMINISTRATIVO':
                    return $this->redirectToRoute("administrativo.html.twig");
                    break;
            }
        }
    }

    // Registrar nuevo Usuario
    #[Route('/newuser', name: 'altaUsuario')]
    public function altaUsuario(Request $request, EntityManagerInterface $em)
    { 
        // Recupero los datos de usuario del formulario
        $email = $request->request->get('txtEmail');
        $password = $request->request->get('txtPassword');

        // Nueva instancia de Usuario      
        $usuario = new Usuarios();
        $usuario->setEmail($email);
        $usuario->setPassword($password);
        // Por defecto todos los usuarios se dan de alta como Pacientes (Los puede modificar el Administrativo)
        $usuario->setTipousuario('PACIENTE');
                
        $em->persist($usuario);
        $em->flush();

        // Recuperamos el Id de Usuario dado de alta
         $datosUsuario = $em->getRepository(Usuarios::class)->findOneBy([
            'Email' => $email,
            'Password' => $password,
        ]);
        
        //****** */
        // Redirigimos conexion a Pagina Inicial de Pacientes mandando el IdUsuario
        return $this->redirectToRoute("paciente.html.twig",
            ['idusuario' => $datosUsuario->getIdusuario()]
        );
            
    }
}