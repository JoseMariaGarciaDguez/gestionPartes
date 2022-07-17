<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Obra extends CI_Controller
{

    public $empleado;
    public $obra;
    public $cliente;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Obra_model');
        $this->load->model('Empleado_model');
        $this->load->model('Cliente_model');

        if ($this->session->userdata('email') != null && $this->Empleado_model->comprobarEmail($this->session->userdata('email'))) {
            $this->empleado = new Empleado_model(null, $this->session->userdata('email'));
            $this->obra = new Obra_model();
            $this->cliente = new Cliente_model();
        } else redirect('/login');
    }

    public function index()
    {
        $this->load->view('obra_ver_todos.php', array("empleado" => $this->empleado, "obra" => $this->obra, "cliente" => $this->cliente));
    }

    public function crear()
    {
        //Cargamos la vista y le pasamos un array con los objetos de empleado,obra y cliente
        $this->load->view('obra_crear.php', array("empleado" => $this->empleado, "obra" => $this->obra, "cliente" => $this->cliente));
    }

    public function ver()
    {
        $seg2 = $this->uri->segment(3);
        if (isset($seg2)) {
            $this->obra = new Obra_model($seg2);

            if (empty($this->obra->id)) redirect('/obra');
            else $this->load->view('obra_ver.php', array("empleado" => $this->empleado, "obra" => $this->obra, "cliente" => $this->cliente));
        } else redirect('/obra');
    }

    public function editar()
    {
        $seg2 = $this->uri->segment(3);
        if (isset($seg2)) {
            $this->obra = new Obra_model($seg2);

            if (empty($this->obra->id)) redirect('/obra');
            else $this->load->view('obra_editar.php', array("empleado" => $this->empleado, "obra" => $this->obra, "cliente" => $this->cliente));
        } else redirect('/obra');
    }

    public function EditarObraFormulario()
    {
        $this->obra = new Obra_model($_POST['id']);

        $enviar = true;

        $mensaje = '<ul>';

        if (empty($_POST['nombre']) || empty($_POST['estado'])) {
            $mensaje .= '<li>No puedes dejar campos vacíos.</li>';
            $enviar = false;
        }

        if ($enviar) {
            $this->obra->editarObra($this->getFormArray());
            $this->obra->insertarAvatar($_POST['avatarName']);
            $mensaje .= '<li>La obra se ha modificado correctamente.</li>';
            $titulo = '¡Obra modificada correctamente!';
        } else {
            $titulo = '¡Ha ocurrido un error!';
        }

        $mensaje .= '</ul>';

        exit(json_encode(array("titulo" => $titulo, "mensaje" => $mensaje, "enviar" => $enviar)));
    }

    function getFormArray()
    {
        return array(
            'nombre' => $_POST['nombre'],
            'descripcion' => $_POST['descripcion'],
            'estado' => $_POST['estado'],
            'clienteID' => $_POST['clienteID'],
            'fechaCreacion' => date('d/m/Y'),
            'byEmpleadoID' => $this->empleado->id
        );
    }

    public function EditarObrasFormulario()
    {

        $enviar = true;

        $mensaje = '<ul>';

        if (empty($_POST['estado']) || empty($_POST['ids'])) {
            $mensaje .= '<li>No puedes dejar campos vacíos.</li>';
            $enviar = false;
        }

        if ($enviar) {
            foreach ((array)json_decode($_POST['ids']) as $id) {
                $this->obra = new Obra_model($id);
                $array = $this->getFormArray();
                $array['id'] = $id;
                $array['nombre'] = $this->obra->nombre;
                $array['descripcion'] = $this->obra->descripcion;
                $this->obra->editarObra($array);
                $this->obra->insertarAvatar($_POST['avatarName']);
            }
            $mensaje .= '<li>La obra se ha modificado correctamente.</li>';
            $titulo = '¡Obra modificada correctamente!';
        } else {
            $titulo = '¡Ha ocurrido un error!';
        }

        $mensaje .= '</ul>';

        exit(json_encode(array("titulo" => $titulo, "mensaje" => $mensaje, "enviar" => $enviar)));
    }

    public function comprobarNombre($nombre)
    {
        $query = $this->db->get_where('obras', array('nombre' => $nombre));
        if ($query->num_rows() > 0) return true; else return false;
    }

    public function obtenerObrasTabla()
    {
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));


        $query = $this->obra->obtenerObrasTabla();

        $data = array();

        $tablaSoloCreados = $_POST["tablaSoloCreados"];
        foreach ($query->result() as $r) {
            if ($tablaSoloCreados && $r->byEmpleadoID != $this->empleado->id) continue;

            $descripcion = "";
            $cliente = new Cliente_model($r->clienteID);

            $descripcionHTML = '';
            if (!empty($r->descripcion)) $descripcionHTML = '<b>Descripción: </b>' . $r->descripcion . '</br>';

            $clienteHTML = '';
            if (!empty($cliente->nombre)) $clienteHTML = '<b>Cliente asignado: </b><a href="' . base_url() . 'cliente/ver/' . $cliente->id . '">' . $cliente->nombre . ' (x' . $cliente->contarObrasDeCliente() . ')</a></br>';

            $botonera = $this->empleado->rol->botoneraHTML($this->uri->segment(1), $this->empleado, $r, "#modalBorrarObra", "btn_abrirModalBorrarObra");

            $card = '

				<ul class="products-list product-list-in-box">
					<li class="item">
						<i data-id="' . $r->id . '" class="checkboxTabla fa fa-square pull-left"></i>
						<div class="product-img">
							<img class="img-circle" src="' . base_url() . 'assets/images/obras/' . $r->avatar . '">
						</div>
						<div class="product-info">
								<div class="row product-description">
                                    <div class="col-sm-3">
									    <b>Referencia: </b>OBR' . $r->id . '</br>
										<b>Nombre: </b>' . $r->nombre . '</br>
										<b>Estado: </b><span class="label label-' . $this->obra->traducirEstadoColor($r->estado) . '">' . $this->obra->traducirEstado($r->estado) . '</span></br>
									    ' . $clienteHTML . '
                                    </div>
									<div class="col-sm-9">
										' . $descripcionHTML . '
                                    </div>
								</span>
							
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

    public function BorrarObraModalSi()
    {
        $this->obra = new Obra_model($_POST['id'], null);
        $mensaje = '<ul>';

        $this->obra->borrarObra();
        $mensaje .= '<li>La obra se ha eliminado correctamente.</li>';
        $titulo = '¡Obra eliminado correctamente!';

        $mensaje .= '</ul>';

        exit(json_encode(array("titulo" => $titulo, "mensaje" => $mensaje)));
    }

    public function CrearObraFormulario()
    {
        $this->obra = new Obra_model();

        $enviar = true;

        $mensaje = '<ul>';

        if (empty($_POST['nombre']) || empty($_POST['estado'])) {
            $mensaje .= '<li>No puedes dejar campos vacíos.</li>';
            $enviar = false;
        }

        if ($enviar) {
            if (empty($_POST['descripcion'])) $descripcion = null;
            else $descripcion = $_POST['descripcion'];

            $this->obra->crearObra($this->getFormArray());
            $this->obra->insertarAvatar($_POST['avatarName']);

            $mensaje .= '<li>La obra se ha modificado correctamente.</li>';
            $titulo = '¡Obra modificada correctamente!';
        } else {
            $titulo = '¡Ha ocurrido un error!';
        }

        $mensaje .= '</ul>';

        exit(json_encode(array("titulo" => $titulo, "mensaje" => $mensaje, "enviar" => $enviar)));
    }

    public function contadorDeTiposHTML()
    {
        exit($this->obra->contadorDeTiposHTML($this->empleado));
    }

}
