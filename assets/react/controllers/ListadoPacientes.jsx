import React from 'react';
  
const ListadoPacientes = (props) => {
    console.log(props);
	return (
            <table className='table'>
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Telefono</th>
                        <th>Accion</th>
                    </tr>
                </thead>
            <tbody> 
                    {
                        props.pacientes.map( (paciente, index) => {
                            <tr key={paciente.idpaciente}>
                                <td scope="row"> {paciente.nombre} {paciente.apellido1} {paciente.apellido2}</td>
                                <td>{paciente.telefono}</td>
                                <td>
                                    <a href="{{ path('mostrarPacienteAdmin', {idpaciente: Paciente.idpaciente }) }}">Editar</a>
                                </td>
                            </tr>
                        })
                    }
            </tbody>
            </table>
    );
}

export default ListadoPacientes;