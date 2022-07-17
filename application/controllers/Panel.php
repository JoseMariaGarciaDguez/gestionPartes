<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Panel extends CI_Controller
{

    public $empleado;
    public $rol;
    public $obra;
    public $asignador;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Empleado_model');
        $this->load->model('Obra_model');
        $this->load->model('Rol_model');
        $this->load->model('Asignador_model');
        $this->load->model('Vehiculo_model');
        $this->load->model('Cliente_model');
        if ($this->session->userdata('email') != null && $this->Empleado_model->comprobarEmail($this->session->userdata('email'))) {
            $this->empleado = new Empleado_model(null, $this->session->userdata('email'));
            $this->rol = new Rol_model($this->empleado->rolID);
            $this->obra = new Obra_model();
            $this->asignador = new Asignador_model();
            $this->vehiculo = new Vehiculo_model();
        } else redirect('/login');
    }

    public function index()
    {
        $this->load->view('index.php', array("empleado" => $this->empleado, "rol" => $this->rol, "obra" => $this->obra, "asignador" => $this->asignador));
    }

    public function sin_permiso()
    {
        $this->load->view('sin_permiso.php', array("empleado" => $this->empleado, "rol" => $this->rol));
    }

    public function bloqueado()
    {
        $this->load->view('bloqueado.php', array("empleado" => $this->empleado, "rol" => $this->rol));
        $this->empleado->cerrarSesion();
    }
}
