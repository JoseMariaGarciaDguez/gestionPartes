<?php

class Parte_model extends CI_Model
{

    public $id;
    public $estado;
    public $obraID;
    public $fechaCreacion;
    public $tipo;
    public $empleadosID;
    public $horas;
    public $material;
    public $observaciones;
    public $observacionesExtra;
    public $firme;
    public $avatar;
    public $dni;
    public $byEmpleadoID;

    public function __construct($id = null)
    {
        parent::__construct();
        if ($id != null) $this->get($id);
    }

    //Obtiene un usuario
    public function get($id)
    {
        $query = $this->db->get_where('partes', array('id' => $id));

        $query = $query->result_array();
        $this->sql = $this->db->last_query();

        foreach ($query as $row) {
            $this->id = $row['id'];
            $this->estado = $row['estado'];
            $this->obraID = $row['obraID'];
            $this->fechaCreacion = $row['fechaCreacion'];
            $this->tipo = $row['tipo'];
            $this->empleadosID = json_decode($row['empleadosID']);
            $this->horas = $row['horas'];
            $this->material = $row['material'];
            $this->observaciones = $row['observaciones'];
            $this->observacionesExtra = $row['observacionesExtra'];
            $this->byEmpleadoID = $row['byEmpleadoID'];
            $this->avatar = json_decode($row['avatar']);
            $this->firma = json_decode($row['firma']);
            $this->dni = $row['dni'];
            break;
        }

    }

    public function obtenerListaEmpleados($idParte)
    {
        $parte = new Parte_model($idParte);

        $lista = "";
        $x = 0;
        foreach ($parte->empleadosID as $empleadoID) {
            $emp = new Empleado_model($empleadoID);
            if ($x < count($parte->empleadosID) - 1)
                $lista .= '<a href="' . base_url() . 'empleado/ver/' . $emp->id . '">' . $emp->nombre . ', </a>';
            else
                $lista .= '<a href="' . base_url() . 'empleado/ver/' . $emp->id . '">' . $emp->nombre . '</a>';
            $x++;
        }
        $lista = rtrim($lista, ",");
        return $lista;
    }

    public function obtenerPartesTabla($estado)
    {
		$this->db->where(array("estado" => $estado));
		$this->db->order_by("id","desc");
        return $this->db->get("partes");
    }

    public function obtenerTabla($selector, $estado = null)
    {
        if (is_null($estado))
            return $this->db->select($selector)->get("partes");
        else
            return $this->db->select($selector)->get_where("partes", array("estado" => $estado));
    }

    public function obtenerContadorTipos($codigoTipo)
    {
        if ($codigoTipo == null)
            return $this->db->get("partes")->num_rows();
        else
            return $this->db->get_where("partes", array("tipo" => $codigoTipo))->num_rows();
    }

    public function traducirEstado($codigoEstado)
    {
        switch ($codigoEstado) {
            case "P":
                return "Pendiente";
            case "C":
                return "Cerrado";
        }

    }

    public function traducirEstadoColor($codigoEstado)
    {
        switch ($codigoEstado) {
            case "P":
                return "warning";
            case "C":
                return "danger";
        }

    }

    public function traducirTipo($codigoTipo)
    {
        switch ($codigoTipo) {
            case "A":
                return "Avería";
            case "T":
                return "Trabajo";
        }

    }

    public function traducirTipoColor($codigoTipo)
    {
        switch ($codigoTipo) {
            case "A":
                return "danger";
            case "T":
                return "warning";
        }

    }

    public function borrarParte()
    {
        $this->db->delete('partes', array('id' => $this->id));
        $this->rrmdir('assets/images/parte/' . $this->id);

    }

