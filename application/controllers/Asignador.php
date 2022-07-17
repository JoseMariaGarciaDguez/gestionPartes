<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Asignador extends CI_Controller
{

    public $empleado;
    public $asignador;
    public $vehiculo;
    public $cliente;
    public $obra;
    public $rol;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Empleado_model');
        $this->load->model('Rol_model');
        $this->load->model('Vehiculo_model');
        $this->load->model('Cliente_model');
        $this->load->model('Asignador_model');
        $this->load->model('Obra_model');
        if ($this->session->userdata('email') != null && $this->Empleado_model->comprobarEmail($this->session->userdata('email'))) {
            $this->empleado = new Empleado_model(null, $this->session->userdata('email'));
            $this->asignador = new Asignador_model();
            $this->obra = new Obra_model();
            $this->vehiculo = new Vehiculo_model();
            $this->cliente = new Cliente_model();
            $this->rol = new Rol_model($this->empleado->rolID);
        } else redirect('/login');
    }

    public function index()
    {
        $this->load->view('asignador_ver.php', array(
            "empleado" => $this->empleado,
            "rol" => $this->rol,
            "asignador" => $this->asignador,
            "vehiculo" => $this->vehiculo,
            "obra" => $this->obra,
            "HTMLEmpleados" => $this->getHTMLEmpleados(),
            "HTMLVehiculos" => $this->getHTMLVehiculos(),
            "HTMLObras" => $this->getHTMLObras()
        ));
    }

    public function getHTMLEmpleados()
    {

        $query = $this->empleado->obtenerEmpleadosTabla('A');
        $html = '<div class="scroll-bar "><div class="row ">';
        foreach ($query->result() as $r) {
            $tempEmpleado = new Empleado_model($r->id);
            if (!$tempEmpleado->rol->tieneRol("asignador_ser_asignado", $tempEmpleado) ) continue;
            $card = '';
            if ($this->asignador->comprobar(null, $r->id, null))
                $card = $this->asignador->obtenerEmpleadosHTML($r);
            $html .= $card;
        }

        return $html . '</div></div>';
    }

    public function getHTMLVehiculos()
    {
        $query = $this->vehiculo->obtenerTabla('id,nombre,matricula,estado,avatar');
        $html = '<div class="scroll-bar "><div class="row">';
        foreach ($query->result() as $r) {
            if ($r->estado != 'LI') continue;

            $card = '';
            if ($this->asignador->comprobar($r->id, null, null))
                $card = $this->asignador->obtenerVehiculosHTML($r);
            $html .= $card;
        }

        return $html . '</div></div>';
    }

    public function getHTMLObras()
    {
        $query = $this->obra->obtenerObrasTabla();
        $html = '<div class="scroll-bar "><div class="row ">';
        foreach ($query->result() as $r) {
            if ($r->estado != 'A') continue;

            $card = '';
            if ($this->asignador->comprobar(null, null, $r->id))
                $card = $this->asignador->obtenerObrasHTML($r);
            $html .= $card;
        }

        return $html . '</div></div>';
    }

    public function ver()
    {
        $this->load->view('asignador_ver.php', array(
            "empleado" => $this->empleado,
            "rol" => $this->rol,
            "asignador" => $this->asignador,
            "vehiculo" => $this->vehiculo,
            "obra" => $this->obra,
            "HTMLEmpleados" => $this->getHTMLEmpleados(),
            "HTMLVehiculos" => $this->getHTMLVehiculos(),
            "HTMLObras" => $this->getHTMLObras()
        ));
    }

    public function editar()
    {
        $this->load->view('asignador_editar.php', array(
            "empleado" => $this->empleado,
            "rol" => $this->rol,
            "asignador" => $this->asignador,
            "vehiculo" => $this->vehiculo,
            "obra" => $this->obra,
            "HTMLEmpleados" => $this->getHTMLEmpleados(),
            "HTMLVehiculos" => $this->getHTMLVehiculos(),
            "HTMLObras" => $this->getHTMLObras()
        ));
    }

    public function historial()
    {
        $this->load->view('asignador_historial.php', array(
            "empleado" => $this->empleado,
            "rol" => $this->rol,
            "asignador" => $this->asignador,
            "vehiculo" => $this->vehiculo,
            "obra" => $this->obra,
            "HTMLEmpleados" => $this->getHTMLEmpleados(),
            "HTMLVehiculos" => $this->getHTMLVehiculos(),
            "HTMLObras" => $this->getHTMLObras()
        ));
    }

    function actualizarDatos()
    {
        if (isset($_POST['id'])) $id = $_POST['id'];
        else $id = null;
        return $this->asignador->obtenerVisorHTML($id);
    }

    public function guardarDatos()
    {
        $fechaHoy = null;
        if (isset($_POST['id'])) $fechaHoy = $this->asignador->obtenerFecha($_POST['id']);
        if (isset($_POST['fecha'])) $fechaHoy = $_POST['fecha'];

        $this->asignador->guardar($_POST['asignaciones'], $fechaHoy, $this->empleado->id);
    }

    public function borrarDato()
    {
        $fechaHoy = null;
        if (isset($_POST['id'])) $fechaHoy = $this->asignador->obtenerFecha($_POST['id']);
        $this->asignador->borrar($fechaHoy);
    }


    public function obtenerAsignadorTabla()
    {
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));


        $query = $this->asignador->obtenerAsignaciones(true);

        $data = array();

        foreach ($query->result() as $r) {
            $botonera = $this->empleado->rol->botoneraHTML($this->uri->segment(1), $this->empleado, $r, "#modalBorrarAsignador", "btn_abrirModalBorrarAsignador");
            $card = '

				<ul class="products-list product-list-in-box">
					<li class="item">
						<i data-id="' . $r->id . '" class="checkboxTabla fa fa-square pull-left"></i>
						<div class="product-info">
								<div class="row product-description">
									<div class="col-sm-6">
										<b>Referencia: </b>ASI' . str_replace('-', '', $r->id) . '</br>
										<b>Fecha: </b>' . $r->fecha . '
                                    </div>
									<div class="col-sm-6">
										<b>Asignaciones: </b>' . $this->asignador->contar($r->fecha) . '</br>
										<b>Creado por: </b><a href="' . base_url() . 'empleado/ver/' . $r->byEmpleadoID . '">' . $this->empleado->traducirEmpleado($r->byEmpleadoID) . '</a>
									</div>
										
								</div>
							
							' . $botonera . '
						</div>
					</li>
				</ul>
			';

            $data[] = array(
                $card
            );
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $query->num_rows(),
            "recordsFiltered" => $query->num_rows(),
            "data" => $data
        );
        echo json_encode($output);
        exit();
    }


}
