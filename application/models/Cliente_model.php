<?php

class Cliente_model extends CI_Model
{

    public $id;
    public $nombreJuridico;
    public $nombre;
    public $apellidos;
    public $nif;
    public $telefonos;
    public $fax;
    public $email;
    public $web;
    public $direccion;
    public $codigoPostal;
    public $localidad;
    public $provincia;
    public $pais;
    public $IBAN;
    public $rolID;
    public $fechaCreacion;
    public $avatarName;
    public $byEmpleadoID;

    public function __construct($id = null)
    {
        parent::__construct();
        if ($id != null) $this->get($id);
    }

    //Obtiene un usuario
    public function get($id, $email = null)
    {

        if (!is_null($email))
            $query = $this->db->get_where('clientes', array('email' => $id));
        else
            $query = $this->db->get_where('clientes', array('id' => $id));

        $query = $query->result_array();

        foreach ($query as $row) {
            $this->id = $row['id'];
            $this->nombreJuridico = $row['nombreJuridico'];
            $this->nombre = $row['nombre'];
            $this->apellidos = $row['apellidos'];
            $this->nif = $row['nif'];
            $this->telefonos = json_decode($row['telefonos']);
            $this->fax = $row['fax'];
            $this->email = $row['email'];
            $this->web = $row['web'];
            $this->direccion = $row['direccion'];
            $this->codigoPostal = $row['codigoPostal'];
            $this->localidad = $row['localidad'];
            $this->provincia = $row['provincia'];
            $this->pais = $row['pais'];
            $this->IBAN = $row['IBAN'];
            $this->rolID = $row['rolID'];
            $this->fechaCreacion = $row['fechaCreacion'];
            $this->avatarName = $row['avatarName'];
            $this->byEmpleadoID = $row['byEmpleadoID'];
            break;
        }

    }

    public function obtenerTabla($selector)
    {
        return $this->db->select($selector)->get("clientes");
    }

    public function contarObrasDeCliente()
    {
        return $this->db->get_where('obras', array("clienteID" => $this->id))->num_rows();
    }

    public function borrar()
    {
        $this->db->delete('clientes', array('id' => $this->id));
    }

    public function crear($array)
    {
        $this->db->insert('clientes', $array);
        $this->get(null, $array['email']);
    }

    public function editar($id, $array)
    {
        $this->db->where('id', $id);
        $this->db->update('clientes', $array);
        $this->get($id);
    }

    public function insertarAvatar($avatar)
    {
        if (file_exists('assets/images/temp/' . $avatar)) {
            $this->db->where('id', $this->id);
            $newName = $this->id . '.' . pathinfo($avatar, PATHINFO_EXTENSION);
            $this->db->update('clientes', array('avatarName' => $newName));
            rename('assets/images/temp/' . $avatar, 'assets/images/cliente/' . $newName);
        }
    }

    public function comprobarEmail($email)
    {
        $query = $this->db->get_where('clientes', array('email' => $email));
        if ($query->num_rows() > 0) return true; else return false;
    }

    public function getDireccionCompleta()
    {
        if ($this->direccion != null && $this->localidad != null && $this->provincia != null && $this->pais = !null) {
            return ($this->direccion . ", " . $this->localidad . ", " . $this->provincia . ", " . $this->pais . ".");
        } else {
            return ("ESTE CLIENTE NO TIENE DIRECCION ASIGNADA");
        }

    }
}
