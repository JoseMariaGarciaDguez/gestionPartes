<?php

class Rol_model extends CI_Model
{

    public $id;
    public $nombre;
    public $permisos;
    public $byEmpleadoID;

    public function __construct($id = null)
    {
        parent::__construct();
        if ($id != null) $this->get($id);
    }

    //Obtiene una rol
    public function get($id)
    {
        $query = $this->db->get_where('roles', array('id' => $id));

        $query = $query->result_array();

        foreach ($query as $row) {
            $this->id = $row['id'];
            $this->nombre = $row['nombre'];
            $this->byEmpleadoID = $row['byEmpleadoID'];
            $this->permisos = json_decode($row['permisos']);
            break;
        }

    }

    public function obtenerTabla($selector)
    {
        return $this->db->select($selector)->get("roles");
    }

    public function borrar()
    {
        $this->db->delete('roles', array('id' => $this->id));
    }

    public function crear($array)
    {
        $this->db->insert('roles', $array);
    }

    public function editar($id, $array)
    {
        $this->db->where('id', $id);
        $this->db->update('roles', $array);
        $this->get($id);
    }

    public function comprobarNombre($nombre)
    {
        $query = $this->db->get_where('roles', array('nombre' => $nombre));
        if ($query->num_rows() > 0) return true; else return false;
    }

    public function contadorDeTiposHTML($empleado)
    {
        $crear = $borrar = '';
        if ($empleado->rol->tieneRol($this->uri->segment(1) . "_crear", $empleado)) $crear = '<a type="button" href="' . base_url() . 'rol/crear" class="btn btn-success" ><i class="fa fa-plus"></i></a>';
        if ($empleado->rol->tieneRol($this->uri->segment(1) . "_borrar_todos", $empleado)) $borrar = '<button type="button" data-target="#modalBorrarRoles" data-toggle="modal" class="btn btn-danger" id="borrarSeleccionTabla"><i class="fa fa-trash"></i></button>';

        return '
		<div class="col-sm-12 espacioAbajo">	    
			' . $crear . '
			<button type="button" class="btn btn-info seleccionarTodos" ><i class="fa fa-check-double"></i></button>
			<div class="btn-group accionSeleccion" style="display:none; vertical-align: top;">   
			    ' . $borrar . '
			</div>
		</div>
		
		';
    }

    public function tieneRol($rol, $empleado)
    {
        if ($empleado->rolID == -1 && $rol != "asignador_ser_asignado") return true;
        return in_array($rol, json_decode($empleado->rol->permisos));
    }

    public function esSuyo($segmento, $empleadoID, $id)
    {
        if ($segmento == "albaran") $segmento = "albarane";
        if ($segmento == "asignador") $segmento = "asignacione";
        if ($segmento == "rol") $segmento = "role";
        $query = $this->db->get_where($segmento . "s", array("byEmpleadoID" => $empleadoID, "id" => $id));
        if ($query->num_rows() == 0) return false;
        else return true;
    }

    public function botoneraHTML($segmento, $empleado, $r, $dataTarget, $class)
    {
        $es_suyo = $empleado->rol->esSuyo($segmento, $empleado->id, $r->id);
        $participa = false;
        if ($segmento == "parte" && in_array($empleado->id, json_decode($r->empleadosID))) $participa = true;
        $editar = $borrar = $ver = "";

        if ($segmento == "asignador") $r->id = str_replace('/', '-', $r->fecha);
        if ($segmento == "rol" && isset($r) && $r->id == -1) return $editar . $borrar . $ver;

        if ($participa || $es_suyo) {
            if ($empleado->rol->tieneRol($segmento . "_editar_creados", $empleado) || $empleado->rol->tieneRol($segmento . "_editar_participado", $empleado))
                $editar = ' <a href="' . base_url() . '' . $segmento . '/editar/' . $r->id . '"class="btn btn-warning"><i class="fa fa-edit"></i> Editar</a>';

            if ($empleado->rol->tieneRol($segmento . "_borrar_creados", $empleado) || $empleado->rol->tieneRol($segmento . "_borrar_participado", $empleado))
                $borrar = '<span data-toggle="modal" data-target="' . $dataTarget . '" data-id="' . $r->id . '" class="' . $class . ' btn btn-danger"><i class="fa fa-trash-alt"></i> Borrar</span>';

            if ($empleado->rol->tieneRol($segmento . "_ver_creados", $empleado) || $empleado->rol->tieneRol($segmento . "_ver_participado", $empleado))
                $ver = '<a href="' . base_url() . '' . $segmento . '/ver/' . $r->id . '" class="btn btn-primary"><i class="fa fa-eye"></i> Ver</a>';
        } else {
            if ($empleado->rol->tieneRol($segmento . "_editar_todos", $empleado))
                $editar = ' <a href="' . base_url() . '' . $segmento . '/editar/' . $r->id . '"class="btn btn-warning"><i class="fa fa-edit"></i> Editar</a>';

            if ($empleado->rol->tieneRol($segmento . "_borrar_todos", $empleado))
                $borrar = '<span data-toggle="modal" data-target="' . $dataTarget . '" data-id="' . $r->id . '" class="' . $class . ' btn btn-danger"><i class="fa fa-trash-alt"></i> Borrar</span>';

            if ($empleado->rol->tieneRol($segmento . "_ver_todos", $empleado))
                $ver = '<a href="' . base_url() . '' . $segmento . '/ver/' . $r->id . '" class="btn btn-primary"><i class="fa fa-eye"></i> Ver</a>';
        }
        return '<div class="btn-group" role="group">'.$editar . $borrar . $ver.'</div>';
    }
}
