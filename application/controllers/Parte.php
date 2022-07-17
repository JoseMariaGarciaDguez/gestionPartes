<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Mpdf\Mpdf;
use Spipu\Html2Pdf\Html2Pdf;

class Parte extends CI_Controller
{

    public $empleado;
    public $parte;
    public $obra;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Parte_model');
        $this->load->model('Empleado_model');
        $this->load->model('Obra_model');

        $this->parte = new Parte_model();
        $this->obra = new Obra_model();

        if (!is_null($this->uri->segment(2)) && strtolower($this->uri->segment(2)) == "plantillan" && isset($_POST['empleadoID']) && isset($_POST['canvas'])) {
            $this->empleado = new Empleado_model(null, $_POST['empleadoID']);
        } else if ($this->session->userdata('email') != null && $this->Empleado_model->comprobarEmail($this->session->userdata('email'))) {
            $this->empleado = new Empleado_model(null, $this->session->userdata('email'));
        } else redirect('/login');
    }

    public function index()
    {
        $this->load->view('parte_ver_todos.php', array("empleado" => $this->empleado, "parte" => $this->parte, "obra" => $this->obra));
    }

    public function crear()
    {
        $this->load->view('parte_crear.php', array("empleado" => $this->empleado, "parte" => $this->parte, "obra" => $this->obra));
    }

    public function ver()
    {
        $seg2 = $this->uri->segment(3);
        if (isset($seg2)) {
            $this->parte = new Parte_model($seg2);
            $this->obra = new Obra_model($this->parte->obraID);

            if (empty($this->parte->id)) redirect('/parte');
            else $this->load->view('parte_ver.php', array("empleado" => $this->empleado, "parte" => $this->parte, "obra" => $this->obra));
        } else redirect('/parte');
    }

    //Crea el PDF desde AJAX
    public function plantilla()
    {

        $seg2 = $this->uri->segment(3);

        //Si existe el segmento del ID
        if (isset($seg2)) {
            $this->parte = new Parte_model($seg2);
            $this->obra = new Obra_model($this->parte->obraID);

            //Si existe el id del parte
            if (empty($this->parte->id)) redirect('/parte');
            else {
                require 'vendor/autoload.php';

                //Cual será la url que genera la plantilla de /views/plantillas
                $actual_link = base_url() . "parte/plantillan/" . $this->parte->id;

                //Creamos los datos a enviar mediante POST
                $post = array(
                    'canvas' => $_POST['canvas'],
                    'empleadoID' => $_POST['empleadoID']
                );

                //Lanzamos una petición CURL con el POST para recuperar el html
                $ch = curl_init($actual_link);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT , 10);
                //Guardamos el html resultante y cerramos el flujo del CURL
                $html = curl_exec($ch);
				$info = curl_getinfo($ch);
				$error = curl_error($ch);
                curl_close($ch);

                //Directorio y nombre formado por ID+PAR+RANDOM donde se guardará el pdf del parte
                $directorio = 'assets/partes/';
                $randomName = $this->parte->id . 'PAR' . $this->generateRandomString(10);

                //Si no existe el directorio se crea
                if (!is_dir($directorio)) {
                    $old_umask = umask(0);
                    mkdir($directorio, 0777, true);
                    umask($old_umask);
                } else {
                    //En caso de existir, recorremos el directorio en busca de PDFs generados anteriormente
                    foreach (scandir("assets/partes/", 1) as $filename) {
                        //Si el nombre del archivo contiene el ID del parte
                        if (strpos($filename, $this->parte->id) !== false) {
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
                $mpdf->SetDisplayMode('fullwidth');
                $mpdf->writeHTML($html);
                $mpdf->Output($directorio . $randomName . '.pdf', \Mpdf\Output\Destination::FILE);

                //Devolvemos al ajax el ID del parte
                exit($this->parte->id);
            }
        } else redirect('/parte');

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

    public function pdf()
    {
        $seg3 = $this->uri->segment(3);

        //Comprobamos que exista el ID en la url
        if (isset($seg3)) {

            //Escaneamos los archivos del directorio de los PDFs
            foreach (scandir("assets/partes/", 1) as $filename) {

                //Si existe un archivo con el ID en su nombre
                if (strpos($filename, $seg3) !== false) {
                    //Generamos la ruta del archivo
                    $archivo = 'assets/partes/' . $filename;

                    //Generamos una salida de contenido (Codeigniter) con la ruta del PDF obfuscada
                    $this->output->set_header('Content-Disposition: inline; filename="PAR' . $seg3 . '.pdf"')->set_content_type('application/pdf')->set_output(file_get_contents($archivo));
                    break;
                }
            }
        }
    }

    //Esta funcion genera el HTML de la plantilla (Llamada desde CURL)

    public function descargarImagenes()
    {

        $zipname = 'PAR' . $_POST['parteID'] . '.zip';
        $rutaPadre = 'assets/images/parte/' . $_POST['parteID'] . '/';
        $destino = $rutaPadre . $zipname;

        $zip = new ZipArchive();
        $zip->open($destino, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($rutaPadre),
            RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $name => $file) {
            if (!$file->isDir()) {
                if (strpos($name, '.zip') == false)
                    $zip->addFile($file);
            }
        }


        $zip->close();
        exit(json_encode(array("nombre" => $zipname, "url" => base_url() . "$destino")));
    }

    public function plantillaN()
    {
        $seg2 = $this->uri->segment(3);
        $this->empleado = new Empleado_model($_POST['empleadoID']);
        $this->parte = new Parte_model($seg2);
        $this->obra = new Obra_model($this->parte->obraID);

        //Cargamos la vista que será recogida por el CURL que se ha lanzado
        $this->load->view('plantillas/parte', array("empleado" => $this->empleado, "parte" => $this->parte, "obra" => $this->obra, "canvas" => $_POST['canvas']));
    }

    public function editar()
    {
        $seg2 = $this->uri->segment(3);
        if (isset($seg2)) {
            $this->parte = new Parte_model($seg2);

            if (empty($this->parte->id)) redirect('/parte');
            else $this->load->view('parte_editar.php', array("empleado" => $this->empleado, "parte" => $this->parte, "obra" => $this->obra));
        } else redirect('/parte');
    }

    public function EditarParteFormulario()
    {
        $this->parte = new Parte_model($_POST['id']);

        $enviar = true;

        $mensaje = '<ul>';

        if (empty($_POST['fechaCreacion'])
            || empty($_POST['empleadosID'])
            || empty($_POST['horas'] || empty($_POST['obraF']))
        ) {
            $mensaje .= '<li>No puedes dejar campos vacíos.</li>';
            $enviar = false;
        }


        if ($_POST['tipo'] == "A") {
            if (!isset($_POST['obraF']) || trim($_POST['obraF']) == "" || empty($_POST['obraF']) || strtoupper($_POST['obraF']) == "NULL" || $_POST['obraF'] == NULL || is_null($_POST['obraF'])) {
                $mensaje .= '<li>Debes escribir una obra.</li>';
                $enviar = false;
            }
        } else {
            if (!isset($_POST['obraF']) || trim($_POST['obraF']) == "" || empty($_POST['obraF']) || strtoupper($_POST['obraF']) == "NULL" || $_POST['obraF'] == NULL) {
                $mensaje .= '<li>Debes seleccionar una obra.</li>';
                $enviar = false;
            }
        }

        if ($enviar) {
            $parteID = $this->parte->editarParte($this->getFormArray());
            $this->parte->insertarAvatares(json_decode($_POST['galeriaImagenes']), $parteID);

            $mensaje .= '<li>El parte se ha creado correctamente.</li>';
            $titulo = '¡Parte creado correctamente!';
        } else {
            $titulo = '¡Ha ocurrido un error!';
        }

        $mensaje .= '</ul>';

        exit(json_encode(array("titulo" => $titulo, "mensaje" => $mensaje, "enviar" => $enviar)));
    }

    function getFormArray()
    {

        return array(
            'obraID' => $_POST['obraF'],
            'estado' => $_POST['estado'],
            'fechaCreacion' => $_POST['fechaCreacion'],
            'tipo' => $_POST['tipo'],
            'empleadosID' => json_encode($_POST['empleadosID']),
            'firma' => $_POST['firma'],
            'dni' => $_POST['dni'],
            'observaciones' => $_POST['observaciones'],
            'observacionesExtra' => $_POST['observacionesExtra'],
            'material' => $_POST['material'],
            'horas' => $_POST['horas'],
            'byEmpleadoID' => $this->empleado->id
        );
    }

    public function comprobarNombre($nombre)
    {
        $query = $this->db->get_where('partes', array('nombre' => $nombre));
        if ($query->num_rows() > 0) return true; else return false;
    }

    public function obtenerPartesTabla()
    {
        $draw = intval($this->input->get("draw"));
        $start = intval($this->input->get("start"));
        $length = intval($this->input->get("length"));
        $query = $this->parte->obtenerPartesTabla($_POST['estado']);
		//exit($this->db->last_query());

        $data = array();

        $tablaSoloCreados = $_POST["tablaSoloCreados"];
        foreach ($query->result() as $r) {
            $participa = $this->empleado->rol->tieneRol("parte_ver_participado", $this->empleado);
            if (($participa && $this->empleado->rol->id != -1) && !in_array($this->empleado->id, json_decode($r->empleadosID))) continue;
            if (!$participa && $tablaSoloCreados && $r->byEmpleadoID != $this->empleado->id && $this->empleado->rol->id != -1) continue;

            $empleados = $this->parte->obtenerListaEmpleados($r->id);

            if (is_numeric($r->obraID)) {
                $obraID = '<b>Obra asignada: </b> <a href="' . base_url() . 'obra/ver/' . $r->obraID . '">' . $this->obra->traducirObra($r->obraID) . '</a></br>';
            } else {
                $obraID = '<b>Obra: </b>' . $r->obraID . '</br>';
            }

            $array = (array)json_decode($r->avatar);
            if (count($array) == 0) $image = "default.png";
            else $image = $array[0];

            $botonera = $this->empleado->rol->botoneraHTML($this->uri->segment(1), $this->empleado, $r, "#modalBorrarParte", "btn_abrirModalBorrarParte");

            $card = '
				<ul class="products-list product-list-in-box">
					<li class="item">
						<i data-id="' . $r->id . '" class="checkboxTabla fa fa-square pull-left"></i>
                        <div class="product-img">
							<img class="img-circle" src="' . base_url() . 'assets/images/parte/' . $image . '">
						</div>
						<br class="product-info">
							<div class="row product-description">
									<div class="col-sm-2">
									    <b>Referencia: </b>PAR' . $r->id . '</br>
									    <b>Tipo: </b><span class="label label-' . $this->parte->traducirTipoColor($r->tipo) . '">' . $this->parte->traducirTipo($r->tipo) . '</span></br>
									    <b>Estado: </b><span class="label label-' . $this->parte->traducirEstadoColor($r->estado) . '">' . $this->parte->traducirEstado($r->estado) . '</span>
                                    </div>
							
									<div class="col-sm-5">
									    <b>Creado por: </b><a href="' . base_url() . 'empleado/ver/' . $r->byEmpleadoID . '">' . $this->empleado->traducirEmpleado($r->byEmpleadoID) . '</a></br>
                                        <b>Fecha de Creación: </b>' . $r->fechaCreacion . '</br>
							            ' . $obraID . '
                                    </div>
                                    <div class="col-sm-5">
                                        <b>Empleados: </b>' . $empleados . '
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

    public function BorrarParteModalSi()
    {
        $this->parte = new Parte_model($_POST['id'], null);
        $mensaje = '<ul>';

        $this->parte->borrarParte();
        $mensaje .= '<li>Se ha eliminado correctamente.</li>';
        $titulo = '¡Eliminado correctamente!';

        $mensaje .= '</ul>';

        exit(json_encode(array("titulo" => $titulo, "mensaje" => $mensaje)));
    }

    public function CrearParteFormulario()
    {
        $this->parte = new Parte_model();

        $enviar = true;

        $mensaje = '<ul>';

        if (empty($_POST['fechaCreacion'])
            || empty($_POST['empleadosID'])
            || empty($_POST['horas']) || empty($_POST['obraF'])) {
            $mensaje .= '<li>No puedes dejar campos vacíos.</li>';
            $enviar = false;
        }


        if ($_POST['tipo'] == "A") {
            if (!isset($_POST['obraF']) || trim($_POST['obraF']) == "" || empty($_POST['obraF']) || strtoupper($_POST['obraF']) == "NULL" || $_POST['obraF'] == NULL ||
                is_null($_POST['obraF'])) {
                $mensaje .= '<li>Debes escribir una obra.</li>';
                $enviar = false;
            }
        } else {
            if (!isset($_POST['obraF']) || trim($_POST['obraF']) == "" || empty($_POST['obraF']) || strtoupper($_POST['obraF']) == "NULL" || $_POST['obraF'] == NULL) {
                $mensaje .= '<li>Debes seleccionar una obra.</li>';
                $enviar = false;
            }
        }

        if ($enviar) {
            $parteID = $this->parte->crearParte($this->getFormArray());
            $this->parte->insertarAvatares(json_decode($_POST['galeriaImagenes']), $parteID);

            $mensaje .= '<li>El parte se ha creado correctamente.</li>';
            $titulo = '¡Parte creado correctamente!';
        } else {
            $titulo = '¡Ha ocurrido un error!';
        }

        $mensaje .= '</ul>';

        exit(json_encode(array("titulo" => $titulo, "mensaje" => $mensaje, "enviar" => $enviar)));
    }

    public function EditarPartesFormulario()
    {
        $enviar = true;

        $mensaje = '<ul>';

        if (empty($_POST['estado']) || empty($_POST['ids']) || empty($_POST['tipo'])) {
            $mensaje .= '<li>No puedes dejar campos vacíos.</li>';
            $enviar = false;
        }

        if ($enviar) {
            foreach ((array)json_decode($_POST['ids']) as $id) {
                $this->parte = new Parte_model($id);
                $this->parte->editarParteM($id, $_POST['tipo'], $_POST['estado']);
            }
            $mensaje .= '<li>Los partes se han modificado correctamente.</li>';
            $titulo = '¡Partes modificados correctamente!';
        } else {
            $titulo = '¡Ha ocurrido un error!';
        }

        $mensaje .= '</ul>';

        exit(json_encode(array("titulo" => $titulo, "mensaje" => $mensaje, "enviar" => $enviar)));
    }

    public function contadorDeTiposHTML()
    {
        exit($this->parte->contadorDeTiposHTML($_POST['tipo'], $this->empleado));
    }


}
