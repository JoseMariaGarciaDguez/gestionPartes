<?php

class Vehiculo_model extends CI_Model
{

    public $id;
    public $nombre;
    public $matricula;
    public $observaciones;
    public $estado;
    public $plazas;
    public $modelo;
    public $avatar;
    public $byEmpleadoID;

    public function __construct($id = null)
    {
        parent::__construct();
        if ($id != null) $this->get($id);
    }

    //Obtiene una vehiculo
    public function get($id)
    {
        $query = $this->db->get_where('vehiculos', array('id' => $id));

        $query = $query->result_array();

        foreach ($query as $row) {
            $this->id = $row['id'];
            $this->nombre = $row['nombre'];
            $this->matricula = $row['matricula'];
            $this->observaciones = $row['observaciones'];
            $this->estado = $row['estado'];
            $this->plazas = $row['plazas'];
            $this->modelo = $row['modelo'];
            $this->avatar = $row['avatar'];
            $this->byEmpleadoID = $row['byEmpleadoID'];
            break;
        }

    }

    public function obtenerTabla($selector)
    {
        return $this->db->select($selector)->get("vehiculos");
    }

    public function borrar()
    {
        $this->db->delete('vehiculos', array('id' => $this->id));
    }

    public function crear($array)
    {
        $this->db->insert('vehiculos', $array);
        $row = $this->db->select('id')->limit(1)->order_by('id', 'DESC')->get("vehiculos")->row();
        $this->id = $row->id;
        $this->get($this->id);
    }

    public function comprobarReferencia($ref)
    {
        $query = $this->db->get_where('vehiculos', array('ref' => $ref));
        if ($query->num_rows() > 0) return true; else return false;
    }

    public function comprobarMatricula($matricula)
    {
        $query = $this->db->get_where('vehiculos', array('matricula' => $matricula));
        if ($query->num_rows() > 0) return true; else return false;
    }

    public function comprobarEstadosAsignados($vehiculoID)
    {
        $vehiculoModel = new Vehiculo_model($vehiculoID);

        $fecha = date('d/m/Y');
        $this->db->where('fecha', $fecha);
        $query = $this->db->get('asignaciones');
        $esta = false;

        foreach ($query->result() as $r) {
            if (in_array($vehiculoModel->id, json_decode($r->vehiculosID))) {
                $esta = true;
                break;
            }
        }

        if (!$esta && $vehiculoModel->estado == 'OC') {
            $vehiculoModel->editar(null, array("estado" => 'LI'));
            return true;
        }

        return false;
    }

    public function editar($id = null, $array)
    {
        if (is_null($id))
            $this->db->where('id', $this->id);
        else
            $this->db->where('id', $id);

        $this->db->update('vehiculos', $array);
        $this->get($id);
    }

    public function contadorDeTiposHTML($empleado)
    {
        $editar = $borrar = $crear = '';

        if ($empleado->rol->tieneRol($this->uri->segment(1) . "_borrar_todos", $empleado)) {
            $borrar = '<button type="button" data-target="#modalBorrarVehiculos" data-toggle="modal" class="btn btn-danger" id="borrarSeleccionTabla"><i class="fa fa-trash"></i></button>';
        }

        if ($empleado->rol->tieneRol($this->uri->segment(1) . "_editar_todos", $empleado)) {
            $editar = '<button type="button" data-target="#modalEditarVehiculos" data-toggle="modal" class="btn btn-info" id="editarSeleccionTabla"><i class="fa fa-edit"></i></button>';
        }

        if ($empleado->rol->tieneRol($this->uri->segment(1) . "_crear", $empleado)) {
            $crear = '<a type="button" href="' . base_url() . 'vehiculo/crear" class="btn btn-success" ><i class="fa fa-plus"></i></a>';
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
			<div class="filtradoEstados">
				<button class="btn btn-info estadoTodos" data-search=" ">Todos (' . $this->obtenerContadorEstados(array(), $empleado) . ')</button>
				<button class="btn btn-' . $this->traducirEstadoColor('OC') . ' estado" data-search="Ocupado">Ocupado (' . $this->obtenerContadorEstados(array("estado" => 'OC'), $empleado) . ')</button>
				<button class="btn btn-' . $this->traducirEstadoColor('LI') . ' estado" data-search="Libre">Libre (' . $this->obtenerContadorEstados(array("estado" => 'LI'), $empleado) . ')</button>
				<button class="btn btn-' . $this->traducirEstadoColor('RE') . ' estado" data-search="Reparación">Reparación (' . $this->obtenerContadorEstados(array("estado" => 'RE'), $empleado) . ')</button>
			</div>
		</div>
		
		';
    }

    public function obtenerContadorEstados($array, $empleado)
    {
        if (!is_null($empleado) && !$empleado->rol->tieneRol('vehiculo_ver_todos', $empleado)) {
            $array["byEmpleadoID"] = $empleado->id;
        }
        return $this->db->get_where("vehiculos", $array)->num_rows();
    }

    public function traducirEstadoColor($codigoEstado)
    {
        switch ($codigoEstado) {
            case "OC":
                return "danger";
            case "LI":
                return "success";
            case "RE":
                return "warning";
        }

    }

    public function traducirEstado($codigoEstado)
    {
        switch ($codigoEstado) {
            case "OC":
                return "Ocupado";
            case "LI":
                return "Libre";
            case "RE":
                return "Reparación";
        }

    }

    public function insertarAvatar($avatar)
    {
        if (file_exists('assets/images/temp/' . $avatar)) {
            $this->db->where('id', $this->id);
            $newName = $this->id . '.' . pathinfo($avatar, PATHINFO_EXTENSION);
            $this->db->update('vehiculos', array('avatar' => $newName));
            rename('assets/images/temp/' . $avatar, 'assets/images/vehiculo/' . $newName);
        }
    }

}
