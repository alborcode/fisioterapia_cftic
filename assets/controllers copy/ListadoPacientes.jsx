import React from 'react';
  
const ListadoPacientes = (pacientes) => {
	return (
            <tbody>
                {
                    pacientes.map( (paciente, index) => (
                        <tr key={index}>
                            <td scope="row"> {paciente.nombre} {paciente.apellido1} {paciente.apellido2}</td>
                            <td>{paciente.telefono}</td>
                            <td>
                                <a href="{{ path('mostrarPacienteAdmin', {idpaciente: Paciente.idpaciente }) }}">Editar</a>
                            </td>
                        </tr>
                    ))
                }
            </tbody>
    );
}

export default ListadoPacientes;