<?php
/** @noinspection ALL */
defined('BASEPATH') OR exit('No direct script access allowed');

class Rol extends CI_Controller
{

    public $empleado;
    public $rol;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Empleado_model');
        $this->load->model('Rol_model');
        $this->load->helper('Rol_helper');

        if ($this->session->userdata('email') != null && $this->Empleado_model->comprobarEmail($this->session->userdata('email'))) {
            $this->empleado = new Empleado_model(null, $this->session->userdata('email'));
            $this->rol = new Rol_model();
        } else redirect('/login');
    }

    public function index()
    {
        $this->load->view('rol_ver_todos.php', array("empleado" => $this->empleado, "rol" => $this->rol));
    }

    public function crear()
    {
        $this->load->view('rol_crear.php', array(
            "empleado" => $this->empleado,
            "rol" => $this->rol
        ));
    }

    public function editar()
    {
        $seg2 = $this->uri->segment(3);
        if (isset($seg2)) {
            $this->rol = new Rol_model($seg2);

            if (empty($this->rol->id)) redirect('/rol');
            else $this->load->view('rol_editar.php', array("rol" => $this->rol, "empleado" => $this->empleado));
        } else redirect('/rol');
    }


    public function ver()
    {
        $seg2 = $this->uri->segment(3);
        if (isset($seg2)) {
            $this->rol = new Rol_model($seg2);

            if (empty($this->rol->id)) redirect('/rol');
            else $this->load->view('rol_ver.php', array("rol" => $this->rol, "empleado" => $this->empleado));
        } else redirect('/rol');
    }

    public function crearRol()
    {
        $this->rol = new Rol_model();

        $enviar = true;

        $mensaje = '<ul>';

        if (empty($_POST['nombre'])) {
            $mensaje .= '<li>No puedes dejar campos vacíos.</li>';
            $enviar = false;
        }

        if ($this->rol->comprobarNombre($_POST['nombre'])) {
            $mensaje .= '<li>Ya existe un rol con el mismo nombre.</li>';
            $enviar = false;
        }

        if ($enviar) {
            $this->rol->crear($this->getFormArray());

            $mensaje .= '<li>El Rol se ha creado correctamente.</li>';
            $titulo = '¡Rol creado correctamente!';
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
            'permisos' => json_encode($_POST['permisos'])
        );
    }

    public function BorrarRolModalSi()
    {
        $this->rol = new Rol_model($_POST['id']);
        $mensaje = '<ul>';

        $this->rol->borrar();
        $mensaje .= '<li>El rol se ha eliminado correctamente.</li>';
        $titulo = '¡Rol eliminado correctamente!';

        $mensaje .= '</ul>';

        exit(json_encode(array("titulo" => $titulo, "mensaje" => $mensaje)));
    }

    public function editarRol()
    {
        $this->rol = new Rol_model($_POST['id']);

        $enviar = true;

        $mensaje = '<ul>';

        if (empty($_POST['nombre'])) {
            $mensaje .= '<li>No puedes dejar campos vacíos.</li>';
            $enviar = false;
        }

        if ($this->rol->nombre != $_POST['nombre'] && $this->rol->comprobarNombre($_POST['nombre'])) {
            $mensaje .= '<li>Ya existe un rol con el mismo nombre.</li>';
            $enviar = false;
        }
        if ($enviar) {
            $this->rol->editar($_POST['id'], $this->getFormArray());

            $mensaje .= '<li>El Rol se ha editado correctamente.</li>';
            $titulo = '¡Rol editado correctamente!';
        } else {
            $titulo = '¡Ha ocurrido un error!';
        }

        $mensaje .= '</ul>';

        exit(json_encode(array("titulo" => $titulo, "mensaje" => $mensaje, "enviar" => $enviar)));
    }

    public function obtenerTabla()
    {
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));


        $query = $this->rol->obtenerTabla('id,nombre');

        $data = array();

        foreach ($query->result() as $r) {
            if ($r->id == -1) continue;
            $botonera = $this->empleado->rol->botoneraHTML($this->uri->segment(1), $this->empleado, $r, "#modalBorrarRol", "btn_abrirModalBorrarRol");
            $card = '
				<ul class="products-list product-list-in-box">
					<li class="item">
						<i data-id="' . $r->id . '" class="checkboxTabla fa fa-square pull-left"></i>
						<div class="product-info">
							<a href="' . base_url() . 'rol/ver/' . $r->id . '">
								<span class="product-description">
									<b>Referencia: </b>ROL' . $r->id . '</br>
									<b>Nombre: </b>' . $r->nombre . '</br>
								</span>
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

}
