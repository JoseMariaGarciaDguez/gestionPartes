<?php

class Albaran_model extends CI_Model
{

    public $id;
    public $nombreJuridico;
    public $nombre;
    public $email;
    public $apellidos;
    public $nif;
    public $clienteID;
    public $fechaCreacion;
    public $articulos;
    public $estado;
    public $enviado;
    public $nombreAlbaran;
    public $total;
    public $byEmpleadoID;

    public function __construct($id = null)
    {
        parent::__construct();
        if ($id != null) $this->get($id);
    }

    //Obtiene una albaran
    public function get($id)
    {
        $query = $this->db->get_where('albaranes', array('id' => $id));

        $query = $query->result_array();

        foreach ($query as $row) {
            $this->id = $row['id'];
            $this->nombreJuridico = $row['nombreJuridico'];
            $this->nombre = $row['nombre'];
            $this->email = $row['email'];
            $this->apellidos = $row['apellidos'];
            $this->nif = $row['nif'];

            if ($row['clienteID'] != null)
                $this->clienteID = new Cliente_model($row['clienteID']);
            else
                $this->clienteID = null;

            $this->fechaCreacion = $row['fechaCreacion'];
            $this->estado = $row['estado'];
            $this->enviado = $row['enviado'];
            $this->total = $row['total'];
            $this->nombreAlbaran = $row['nombreAlbaran'];
            $this->byEmpleadoID = $row['byEmpleadoID'];
            $this->articulos = json_decode($row['articulos']);
            break;
        }

    }

    public function obtenerTabla($selector)
    {
        return $this->db->select($selector)->get("albaranes");
    }

    public function borrar()
    {
        $this->db->delete('albaranes', array('id' => $this->id));
    }

    public function crear($array)
    {
        $this->db->insert('albaranes', $array);
    }

    public function editar($id, $array)
    {
        $this->db->where('id', $id);
        $this->db->update('albaranes', $array);
        $this->get($id);
    }

    public function comprobarReferencia($ref)
    {
        $query = $this->db->get_where('albaranes', array('ref' => $ref));
        if ($query->num_rows() > 0) return true; else return false;
    }

    public function obtenerTotalArticulos()
    {
        $total = 0.00;
        foreach (json_decode($this->articulos) as $articulo) {
            $total = floatval($total) + floatval($articulo[2] * $articulo[1]);
        }
        return $total;
    }

    public function traducirEstado($codigoEstado)
    {
        switch ($codigoEstado) {
            case "PA":
                return "Pagada";
            case "PE":
                return "Pendiente";
            case "CA":
                return "Cancelada";
        }

    }

    public function traducirEstadoColor($codigoEstado)
    {
        switch ($codigoEstado) {
            case "PA":
                return "success";
            case "PE":
                return "warning";
            case "CA":
                return "danger";
        }

    }

    public function contadorDeTiposHTML($empleado)
    {

        $editar = $borrar = $crear = '';

        if ($empleado->rol->tieneRol($this->uri->segment(1) . "_borrar_todos", $empleado)) {
            $borrar = '<button type="button" data-target="#modalBorrarAlbaranes" data-toggle="modal" class="btn btn-danger" id="borrarSeleccionTabla"><i class="fa fa-trash"></i></button>';
        }

        if ($empleado->rol->tieneRol($this->uri->segment(1) . "_editar_todos", $empleado)) {
            $editar = '<button type="button" data-target="#modalEditarAlbaranes" data-toggle="modal" class="btn btn-info" id="editarSeleccionTabla"><i class="fa fa-edit"></i></button>';
        }

        if ($empleado->rol->tieneRol($this->uri->segment(1) . "_crear", $empleado)) {
            $crear = '<a type="button" href="' . base_url() . 'albaran/crear" class="btn btn-success" ><i class="fa fa-plus"></i></a>';
        }

        return '
		<div class="col-sm-3">
			' . $crear . '
			<button type="button" class="btn btn-info seleccionarTodos" ><i class="fa fa-check-double"></i></button>
			<div class="btn-group accionSeleccion" style="display:none; vertical-align: top;">
				' . $borrar . '
				' . $editar . '
			</div>
		</div>
		<div class="col-sm-9">
			<div class="filtradoEstadosAlbaranes">
				<button class="btn btn-info estadoTodos" data-search=" ">Todos (' . $this->obtenerContadorEstados(array(), $empleado) . ')</button>
				<button class="btn btn-warning estado" data-search="Pendiente">Pendiente (' . $this->obtenerContadorEstados(array("estado" => 'PE'), $empleado) . ')</button>
				<button class="btn btn-success estado" data-search="Pagada">Pagada (' . $this->obtenerContadorEstados(array("estado" => 'PA'), $empleado) . ')</button>
				<button class="btn btn-danger estado" data-search="Cancelada">Cancelada (' . $this->obtenerContadorEstados(array("estado" => 'CA'), $empleado) . ')</button>

			</div>
		</div>
		
		';
    }

    public function obtenerContadorEstados($array, $empleado)
    {
        if (!is_null($empleado) && !$empleado->rol->tieneRol('albaran_ver_todos', $empleado)) {
            $array["byEmpleadoID"] = $empleado->id;
        }
        return $this->db->get_where("albaranes", $array)->num_rows();
    }

    public function obtenerHorasObras($id)
    {
        $query = $this->db->get_where('partes', array("obraID" => $id));
        $suma = 0;
        foreach ($query->result() as $row) {
            $suma = $suma + $row->horas;
        }
        return $suma;
    }

}
