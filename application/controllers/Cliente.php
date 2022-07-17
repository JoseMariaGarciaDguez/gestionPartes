<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cliente extends CI_Controller
{

    public $empleado;
    public $cliente;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Cliente_model');
        $this->load->model('Empleado_model');

        if ($this->session->userdata('email') != null && $this->Empleado_model->comprobarEmail($this->session->userdata('email'))) {
            $this->empleado = new Empleado_model(null, $this->session->userdata('email'));
            $this->cliente = new Cliente_model();
        } else redirect('/login');
    }

    public function index()
    {
        $this->load->view('cliente_ver_todos.php', array("empleado" => $this->empleado, "cliente" => $this->cliente));
    }

    public function crear()
    {
        $this->load->view('cliente_crear.php', array("empleado" => $this->empleado, "cliente" => $this->cliente));
    }

    public function editar()
    {
        $seg2 = $this->uri->segment(3);
        if (isset($seg2)) {
            $this->cliente = new Cliente_model($seg2);

            if (empty($this->cliente->id)) redirect('/cliente');
            else $this->load->view('cliente_editar.php', array("empleado" => $this->empleado, "cliente" => $this->cliente));
        } else redirect('/cliente');
    }

    public function ver()
    {
        $seg2 = $this->uri->segment(3);
        if (isset($seg2)) {
            $this->cliente = new Cliente_model($seg2);

            if (empty($this->cliente->id)) redirect('/cliente');
            else $this->load->view('cliente_ver.php', array("empleado" => $this->empleado, "cliente" => $this->cliente));
        } else redirect('/cliente');
    }


    public function obtenerClientesTabla()
    {
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));


        $query = $this->cliente->obtenerTabla('id,nombre,nif,avatarName,email,telefonos,apellidos,byEmpleadoID');

        $data = array();
        $tablaSoloCreados = $_POST["tablaSoloCreados"];
        foreach ($query->result() as $r) {
            if ($tablaSoloCreados && $r->byEmpleadoID != $this->empleado->id) continue;

            $telefono = "";
            $telefonos = (array)json_decode($r->telefonos);
            for ($x = 0; $x < count($telefonos); $x++) {
                $telefono = $telefonos[0][$x] . ' (' . $telefonos[1][$x] . ')';
                break;
            }

            if (!empty($telefono)) $telefono = '<b>Teléfono: </b>' . $telefono . '</br>';


            $botonera = $this->empleado->rol->botoneraHTML($this->uri->segment(1), $this->empleado, $r, "#modalBorrarCliente", "btn_abrirModalBorrarCliente");

            $card = '
				<ul class="products-list product-list-in-box">
					<li class="item">
							<i data-id="' . $r->id . '" class="checkboxTabla fa fa-square pull-left"></i>
							<a href="' . base_url() . 'cliente/ver/' . $r->id . '">
								<div class="product-img">
									<img class="img-circle" src="' . base_url() . 'assets/images/cliente/' . $r->avatarName . '">
								</div>
							</a>
							<div class="product-info">
								<a href="' . base_url() . 'cliente/ver/' . $r->id . '">
									<div class="row product-description">
										<div class="col-sm-6">
                                            <b>Referencia: </b>CLI' . $r->id . '</br>
                                            <b>Nombre: </b>' . $r->nombre . ' ' . $r->apellidos . '
										</div>
										<div class="col-sm-6">
										    <b>Email: </b>' . $r->email . '</br>
										    ' . $telefono . '
										</div>
									</div>
								</a>
								' . $botonera . ' 
							</div>
					</li>
				</ul>
			';


            /*$data[] = array(
                 '<i data-id="'.$r->id.'" class="checkboxTabla fa fa-square pull-left"></i><img class="img-25-redonda" src="'.base_url().'assets/images/cliente/'.$r->avatarName.'"/> '.$r->nombre.' ('.$r->email.')',
                 $telefono,
                 $r->nif.' <a href="'.base_url().'cliente/editar/'.$r->id.'"
                     class="fa fa-edit"></a>
                     <i data-toggle="modal" data-target="#modalBorrarCliente" data-id="'.$r->id.'" class="btn_abrirModalBorrarCliente fa fa-trash"></i>
                 <a href="'.base_url().'cliente/ver/'.$r->id.'" class="fa fa-eye"></a> '
            ); */

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

    public function BorrarClienteModalSi()
    {
        $this->cliente = new Cliente_model($_POST['id'], null);
        $mensaje = '<ul>';

        $this->cliente->borrar();
        $mensaje .= '<li>El cliente se ha eliminado correctamente.</li>';
        $titulo = '¡Cliente eliminado correctamente!';

        $mensaje .= '</ul>';

        exit(json_encode(array("titulo" => $titulo, "mensaje" => $mensaje)));
    }

    public function crearCliente()
    {
        $this->cliente = new Cliente_model();

        $enviar = true;

        $mensaje = '<ul>';

        if (empty($_POST['fechaCreacion'])
            || empty($_POST['nombre'])
            || empty($_POST['email'])
        ) {
            $mensaje .= '<li>No puedes dejar campos vacíos.</li>';
            $enviar = false;
        }

        if ($this->cliente->comprobarEmail($_POST['email'])) {
            $mensaje .= '<li>Ya existe un cliente con el mismo correo electrónico.</li>';
            $enviar = false;
        }

        if ($enviar) {
            $this->cliente->crear($this->getFormArray());

            $mensaje .= '<li>El cliente se ha creado correctamente.</li>';
            $titulo = '¡Cliente creado correctamente!';
        } else {
            $titulo = '¡Ha ocurrido un error!';
        }

        $mensaje .= '</ul>';

        exit(json_encode(array("titulo" => $titulo, "mensaje" => $mensaje, "enviar" => $enviar)));
    }

    function getFormArray()
    {

        $telefonos = array();

        if (isset($_POST['telefonos'])) $telefonos = array($_POST['telefonos'], $_POST['telefonosNombre']);

        return array(
            'nombreJuridico' => $this->POST('nombreJuridico'),
            'nombre' => $this->POST('nombre'),
            'apellidos' => $this->POST('apellidos'),
            'nif' => $this->POST('nif'),
            'telefonos' => json_encode($telefonos),
            'fax' => $this->POST('fax'),
            'email' => $this->POST('email'),
            'web' => $this->POST('web'),
            'direccion' => $this->POST('direccion'),
            'codigoPostal' => $this->POST('codigoPostal'),
            'localidad' => $this->POST('localidad'),
            'provincia' => $this->POST('provincia'),
            'pais' => $this->POST('pais'),
            'IBAN' => $this->POST('IBAN'),
            'rolID' => $this->POST('rolID'),
            'fechaCreacion' => $this->POST('fechaCreacion'),
            'byEmpleadoID' => $this->empleado->id,
            'avatarName' => $this->POST('avatarName')
        );
    }

    function POST($index, $default = NULL)
    {
        if (isset($_POST[$index])) {
            if (!is_null(trim($_POST[$index])))
                return $_POST[$index];
        }
        return $default;
    }

    public function editarCliente()
    {
        $this->cliente = new Cliente_model($_POST['id']);
        $enviar = true;

        $mensaje = '<ul>';

        if (empty($_POST['fechaCreacion'])
            || empty($_POST['nombre'])
            || empty($_POST['email'])
        ) {
            $mensaje .= '<li>No puedes dejar campos vacíos.</li>';
            $enviar = false;
        }

        if ($this->cliente->email != $_POST['email'] && $this->cliente->comprobarEmail($_POST['email'])) {
            $mensaje .= '<li>Ya existe un cliente con el mismo correo electrónico.</li>';
            $enviar = false;
        }

        if ($enviar) {
            $this->cliente->editar($_POST['id'], $this->getFormArray());
            $this->cliente->insertarAvatar($_POST['avatarName']);

            $mensaje .= '<li>El Cliente se ha modificado correctamente.</li>';
            $titulo = '¡Cliente modificado correctamente!';
        } else {
            $titulo = '¡Ha ocurrido un error!';
        }

        $mensaje .= '</ul>';

        exit(json_encode(array("titulo" => $titulo, "mensaje" => $mensaje, "enviar" => $enviar)));
    }


}
