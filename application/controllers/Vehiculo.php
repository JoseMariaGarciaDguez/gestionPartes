<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vehiculo extends CI_Controller
{

    public $vehiculo;
    public $empleado;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Vehiculo_model');
        $this->load->model('Empleado_model');

        if ($this->session->userdata('email') != null && $this->Empleado_model->comprobarEmail($this->session->userdata('email'))) {
            $this->empleado = new Empleado_model(null, $this->session->userdata('email'));
            $this->vehiculo = new Vehiculo_model();
        } else redirect('/login');
    }

    public function index()
    {
        $this->load->view('vehiculo_ver_todos.php', array("empleado" => $this->empleado, "vehiculo" => $this->vehiculo));
    }

    public function crear()
    {
        $this->load->view('vehiculo_crear.php', array(
            "empleado" => $this->empleado,
            "vehiculo" => $this->vehiculo
        ));
    }


    public function ver()
    {
        $seg2 = $this->uri->segment(3);
        if (isset($seg2)) {
            $this->vehiculo = new Vehiculo_model($seg2);

            if (empty($this->vehiculo->id)) redirect('/vehiculo');
            else $this->load->view('vehiculo_ver.php', array("vehiculo" => $this->vehiculo, "empleado" => $this->empleado));
        } else redirect('/vehiculo');
    }

    public function editar()
    {
        $seg2 = $this->uri->segment(3);
        if (isset($seg2)) {
            $this->vehiculo = new Vehiculo_model($seg2);

            if (empty($this->vehiculo->id)) redirect('/vehiculo');
            else $this->load->view('vehiculo_editar.php', array("vehiculo" => $this->vehiculo, "empleado" => $this->empleado));
        } else redirect('/vehiculo');
    }

    public function obtenerTabla()
    {
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));


        $query = $this->vehiculo->obtenerTabla('id,nombre,matricula,estado,avatar,byEmpleadoID');

        $data = array();

        $tablaSoloCreados = $_POST["tablaSoloCreados"];
        foreach ($query->result() as $r) {
            if ($this->vehiculo->comprobarEstadosAsignados($r->id)) $r->estado = 'LI';

            if ($tablaSoloCreados && $r->byEmpleadoID != $this->empleado->id) continue;
            $nombre = "";
            if (!empty($r->nombre)) $nombre = '<b>Nombre: </b>' . $r->nombre . '</br>';

            $botonera = $this->empleado->rol->botoneraHTML($this->uri->segment(1), $this->empleado, $r, "#modalBorrarVehiculo", "btn_abrirModalBorrarVehiculo");

            $card = '

				<ul class="products-list product-list-in-box">
					<li class="item">
						<i data-id="' . $r->id . '" class="checkboxTabla fa fa-square pull-left"></i>
						<div class="product-img">
							<img class="img-circle" src="' . base_url() . 'assets/images/vehiculo/' . $r->avatar . '">
						</div>
						<div class="product-info">
							<a href="' . base_url() . 'vehiculo/ver/' . $r->id . '">
								<div class="row product-description">
									<div class="col-sm-6">
                                        <b>Referencia: </b>VEH' . $r->id . '</br>
                                        ' . $nombre . '
                                        <b>Número de vehículo: #</b>' . $r->id . '
                                    </div>
									<div class="col-sm-6">
                                        <b>Matrícula: </b>' . $r->matricula . '</br>
                                        <b>Estado: </b><span class="label label-' . $this->vehiculo->traducirEstadoColor($r->estado) . '">' . $this->vehiculo->traducirEstado($r->estado) . '</span>
                                    </div>
								</div>
							</a>
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
        exit(json_encode($output));
    }

    public function enviarVehiculo()
    {
        $this->vehiculo->editar($_POST['id'], array('enviado' => $_POST['enviado']));
    }

    public function EditarVehiculosFormulario()
    {

        $enviar = true;

        $mensaje = '<ul>';

        if ($enviar) {
            foreach ((array)json_decode($_POST['ids']) as $id) {
                $this->vehiculo = new Vehiculo_model($id);
                $this->vehiculo->editar($id, array('estado' => $_POST['estado']));
            }
            $mensaje .= '<li>La vehiculo se ha modificado correctamente.</li>';
            $titulo = '¡Vehiculo modificada correctamente!';
        } else {
            $titulo = '¡Ha ocurrido un error!';
        }

        $mensaje .= '</ul>';

        exit(json_encode(array("titulo" => $titulo, "mensaje" => $mensaje, "enviar" => $enviar)));
    }

    public function BorrarVehiculoModalSi()
    {
        $this->vehiculo = new Vehiculo_model($_POST['id']);
        $mensaje = '<ul>';

        $this->vehiculo->borrar();
        $mensaje .= '<li>El empleado se ha eliminado correctamente.</li>';
        $titulo = '¡Empleado eliminado correctamente!';

        $mensaje .= '</ul>';

        exit(json_encode(array("titulo" => $titulo, "mensaje" => $mensaje)));
    }

    public function crearVehiculo()
    {
        $this->vehiculo = new Vehiculo_model();

        $enviar = true;

        $mensaje = '<ul>';

        if (empty($_POST['matricula'])) {
            $mensaje .= '<li>No puedes dejar campos vacíos.</li>';
            $enviar = false;
        }

        /*if($this->vehiculo->comprobarReferencia($_POST['ref'])){
            $mensaje .= '<li>Ya existe una vehiculo con la misma referencia.</li>';
            $enviar = false;
        }*/

        if ($this->vehiculo->comprobarMatricula($_POST['matricula'])) {
            $mensaje .= '<li>Ya existe una vehiculo con la misma matricula.</li>';
            $enviar = false;
        }

        if ($enviar) {
            $this->vehiculo->crear($this->getFormArray());
            $this->vehiculo->insertarAvatar($_POST['avatarName']);
            $mensaje .= '<li>El vehiculo se ha creado correctamente.</li>';
            $titulo = '¡Vehiculo creada correctamente!';
        } else {
            $titulo = '¡Ha ocurrido un error!';
        }

        $mensaje .= '</ul>';

        exit(json_encode(array("titulo" => $titulo, "mensaje" => $mensaje, "enviar" => $enviar)));
    }

    function getFormArray()
    {
        $clienteID = null;
        if (isset($_POST['clientesID'])) $clienteID = $_POST['clientesID'];

        return array(
            'nombre' => $_POST['nombre'],
            'matricula' => $_POST['matricula'],
            'observaciones' => $_POST['observaciones'],
            'estado' => $_POST['estado'],
            'plazas' => $_POST['plazas'],
            'modelo' => $_POST['modelo'],
            'byEmpleadoID' => $this->empleado->id
        );
    }

    public function editarVehiculo()
    {
        $this->vehiculo = new Vehiculo_model($_POST['id']);

        $enviar = true;

        $mensaje = '<ul>';

        if (empty($_POST['matricula'])) {
            $mensaje .= '<li>No puedes dejar campos vacíos.</li>';
            $enviar = false;
        }

        /*if($this->vehiculo->ref != $_POST['ref'] && $this->vehiculo->comprobarReferencia($_POST['ref'])){
            $mensaje .= '<li>Ya existe una vehiculo con la misma referencia.</li>';
            $enviar = false;
        }*/

        if ($this->vehiculo->matricula != $_POST['matricula'] && $this->vehiculo->comprobarMatricula($_POST['matricula'])) {
            $mensaje .= '<li>Ya existe una vehiculo con la misma matricula.</li>';
            $enviar = false;
        }

        if ($enviar) {
            $this->vehiculo->editar($_POST['id'], $this->getFormArray());
            $this->vehiculo->insertarAvatar($_POST['avatarName']);

            $mensaje .= '<li>La vehiculo se ha creado correctamente.</li>';
            $titulo = '¡Vehiculo creada correctamente!';
        } else {
            $titulo = '¡Ha ocurrido un error!';
        }

        $mensaje .= '</ul>';

        exit(json_encode(array("titulo" => $titulo, "mensaje" => $mensaje, "enviar" => $enviar)));
    }

    public function contadorDeTiposHTML()
    {
        exit($this->vehiculo->contadorDeTiposHTML($this->empleado));
    }
}
