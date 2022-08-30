import React, { Component } from 'react';

export default class extends Component {
    constructor(props) {
      super(props);
      this.state = { pacientes: '' };
    }

    render() {
        return ( 
            <div>
                <input
                    value={this.state.search}
                    onChange={(event) => this.setState({search: event.target.value})}
                />

                <div className="row">
                    {this.filteredPackages().map(item => (
                        <a key={item.name} href={item.url}>
                            <img src={item.imageUrl} />
                            <h4>{item.humanName}</h4>
                        </a>
                    ))}
                </div>
            </div>
        );
    }

  
const ListadoPacientes = (props) => {
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
                        props.pacientes.map( (paciente, index) => (
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
            </table>
    );
}

export default ListadoPacientes;