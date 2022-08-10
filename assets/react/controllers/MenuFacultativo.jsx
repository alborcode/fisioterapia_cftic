// assets/react/controllers/MyComponent.jsx

import React from 'react';
import Container from 'react-bootstrap/Container';
import Nav from 'react-bootstrap/Nav';
import Navbar from 'react-bootstrap/Navbar';
import NavDropdown from 'react-bootstrap/NavDropdown';

function MenuFacultativo(props) {
  return (
    <Navbar bg="light" expand="lg">
        <Container>
            {/* <Navbar.Brand href="{{ path('ModificarPerfil', {idusuario: props.usuario}) }}">Perfil</Navbar.Brand> */}
            <Navbar.Toggle aria-controls="basic-navbar-nav" />
            <Navbar.Collapse id="basic-navbar-nav">
                <Nav className="me-auto">
                    <Nav.Link href="{{ path('app_citas_index', {idusuario: props.usuario}) }}">Gestion Citas</Nav.Link>
                    <NavDropdown title="Informes" id="basic-nav-dropdown">
                        <NavDropdown.Item href="{{ path('app_informes_index', {idusuario: props.usuario}) }}">
                            Informes
                        </NavDropdown.Item>
                        <NavDropdown.Item href="{{ path('app_rehabilitaciones_index') }}">
                            Rehabilitaciones
                        </NavDropdown.Item>
                    </NavDropdown> 
                    <NavDropdown title="Perfil" id="basic-nav-dropdown">
                        <NavDropdown.Item href="{{ path('ModificarPerfil', {idusuario: props.usuario}) }}">
                            Modificar Perfil
                        </NavDropdown.Item>
                        <NavDropdown.Divider />
                        <NavDropdown.Item href="{{ path('app_vacaciones_index') }}">
                            Gestion Vacaciones
                        </NavDropdown.Item>
                    </NavDropdown> 
                    <Nav.Link href="{{ path('app_logout') }}">Cerrar Sesion</Nav.Link>
                </Nav>
            </Navbar.Collapse>
        </Container>
    </Navbar>
  );
}

export default MenuFacultativo;