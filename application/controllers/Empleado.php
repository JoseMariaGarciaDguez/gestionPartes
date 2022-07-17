<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Empleado extends CI_Controller
{

    public $empleado;
    public $empleadoEditar;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Empleado_model');

        if ($this->session->userdata('email') != null && $this->Empleado_model->comprobarEmail($this->session->userdata('email'))) {
            $this->empleado = new Empleado_model(null, $this->session->userdata('email'));
        } else redirect('/login');
    }

    public function index()
    {
        $this->load->view('empleado_ver_todos.php', array("empleado" => $this->empleado));
    }

    public function crear()
    {
        $this->load->view('empleado_crear.php', array("empleado" => $this->empleado));
    }

    public function editar()
    {
        $seg2 = $this->uri->segment(3);
        if (isset($seg2)) {
            $this->empleadoEditar = new Empleado_model($seg2, null);

            if (empty($this->empleadoEditar->id)) redirect('/empleado');
            else $this->load->view('empleado_editar.php', array("empleado" => $this->empleado, "empleadoEditar" => $this->empleadoEditar));
        } else redirect('/empleado');
    }

    public function ver()
    {
        $seg2 = $this->uri->segment(3);
        if (isset($seg2)) {
            $this->empleadoEditar = new Empleado_model($seg2, null);

            if (empty($this->empleadoEditar->id)) redirect('/empleado');
            else $this->load->view('empleado_ver.php', array("empleadoEditar" => $this->empleadoEditar, "empleado" => $this->empleado));
        } else redirect('/empleado');
    }

    public function CrearEmpleadoFormulario()
    {
        $enviar = true;

        $mensaje = '<ul>';

        if (empty($_POST['email']) || empty($_POST['nombre']) || empty($_POST['password'])) {
            $mensaje .= '<li>No puedes dejar campos vacíos.</li>';
            $enviar = false;
        }

        if ($this->empleado->comprobarEmail($_POST['email'])) {
            $mensaje .= '<li>Ya existe ese correo electrónico.</li>';
            $enviar = false;
        }


        if ($enviar) {
            $this->empleado->crearEmpleado($this->getFormArray());
            $this->empleado->insertarAvatar($_POST['avatarName']);
            $mensaje .= '<li>El empleado se ha creado correctamente.</li>';
            $titulo = '¡Empleado creado correctamente!';
        } else {
            $titulo = '¡Ha ocurrido un error!';
        }

        $mensaje .= '</ul>';

        exit(json_encode(array("titulo" => $titulo, "mensaje" => $mensaje, "enviar" => $enviar)));
    }

    function getFormArray($actualPassword = null)
    {
        if (empty($_POST['id'])) $id = null;
        else $id = $_POST['id'];

        if (empty($_POST['password'])) $password = $actualPassword;
        else $password = md5($_POST['password']);

        return array(
            'id' => $id,
            'nombre' => $_POST['nombre'],
            'apellidos' => $_POST['apellidos'],
            'email' => $_POST['email'],
            'byEmpleadoID' => $this->empleado->id,
            'rolID' => $_POST['rolID'],
            'telefono' => $_POST['telefono'],
            'estado' => $_POST['estado'],
            'password' => $password
        );
    }

    public function EditarEmpleadoFormulario()
    {
        $this->empleadoEditar = new Empleado_model($_POST['id'], null);

        $enviar = true;

        $mensaje = '<ul>';

        if (empty($_POST['email']) || empty($_POST['nombre'])) {
            $mensaje .= '<li>No puedes dejar campos vacíos.</li>';
            $enviar = false;
        }

        if ($_POST['email'] != $this->empleadoEditar->email && $this->empleadoEditar->comprobarEmail($_POST['email'])) {
            $mensaje .= '<li>Ya existe ese correo electrónico.</li>';
            $enviar = false;
        }


        if ($enviar) {
            $array = $this->getFormArray($this->empleadoEditar->password);

            if (empty($_POST['password'])) $password = null;
            else $array['password'] = md5($_POST['password']);

            $this->empleadoEditar->editarEmpleado($array);
            $this->empleadoEditar->insertarAvatar($_POST['avatarName']);
            $mensaje .= '<li>El empleado se ha modificado correctamente.</li>';
            $titulo = '¡Empleado modificado correctamente!';
        } else {
            $titulo = '¡Ha ocurrido un error!';
        }

        $mensaje .= '</ul>';

        exit(json_encode(array("titulo" => $titulo, "mensaje" => $mensaje, "enviar" => $enviar)));
    }

    public function EditarEmpleadosFormulario()
    {
        $enviar = true;

        $mensaje = '<ul>';

        if (empty($_POST['estado']) || empty($_POST['ids'])) {
            $mensaje .= '<li>No puedes dejar campos vacíos.</li>';
            $enviar = false;
        }

        if ($enviar) {
            foreach ((array)json_decode($_POST['ids']) as $id) {
                $this->empleado = new Empleado_model($id);
                $this->empleado->editarEmpleadoM($id, $_POST['estado']);
            }
            $mensaje .= '<li>Los empleados se han modificado correctamente.</li>';
            $titulo = '¡Empleados modificados correctamente!';
        } else {
            $titulo = '¡Ha ocurrido un error!';
        }

        $mensaje .= '</ul>';

        exit(json_encode(array("titulo" => $titulo, "mensaje" => $mensaje, "enviar" => $enviar)));
    }

    public function BorrarEmpleadoModalSi()
    {
        $this->empleadoEditar = new Empleado_model($_POST['id'], null);
        $mensaje = '<ul>';

        if (empty($_POST['password'])) $password = null;
        else $password = md5($_POST['password']);

        $this->empleadoEditar->borrarEmpleado();
        //$this->empleadoEditar->borrarAvatar(); //Hay que hacer una funcion que borre las imagenes descargadas del empleado /assets/images/empleados/ID.formato
        $mensaje .= '<li>El empleado se ha eliminado correctamente.</li>';
        $titulo = '¡Empleado eliminado correctamente!';

        $mensaje .= '</ul>';

        exit(json_encode(array("titulo" => $titulo, "mensaje" => $mensaje)));
    }

    public function obtenerEmpleadosTabla()
    {
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));

        $estado = $_POST["estado"];
        if (empty($estado)) $estado = "A";
        $query = $this->empleado->obtenerEmpleadosTabla($estado);

        $data = array();

        $tablaSoloCreados = $_POST["tablaSoloCreados"];
        foreach ($query->result() as $r) {
            if ($tablaSoloCreados && $r->byEmpleadoID != $this->empleado->id) continue;

            $rol = $ultimaConexion = $telefono = "";

            if (!is_null($r->rolID) && !empty($r->rolID)) {
                $rolModel = new Rol_model($r->rolID);
                if ($r->rolID == -1) $rolModel->nombre = "Super Administrador";
                $rol = '<b>Rol: </b>' . $rolModel->nombre . '</br>';
            }
            if (!is_null($r->ultimaConexion) && $this->empleado->rol->tieneRol("empleado_ver_última_conexión", $this->empleado)) {
                $ultimaConexion = '<b>Última conexión: </b>' . $r->ultimaConexion . '</br>';
            }

            if (!is_null($r->telefono) && !empty($r->telefono)) {
                $telefono = '<b>Teléfono: </b>' . $r->telefono . '</br>';
            }
            $botonera = $this->empleado->rol->botoneraHTML($this->uri->segment(1), $this->empleado, $r, "#modalBorrarEmpleado", "btn_abrirModalBorrarEmpleado");
            $card = '

				<ul class="products-list product-list-in-box">
					<li class="item">
						<i data-id="' . $r->id . '" class="checkboxTabla fa fa-square pull-left"></i>
						<div class="product-img">
							<img class="img-circle" src="' . base_url() . 'assets/images/empleado/' . $r->avatar . '">
						</div>
						<div class="product-info">
							<a href="' . base_url() . 'empleado/ver/' . $r->id . '">
								<div class="row product-description">
									
									<div class="col-sm-6">
                                        <b>Referencia: </b>EMP' . $r->id . '</br>
                                        <b>Nombre: </b>' . $r->nombre . ' ' . $r->apellidos . '</br>
                                        <b>Email: </b>' . $r->email . '
                                    </div>
									<div class="col-sm-6">
									' . $rol . '
									' . $telefono . '
									' . $ultimaConexion . '
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
        echo json_encode($output);
        exit();
    }

    function subirTemporalmenteAvatar()
    {
        $file = $_FILES['avatar']['name'];
        $enviar = true;

        $size = getimagesize($_FILES['avatar']['tmp_name']);
        list($width, $height) = $size;
        $sizearray = array("width" => $width, "height" => $height);

        $titulo = '¡Ha ocurrido un error!';
        $mensaje = '';

        $allowed_extension = array("jpg", "png", "gif", "bmp", "jpeg", "JPG", "PNG", "GIF", "BMP", "JPEG");
        $image_extension = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);

        if (!in_array($image_extension, $allowed_extension)) {
            $mensaje .= '<li>La imagen debe ser PNG, JPG o GIF.</li>';
            $enviar = false;
        }

        if ($sizearray['width'] > $_POST['width'] || $sizearray['height'] > $_POST['height']) {
            $mensaje .= '<li>La imagen no puede superar los ' . $_POST['width'] . 'px x ' . $_POST['height'] . 'px.</li>';
            $enviar = false;
        }

        if ($enviar) {
            move_uploaded_file($_FILES['avatar']['tmp_name'], 'assets/images/temp/' . $_FILES['avatar']['name']);
            $urlAvatarTemporal = base_url() . 'assets/images/temp/' . $_FILES['avatar']['name'];
            $nombreAvatarTemporal = $_FILES['avatar']['name'];

            exit(json_encode(array("titulo" => 'PONER_AVATAR', "url" => $urlAvatarTemporal, "url_name" => $nombreAvatarTemporal)));
        }

        exit(json_encode(array("titulo" => $titulo, "mensaje" => '<ul>' . $mensaje . '</ul>')));
    }


}
