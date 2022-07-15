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

    // Ruta de Operaciones
    #[Route('/conexion', name: 'conexionApp')]
    public function conexionApp(Request $request, EntityManagerInterface $em)
    { 
        // Recuperamos valor de boton que se ha pulsado en login
        $boton= $request->request->get('login');
        // Recupero los datos de usuario del formulario
        $email      = $request->request->get('txtEmail');
        $password   = $request->request->get('txtPassword');

        // Controlo el value de login (todos los botones tienen mismo name)
        // Si es conexion se busca usuario/contraseña en base de datos
        if ($boton=='conexion'){
            // Recupero los datos del usuario que se conecta de la BBDD
            //$query = $em->createQuery("SELECT u FROM App\Entity\Usuarios u WHERE u.email = :email AND u.password = :password");
            //$query->setParameter('email', $email);
            //$query->setParameter('password', $password);
            //$datosUsuario = $query->getResult();       
            // Recupero los datos del usuario que se conecta de la BBDD
            $datosUsuario = $em->getRepository(Usuarios::class)->findOneBy(array(
                'email' => $email, 
                'password' => $password
            ));;
            
            // Si no existen datos del usuario/password
            if(!$datosUsuario) {
                // Establece mensaje Error devolviendo control a ventana de Login
                $mensaje = 'Error Usuario no existe';
                return $this->render('login.html.twig', 
                [
                    'mensaje' => $mensaje
                ]);
            }else{
                // Establece mensaje conexion correcta
                $mensaje = 'Usuario conectado correctamente';
                // Recuperamos el Tipo de Usuario de registro leido
                $tipousuario = $datosUsuario->getTipousuario();
                // Controlamos tipo de usuario
                switch ($tipousuario) {
                    // Si es Paciente se redirige a Pagina Inicial de Pacientes
                    case 'PACIENTE':
                        return $this->render('pacienteIni.html.twig', 
                        [
                            'mensaje' => $mensaje
                        ]);
                        break;
                    // Si es Facultativo se redirige a Pagina Inicial de Pacientes
                    case 'FACULTATIVO':
                        return $this->render('facultativoIni.html.twig', 
                        [
                            'mensaje' => $mensaje
                        ]);
                        break;
                    // Si es Administrativo se redirige a Pagina Inicial de Pacientes
                    case 'ADMINISTRATIVO':
                        return $this->render('administrativoIni.html.twig', 
                        [
                            'mensaje' => $mensaje
                        ]);
                        break;
                }
            }
        }
        // Si es registrar se llama a formulario de Alta de Paciente
        if ($boton=='registrar'){
            // Damos de Alta en tabla de Usuarios
             
            $usuario = new Usuarios();
            $usuario->setEmail($email);
            $usuario->setPassword($password);
            $usuario->setTipousuario('PACIENTE');
                     
            $em->persist($usuario);
            $em->flush();

            // Recupero el id de usuario en variable para enviarla al alta de pacientes
            //$idusuario = $usuario -> getIdusuario();
            $mensaje = 'Usuario dado de Alta, datos para dar de alta Paciente';
             
            // Redirigimos conexion a Pagina Registro de Pacientes mandando el IdUsuario
            return $this->render('paciente.html.twig', 
            [
                'objetousuario' => $usuario,
                'mensaje'   => $mensaje
            ]);
        }
    }

}