    function rrmdir($dir)
    {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (is_dir($dir . "/" . $object))
                        rrmdir($dir . "/" . $object);
                    else
                        unlink($dir . "/" . $object);
                }
            }
            rmdir($dir);
        }
    }

    public function crearParte($array)
    {
        $this->db->insert('partes', $array);
        $row = $this->db->select('id')->limit(1)->order_by('id', 'DESC')->get("partes")->row();
        $this->id = $row->id;
        $this->get($this->id);
        return $this->id;
    }

    public function editarParte($array)
    {
        $this->db->where("id", $this->id);
        $this->db->update('partes', $array);
        $this->get($this->id);
        return $this->id;
    }

    public function editarParteM($id, $tipo, $estado)
    {
        $this->db->where("id", $id);
        if ($tipo == "null")
            $this->db->update('partes', array('estado' => $estado));
        else
            $this->db->update('partes', array('estado' => $estado, 'tipo' => $tipo));

    }

    public function contadorDeTiposHTML($tipo, $empleado)
    {
        return '
			<div class="filtradoEstadosPartes">
				<button class="btn btn-info estado' . $tipo . 'Todos" data-search=" ">Todos (' . $this->obtenerContadorEstadosC(array("estado" => $tipo), $empleado) . ')</button>
				<button class="btn btn-warning estado' . $tipo . 'T" data-search="Trabajo">Trabajo (' . $this->obtenerContadorEstadosC(array("estado" => $tipo, "tipo" => 'T'), $empleado) . ')</button>
				<button class="btn btn-danger estado' . $tipo . 'A" data-search="Avería">Avería (' . $this->obtenerContadorEstadosC(array("estado" => $tipo, "tipo" => 'A'), $empleado) . ')</button>

			</div>
		';
    }

    //MOVER A LIBERERIA

    public function obtenerContadorEstadosC($array, $empleado = null)
    {
        if (!is_null($empleado) && !$empleado->rol->tieneRol('parte_ver_todos', $empleado)) {
            $array["byEmpleadoID"] = $empleado->id;
        }
        return $this->db->get_where("partes", $array)->num_rows();
    }

    //MOVER A LIBERRIA

    public function insertarAvatar($avatar)
    {
        if (file_exists('assets/images/temp/' . $avatar)) {
            $this->db->where('id', $this->id);
            $newName = $this->id . '.' . pathinfo($avatar, PATHINFO_EXTENSION);
            $this->db->update('partes', array('avatar' => $newName));
            rename('assets/images/temp/' . $avatar, 'assets/images/parte/' . $newName);
        }
    }

    //MOVER A LIBRERIA

    public function insertarAvatares($galeriaImagenes, $parteID)
    {
        $avatares = array();

        $rutaSin = "assets/images/parte/" . $parteID . '/';
        if (is_dir($rutaSin)) $this->rrmdir($rutaSin);

        foreach ($galeriaImagenes as $avatar) {
            $avatares = $this->subirTemporalmenteAvatar($avatar, $parteID, $avatares);
        }

        $this->db->where('id', $this->id);
        $this->db->update('partes', array('avatar' => json_encode($avatares)));

    }

    //MOVER A LIBERRIA

    function subirTemporalmenteAvatar($avatar, $parteID, $avatares)
    {
        $base64_string = explode(',', $avatar);
        $base64_string = str_replace(' ', '+', $base64_string[1]);
        $fileBase = base64_decode($base64_string);
        $pretype = explode('/', $avatar);
        $type = explode(';', $pretype[1]);

        $name = $this->random_string(10) . "." . $type[0];
        $rutaCon = "assets/images/parte/" . $parteID . '/' . $name;
        $rutaSin = "assets/images/parte/" . $parteID . '/';

        if (!is_dir($rutaSin)) mkdir($rutaSin, 0777, true);

        if ($file = fopen($rutaCon, 'wb')) {
            fwrite($file, $fileBase);
            array_push($avatares, $parteID . '/' . $name);
            fclose($file);
        }

        return $avatares;

    }

    //MOVER A LIBRERIA
    function random_string($length)
    {
        $key = '';
        $keys = array_merge(range(0, 9), range('a', 'z'));

        for ($i = 0; $i < $length; $i++) {
            $key .= $keys[array_rand($keys)];
        }

        return $key;
    }


}
