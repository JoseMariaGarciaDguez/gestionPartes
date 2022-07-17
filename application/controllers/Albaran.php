<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Mpdf\Mpdf;
use Spipu\Html2Pdf\Html2Pdf;

class Albaran extends CI_Controller
{

    public $empleado;
    public $cliente;
    public $albaran;
    public $obra;
    public $parte;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Cliente_model');
        $this->load->model('Empleado_model');
        $this->load->model('Albaran_model');
        $this->load->model('Obra_model');
        $this->load->model('Parte_model');
        $this->cliente = new Cliente_model();
        $this->albaran = new Albaran_model();
        $this->obra = new Obra_model();
        $this->parte = new Parte_model();

        if (!is_null($this->uri->segment(2)) && strtolower($this->uri->segment(2)) == "plantillan" && isset($_POST['empleadoID'])) {
            $this->empleado = new Empleado_model(null, $_POST['empleadoID']);
        } else if ($this->session->userdata('email') != null && $this->Empleado_model->comprobarEmail($this->session->userdata('email'))) {
            $this->empleado = new Empleado_model(null, $this->session->userdata('email'));
        } else redirect('/login');

    }

    public function index()
    {
        $this->load->view('albaran_ver_todos.php', array("empleado" => $this->empleado, "cliente" => $this->cliente, "albaran" => $this->albaran));
    }

    public function crear()
    {
        $this->load->view('albaran_crear.php', array(
            "empleado" => $this->empleado,
            "cliente" => $this->cliente,
            "albaran" => $this->albaran,
            "obra" => $this->obra,
            "parte" => $this->parte
        ));
    }


    public function ver()
    {
        $seg2 = $this->uri->segment(3);
        if (isset($seg2)) {
            $this->albaran = new Albaran_model($seg2);
            if ($this->albaran->clienteID != null) {
                $this->cliente = $this->albaran->clienteID;
            } else {
                $this->cliente->nombre = $this->albaran->nombre;
                $this->cliente->nombreJuridico = $this->albaran->nombreJuridico;
                $this->cliente->apellidos = $this->albaran->apellidos;
                $this->cliente->nif = $this->albaran->nif;

            }
            if (empty($this->albaran->id)) redirect('/albaran');
            else $this->load->view('albaran_ver.php', array("empleado" => $this->empleado, "cliente" => $this->cliente, "albaran" => $this->albaran));
        } else redirect('/albaran');
    }


    //Crea el PDF desde AJAX
    public function plantilla()
    {
        $seg2 = $this->uri->segment(3);
        if (isset($seg2)) {
            $this->albaran = new Albaran_model($seg2);
            if ($this->albaran->clienteID != null) {
                $this->cliente = $this->albaran->clienteID;
            } else {
                $this->cliente->nombre = $this->albaran->nombre;
                $this->cliente->nombreJuridico = $this->albaran->nombreJuridico;
                $this->cliente->apellidos = $this->albaran->apellidos;
                $this->cliente->nif = $this->albaran->nif;

            }
            if (empty($this->albaran->id)) redirect('/albaran');
            else {
                require 'vendor/autoload.php';

                //Cual será la url que genera la plantilla de /views/plantillas
                $actual_link = base_url() . "albaran/plantillan/" . $this->albaran->id;

                //Creamos los datos a enviar mediante POST
                $post = array(
                    'empleadoID' => $_POST['empleadoID']
                );

                //Lanzamos una petición CURL con el POST para recuperar el html
                $ch = curl_init($actual_link);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

                //Guardamos el html resultante y cerramos el flujo del CURL
                $html = curl_exec($ch);
                curl_close($ch);

                //Directorio y nombre formado por ID+PAR+RANDOM donde se guardará el pdf del albaran
                $directorio = 'assets/albaranes/';
                $randomName = $this->albaran->id . 'ALB' . $this->generateRandomString(10);

                //Si no existe el directorio se crea
                if (!is_dir($directorio)) {
                    $old_umask = umask(0);
                    mkdir($directorio, 0777, true);
                    umask($old_umask);
                } else {
                    //En caso de existir, recorremos el directorio en busca de PDFs generados anteriormente
                    foreach (scandir("assets/albaranes/", 1) as $filename) {

                        //Si el nombre del archivo contiene el ID del albaran
                        if (strpos($filename, $this->albaran->id) !== false) {
                            //En caso de exisitr usamos el mismo nombre (para reemplazarlo)
                            $randomName = str_replace('.pdf', '', $filename);
                            break;
                        }
                    }
                }

                //Creamos el objeto de la libreria HTML2PDF
                /*$html2pdf = new HTML2PDF('P', 'A4', 'es', true, 'UTF-8', 1);
                $html2pdf->pdf->SetDisplayMode('fullwidth');
                $html2pdf->writeHTML($html);*/
                $mpdf = new Mpdf(array('tempDir' => 'assets/temps', 'margin_left' => 5, 'margin_right' => 5, 'margin_top' => 5, 'margin_bottom' => 5,));
                $mpdf->writeHTML($html);
                $mpdf->SetDisplayMode('fullwidth');
                $mpdf->Output($directorio . $randomName . '.pdf', \Mpdf\Output\Destination::FILE);

                //Devolvemos al ajax el ID del albaran
                exit($this->albaran->id);


            }
        } else redirect('/albaran');

    }

    //Vista para el PDF (sirve como control de seguridad)

    function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    //Esta funcion genera el HTML de la plantilla (Llamada desde CURL)

    public function pdf()
    {
        $seg3 = $this->uri->segment(3);

        //Comprobamos que exista el ID en la url
        if (isset($seg3)) {

            //Escaneamos los archivos del directorio de los PDFs
            foreach (scandir("assets/albaranes/", 1) as $filename) {

                //Si existe un archivo con el ID en su nombre
                if (strpos($filename, $seg3) !== false) {
                    //Generamos la ruta del archivo
                    $archivo = 'assets/albaranes/' . $filename;

                    //Generamos una salida de contenido (Codeigniter) con la ruta del PDF obfuscada
                    $this->output->set_header('Content-Disposition: inline; filename="ALB' . $seg3 . '.pdf"')->set_content_type('application/pdf')->set_output(file_get_contents($archivo));
                    break;
                }
            }
        }
    }

    public function plantillaN()
    {

        $seg2 = $this->uri->segment(3);
        if (isset($seg2)) {
            $this->albaran = new Albaran_model($seg2);
            if ($this->albaran->clienteID != null) {
                $this->cliente = $this->albaran->clienteID;
            } else {
                $this->cliente->nombre = $this->albaran->nombre;
                $this->cliente->nombreJuridico = $this->albaran->nombreJuridico;
                $this->cliente->apellidos = $this->albaran->apellidos;
                $this->cliente->nif = $this->albaran->nif;

            }
            if (empty($this->albaran->id)) redirect('/albaran');
            else {
                $this->empleado = new Empleado_model($_POST['empleadoID']);

                //Cargamos la vista que será recogida por el CURL que se ha lanzado
                $this->load->view('plantillas/albaran', array("empleado" => $this->empleado, "albaran" => $this->albaran, "cliente" => $this->cliente));
            }

        } else redirect('/albaran');
    }

    public function editar()
    {
        $seg2 = $this->uri->segment(3);
        if (isset($seg2)) {
            $this->albaran = new Albaran_model($seg2);

            if (empty($this->albaran->id)) redirect('/albaran');
            else $this->load->view('albaran_editar.php', array(
                "empleado" => $this->empleado,
                "cliente" => $this->cliente,
                "albaran" => $this->albaran,
                "obra" => $this->obra,
                "parte" => $this->parte
            ));
        } else redirect('/albaran');
    }

    public function obtenerTabla()
    {
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));


        $query = $this->albaran->obtenerTabla('id,estado,nombreAlbaran,enviado,total,byEmpleadoID');

        $data = array();

        $tablaSoloCreados = $_POST["tablaSoloCreados"];
        foreach ($query->result() as $r) {
            if ($tablaSoloCreados && $r->byEmpleadoID != $this->empleado->id) continue;
            $descripcion = "";

            if (!empty($r->descripcion)) {
                $descripcion = "(" . $r->descripcion . ")";
            }
            $enviado = '';
            if ($r->enviado == 1) $enviado = 'selected';

            $empleadoAsignado = '';
            if (!empty($r->byEmpleadoID)) {
                $empleado = new Empleado_model($r->byEmpleadoID);
                $empleadoAsignado = '<b>Creado por: </b><a href="' . base_url() . 'empleado/ver/' . $r->byEmpleadoID . '">' . $empleado->nombre . ' ' . $empleado->apellidos . '</a>';
            }

            $botonera = $this->empleado->rol->botoneraHTML($this->uri->segment(1), $this->empleado, $r, "#modalBorrarAlbaran", "btn_abrirModalBorrarAlbaran");

            $card = '
                    <ul class="products-list product-list-in-box">
                        <li class="item">
                            <i data-id="' . $r->id . '" class="checkboxTabla fa fa-square pull-left"></i>
                            <div class="product-info">
                                <div class="row product-description">
                                        <div class="col-sm-6">
                                            <b>Referencia: </b>ALB' . $r->id . '</br>
                                            <b>Nombre: </b>' . $r->nombreAlbaran . '
                                        </div>
                                        <div class="col-sm-6">
                                            <b>Estado: </b><span class="label label-' . $this->albaran->traducirEstadoColor($r->estado) . '">' . $this->albaran->traducirEstado($r->estado) . '</span></br>
                                            ' . $empleadoAsignado . '
                                        </div>
                                </div>
                                ' . $botonera . ' 
				                
                                <i class="fa fa-envelope-open checkboxEnviado ' . $enviado . '" data-id="' . $r->id . '"></i>
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

    public function enviarAlbaran()
    {
        $this->albaran->editar($_POST['id'], array('enviado' => $_POST['enviado']));
    }

    public function EditarAlbaranesFormulario()
    {

        $enviar = true;

        $mensaje = '<ul>';

        if (empty($_POST['estado']) || empty($_POST['ids'])) {
            $mensaje .= '<li>No puedes dejar campos vacíos.</li>';
            $enviar = false;
        }

        if ($enviar) {
            foreach ((array)json_decode($_POST['ids']) as $id) {
                $this->albaran = new Albaran_model($id);
                $this->albaran->editar($id, array('estado' => $_POST['estado']));
            }
            $mensaje .= '<li>La albaran se ha modificado correctamente.</li>';
            $titulo = '¡Albaran modificada correctamente!';
        } else {
            $titulo = '¡Ha ocurrido un error!';
        }

        $mensaje .= '</ul>';

        exit(json_encode(array("titulo" => $titulo, "mensaje" => $mensaje, "enviar" => $enviar)));
    }

    public function ObtenerHoras()
    {
        $id = $_POST['id'];
        $tipo = $_POST['tipo'];
        if ($tipo == 'P') {
            $parte = new Parte_model($id);
            $horas = $parte->horas;
        } else if ($tipo == 'O') {
            $horas = $this->albaran->obtenerHorasObras($id);
        }
        exit(strval($horas));
    }

    public function BorrarAlbaranModalSi()
    {
        $this->albaran = new Albaran_model($_POST['id']);
        $mensaje = '<ul>';

        $this->albaran->borrar();
        $mensaje .= '<li>El empleado se ha eliminado correctamente.</li>';
        $titulo = '¡Empleado eliminado correctamente!';

        $mensaje .= '</ul>';

        exit(json_encode(array("titulo" => $titulo, "mensaje" => $mensaje)));
    }

    public function obtenerDatosCliente()
    {
        $array = array();
        if ($_POST['clientesID'] != 'null') {
            $tmpCliente = new Cliente_model($_POST['clientesID']);
            $array = array(
                'nombre' => $tmpCliente->nombre,
                'nombreJuridico' => $tmpCliente->nombreJuridico,
                'nif' => $tmpCliente->nif,
                'apellidos' => $tmpCliente->nombreJuridico
            );
        }

        exit(json_encode($array));
    }

    public function crearAlbaran()
    {
        $this->albaran = new Albaran_model();

        $enviar = true;

        $mensaje = '<ul>';

        if (empty($_POST['fechaCreacion'])) {
            $mensaje .= '<li>No puedes dejar campos vacíos.</li>';
            $enviar = false;
        }

        if (!empty($_POST['clientesID']) && $_POST['clientesID'] == "CREAR") {

            if (empty($_POST['nombre']) || empty($_POST['email'])) {
                $mensaje .= '<li>Si quieres crear un cliente deberás escribir un nombre y un correo electrónico.</li>';
                $enviar = false;
            }

            if ($this->cliente->comprobarEmail($_POST['email'])) {
                $mensaje .= '<li>Ya existe un cliente con el mismo correo electrónico.</li>';
                $enviar = false;
            }
        }

        if ($enviar) {
            $this->albaran->crear($this->getFormArray());

            $mensaje .= '<li>El albaran se ha creado correctamente.</li>';
            $titulo = '¡Albaran creado correctamente!';
        } else {
            $titulo = '¡Ha ocurrido un error!';
        }

        $mensaje .= '</ul>';

        exit(json_encode(array("titulo" => $titulo, "mensaje" => $mensaje, "enviar" => $enviar)));
    }

    function getFormArray()
    {

        //Se trata el cliente
        $clienteID = null;
        if (isset($_POST['clientesID'])) $clienteID = $_POST['clientesID'];

        if ($clienteID == "CREAR") {
            $clienteTemporal = new Cliente_model();
            $clienteTemporal->crear(array(
                "nombre" => $_POST['nombre'],
                "email" => $_POST['email'],
                "nombreJuridico" => $_POST['nombreJuridico'],
                "apellidos" => $_POST['apellidos'],
                "nif" => $_POST['nif']
            ));
            $clienteID = $clienteTemporal->id;
        }

        return array(
            'nombreJuridico' => $_POST['nombreJuridico'],
            'nombre' => $_POST['nombre'],
            'apellidos' => $_POST['apellidos'],
            'nif' => $_POST['nif'],
            'clienteID' => $clienteID,
            'estado' => $_POST['estado'],
            'email' => $_POST['email'],
            'enviado' => $_POST['enviado'],
            'total' => $_POST['total'],
            'nombreAlbaran' => $_POST['nombreAlbaran'],
            'byEmpleadoID' => $this->empleado->id,
            'articulos' => json_encode($_POST['listaArticulos']),
            'fechaCreacion' => $_POST['fechaCreacion']
        );
    }

    public function editarAlbaran()
    {
        $this->albaran = new Albaran_model($_POST['id']);

        $enviar = true;

        $mensaje = '<ul>';

        if (empty($_POST['fechaCreacion'])) {
            $mensaje .= '<li>No puedes dejar campos vacíos.</li>';
            $enviar = false;
        }
        if (!empty($_POST['clientesID']) && $_POST['clientesID'] == "CREAR") {

            if (empty($_POST['nombre']) || empty($_POST['email'])) {
                $mensaje .= '<li>Si quieres crear un cliente deberás escribir un nombre y un correo electrónico.</li>';
                $enviar = false;
            }

            if ($this->cliente->comprobarEmail($_POST['email'])) {
                $mensaje .= '<li>Ya existe un cliente con el mismo correo electrónico.</li>';
                $enviar = false;
            }
        }

        if ($enviar) {
            $this->albaran->editar($_POST['id'], $this->getFormArray());

            $mensaje .= '<li>El albaran se ha creado correctamente.</li>';
            $titulo = '¡Albaran creado correctamente!';
        } else {
            $titulo = '¡Ha ocurrido un error!';
        }

        $mensaje .= '</ul>';

        exit(json_encode(array("titulo" => $titulo, "mensaje" => $mensaje, "enviar" => $enviar)));
    }

    public function contadorDeTiposHTML()
    {
        exit($this->albaran->contadorDeTiposHTML());
    }
}
