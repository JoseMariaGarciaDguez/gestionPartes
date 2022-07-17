<?php

class Empleado_model extends CI_Model
{

    public $id;
    public $email;
    public $password;
    public $nombre;
    public $apellidos;
    public $rango_nombre;
    public $avatar;
    public $rolID;
    public $rol;
    public $byEmpleadoID;
    public $ultimaConexion;
    public $telefono;
    public $estado;

    public function __construct($id = null, $email = null)
    {
        parent::__construct();

        if ($id != null) $this->getUser($id, null);
        else if ($email != null) $this->getUser(null, $email);

        $this->load->model('Rol_model');

    }

    //Obtiene un usuario
    public function getUser($id = null, $email = null)
    {
        if ($id != null) $query = $this->db->get_where('empleados', array('id' => $id));
        elseif ($email != null) $query = $this->db->get_where('empleados', array('email' => $email));

        $query = $query->result_array();

        foreach ($query as $row) {
            $this->id = $row['id'];
            $this->email = $row['email'];
            $this->password = $row['password'];
            $this->nombre = $row['nombre'];
            $this->apellidos = $row['apellidos'];
            $this->avatar = $row['avatar'];
            $this->rolID = $row['rolID'];
            $this->byEmpleadoID = $row['byEmpleadoID'];
            $this->ultimaConexion = $row['ultimaConexion'];
            $this->telefono = $row['telefono'];
            $this->estado = $row['estado'];
            $this->rol = new Rol_model($row['rolID']);
            break;
        }

    }

    public function cambiarClave($newclave)
    {
        $this->db->where('email', $this->email);
        $this->db->update('empleados', array('password' => md5($newclave)));
    }

    public function insertarAvatar($avatar)
    {
        if (file_exists('assets/images/temp/' . $avatar)) {
            $this->db->where('email', $this->email);
            $newName = $this->id . '.' . pathinfo($avatar, PATHINFO_EXTENSION);
            $this->db->update('empleados', array('avatar' => $newName));
            rename('assets/images/temp/' . $avatar, 'assets/images/empleado/' . $newName);
        }
    }

    public function cambiarDatos($email, $nombre, $apellidos)
    {
        $this->db->where('email', $this->email);
        $this->db->update('empleados', array('email' => $email, 'nombre' => $nombre, 'apellidos' => $apellidos));

        if ($email != $this->email) {
            $this->cerrarSesion();
            return true;
        }
        return false;
    }

    public function cerrarSesion()
    {
        $this->session->sess_destroy();
    }

    public function crearEmpleado($array)
    {
        $this->db->insert('empleados', $array);
        $this->getUser(null, $array['email']);
    }

    public function borrarEmpleado()
    {
        $this->db->delete('empleados', array('email' => $this->email));
    }

    public function editarEmpleado($array)
    {

        $this->db->where('id', $array['id']);
        $this->db->update('empleados', $array);

        $this->getUser($array['id']);
    }

    public function comprobarCredenciales($email = null, $password)
    {
        $query = $this->db->get_where('empleados', array('email' => $this->email, 'password' => md5($password)));

        if (!is_null($email))
            $query = $this->db->get_where('empleados', array('email' => $email, 'password' => md5($password)));

        if ($query->num_rows() > 0) return true; else return false;
    }

    public function comprobarEmail($email)
    {
        $query = $this->db->get_where('empleados', array('email' => $email));
        if ($query->num_rows() > 0) return true; else return false;
    }

    public function iniciarSesion()
    {
        $this->session->set_userdata('email', $this->email);
        $this->actualizarConexion();
    }

    public function actualizarConexion()
    {
        $this->getUser(null, $this->email);
        $this->db->where('id', $this->id);
        $this->db->update('empleados', array("ultimaConexion" => date('d/m/Y H:i:s')));
    }

    public function obtenerEmpleadosTabla($estado = null)
    {
        if (is_null($estado))
            return $this->db->get("empleados");
        else
            return $this->db->get_where("empleados", array("estado" => $estado));
    }

    public function editarEmpleadoM($id, $estado)
    {
        $this->db->where("id", $id);
        $this->db->update('empleados', array('estado' => $estado));
    }

    public function traducirEmpleado($id)
    {
        $eml = new Empleado_model($id);
        return $eml->nombre . ' ' . $eml->apellidos;
    }

    public function obtenerRolesTabla($selector)
    {
        return $this->db->select($selector)->get("roles");
    }

    public function obtenerPartesAsignados($date)
    {
        $sentencia = "SELECT id,obraID FROM sge_partes WHERE str_to_date(fechaCreacion,'%d/%m/%Y') 
                      = str_to_date('$date','%d/%m/%Y')
                      AND JSON_CONTAINS(empleadosID, CONCAT('[','\"'," . $this->id . ",'\"',']'))
                      ";
        $query = $this->db->query($sentencia);

        return $query->result();
    }

    public function obtenerObrasAsignadas($date)
    {
        $sentencia = "SELECT id,obraID FROM sge_partes WHERE str_to_date(fechaCreacion,'%d/%m/%Y') 
                      = str_to_date('$date','%d/%m/%Y')
                      AND JSON_CONTAINS(empleadosID, CONCAT('[','\"'," . $this->id . ",'\"',']'))
                      ";
        $query = $this->db->query($sentencia);

        return $query->result();
    }

    public function traducirEstado($codigoEstado)
    {
        switch ($codigoEstado) {
            case "B":
                return "Baja";
            case "A":
                return "Activo";
        }

    }

    public function traducirEstadoColor($codigoEstado)
    {
        switch ($codigoEstado) {
            case "B":
                return "danger";
            case "A":
                return "success";
        }

    }


}
