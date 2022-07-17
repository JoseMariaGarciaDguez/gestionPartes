<?php

class Obra_model extends CI_Model
{

    public $id;
    public $nombre;
    public $descripcion;
    public $estado;
    public $clienteID;
    public $byEmpleadoID;
    public $fechaCreacion;
    public $avatar;

    public function __construct($id = null)
    {
        parent::__construct();
        if ($id != null) $this->getObra($id);
    }

    //Obtiene un usuario
    public function getObra($id)
    {
        $query = $this->db->get_where('obras', array('id' => $id));

        $query = $query->result_array();

        foreach ($query as $row) {
            $this->id = $row['id'];
            $this->nombre = $row['nombre'];
            $this->descripcion = $row['descripcion'];
            $this->estado = $row['estado'];
            $this->clienteID = $row['clienteID'];
            $this->avatar = $row['avatar'];
            $this->fechaCreacion = $row['fechaCreacion'];
            $this->byEmpleadoID = $row['byEmpleadoID'];
            break;
        }

    }

    public function traducirObra($obraID)
    {
        if (is_numeric($obraID)) {
            $query = $this->db->select('nombre')->get_where('obras', array('id' => $obraID));

            $query = $query->result_array();

            foreach ($query as $row) {
                return $row['nombre'];
            }
        }
        return $obraID;

    }

    //Hay que eliminar esta funcion y sustituirla por la de abajo obtenerTabla
    public function obtenerObrasTabla()
    {
        return $this->db->get("obras");
    }

    public function obtenerTabla($selector)
    {
        return $this->db->select($selector)->get("obras");
    }


    public function obtenerObrasActivasTabla()
    {
        return $this->db->get_where("obras", array("estado" => 'A'));
    }

    public function traducirEstado($codigoEstado)
    {
        switch ($codigoEstado) {
            case "A":
                return "Activa";
            case "P":
                return "Pendiente";
            case "C":
                return "Cancelada";
            case "F":
                return "Finalizada";
        }

    }

    public function traducirEstadoColor($codigoEstado)
    {
        switch ($codigoEstado) {
            case "A":
                return "success";
            case "P":
                return "warning";
            case "C":
                return "danger";
            case "F":
                return "primary";
        }

    }

    public function borrarObra()
    {
        $this->db->delete('obras', array('id' => $this->id));
    }

    public function editarObra($array)
    {
        $this->db->where('id', $this->id);
        $this->db->update('obras', $array);

        if ($array["estado"] == 'F' || $array["estado"] == 'C') $this->actualizarEstadoParte('C', $this->id);
        $this->getObra($this->id);
    }

    public function actualizarEstadoParte($estado, $obraID)
    {
        $this->db->query("UPDATE sge_partes SET estado='$estado' WHERE obraID='$obraID'");
    }

    public function crearObra($array)
    {
        $this->db->insert('obras', $array);
        $row = $this->db->select('id')->limit(1)->order_by('id', 'DESC')->get("obras")->row();
        $this->id = $row->id;
        $this->getObra($this->id);
    }

    public function contadorDeTiposHTML($empleado)
    {
        $editar = $borrar = $crear = '';

        if ($empleado->rol->tieneRol($this->uri->segment(1) . "_borrar_todos", $empleado)) {
            $borrar = '<button type="button" data-target="#modalBorrarObras" data-toggle="modal" class="btn btn-danger" id="borrarSeleccionTabla"><i class="fa fa-trash"></i></button>';
        }

        if ($empleado->rol->tieneRol($this->uri->segment(1) . "_editar_todos", $empleado)) {
            $editar = '<button type="button" data-target="#modalEditarObras" data-toggle="modal" class="btn btn-info" id="editarSeleccionTabla"><i class="fa fa-edit"></i></button>';
        }

        if ($empleado->rol->tieneRol($this->uri->segment(1) . "_crear", $empleado)) {
            $crear = '<a type="button" href="' . base_url() . 'obra/crear" class="btn btn-success" ><i class="fa fa-plus"></i></a>';
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
			<div class="filtradoEstadosObras">
				<button class="btn btn-info estadoTodos" data-search=" ">Todas (' . $this->obtenerContadorEstados(array(), $this->empleado) . ')</button>
				<button class="btn btn-success estadoA" data-search="Activa">Activa (' . $this->obtenerContadorEstados(array("estado" => 'A'), $this->empleado) . ')</button>
				<button class="btn btn-warning estadoP" data-search="Pendiente">Pendiente (' . $this->obtenerContadorEstados(array("estado" => 'P'), $this->empleado) . ')</button>
				<button class="btn btn-danger estadoC" data-search="Cancelada">Cancelada (' . $this->obtenerContadorEstados(array("estado" => 'C'), $this->empleado) . ')</button>
				<button class="btn btn-primary estadoD" data-search="Finalizada">Finalizada (' . $this->obtenerContadorEstados(array("estado" => 'F'), $this->empleado) . ')</button>

			</div>
		</div>
		
		';
    }

    public function obtenerContadorEstados($array, $empleado)
    {
        if (!is_null($empleado) && !$empleado->rol->tieneRol('obra_ver_todos', $empleado)) {
            $array["byEmpleadoID"] = $empleado->id;
        }
        return $this->db->get_where("obras", $array)->num_rows();
    }

    public function insertarAvatar($avatar)
    {
        if (file_exists('assets/images/temp/' . $avatar)) {
            $this->db->where('id', $this->id);
            $newName = $this->id . '.' . pathinfo($avatar, PATHINFO_EXTENSION);
            $this->db->update('obras', array('avatar' => $newName));
            rename('assets/images/temp/' . $avatar, 'assets/images/obras/' . $newName);
        }
    }

}
