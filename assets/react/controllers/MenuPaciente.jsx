// assets/react/controllers/MyComponent.jsx

import React from 'react';
import Container from 'react-bootstrap/Container';
import Nav from 'react-bootstrap/Nav';
import Navbar from 'react-bootstrap/Navbar';
import NavDropdown from 'react-bootstrap/NavDropdown';

function MenuPaciente(props) {
  return (
    <Navbar bg="light" expand="lg">
        <Container>
            {/* <Navbar.Brand href="#home">Inicio</Navbar.Brand> */}
            <Navbar.Toggle aria-controls="basic-navbar-nav" />
            <Navbar.Collapse id="basic-navbar-nav">
                <Nav className="me-auto">
                    <Nav.Link href="{{path('app_citas_new', {props.usuario}) }}">Solicitar Cita</Nav.Link>
                    <Nav.Link href="{{path('ModificarPerfil', {props.usuario}) }}">Modificar Perfil</Nav.Link>
                    <Nav.Link href="{{path('app_logout') }}">Cerrar Sesion</Nav.Link>
                </Nav>
            </Navbar.Collapse>
        </Container>
    </Navbar>
  );
}

export default MenuPaciente;