// assets/react/controllers/MyComponent.jsx

import React from 'react';
import Container from 'react-bootstrap/Container';
import Nav from 'react-bootstrap/Nav';
import Navbar from 'react-bootstrap/Navbar';
import NavDropdown from 'react-bootstrap/NavDropdown';

function MenuAdministrativo(props) {
  return (
    <Navbar bg="light" expand="lg">
        <Container>
            {/* <Navbar.Brand href="{{ path('ModificarPerfil', {idusuario: props.usuario}) }}">Perfil</Navbar.Brand> */}
            <Navbar.Toggle aria-controls="basic-navbar-nav" />
            <Navbar.Collapse id="basic-navbar-nav">
                <Nav className="me-auto">
                    <Nav.Link href="{{ path('app_citas_new', {idusuario: props.usuario}) }}">Pedir Cita Paciente</Nav.Link>
                    <NavDropdown title="Pacientes" id="basic-nav-dropdown">
                        <NavDropdown.Item href="{{ path('app_informes_index', {idusuario: props.usuario}) }}">
                            Gestion Pacientes
                        </NavDropdown.Item>
                        <NavDropdown.Divider />
                        <NavDropdown.Item href="{{ path('app_citas_new', {idusuario: props.usuario}) }}">
                            Gestion Citas
                        </NavDropdown.Item>
                    </NavDropdown> 
                    <NavDropdown title="Facultativos" id="basic-nav-dropdown">
                        <NavDropdown.Item href="{{ path('app_facultativos_index', {idusuario: props.usuario}) }}">
                            Gestion Facultativos
                        </NavDropdown.Item>
                        <NavDropdown.Divider />
                        <NavDropdown.Item href="{{ path('app_turnos_index', {idusuario: props.usuario}) }}">
                            Turnos
                        </NavDropdown.Item>
                        <NavDropdown.Item href="{{ path('app_vacaciones_index') }}">
                            Vacaciones
                        </NavDropdown.Item>
                    </NavDropdown> 
                    <Nav.Link href="{{ path('app_aseguradoras_index'}}">Gestion Aseguradoras</Nav.Link>
                    <Nav.Link href="{{ path('app_logout') }}">Cerrar Sesion</Nav.Link>
                </Nav>
            </Navbar.Collapse>

        </Container>
    </Navbar>
  );
}

export default MenuAdministrativo;