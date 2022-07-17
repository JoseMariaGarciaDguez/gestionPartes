<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Informes extends CI_Controller
{

    public $empleado;
    public $informe;
    public $rol;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Empleado_model');
        $this->load->model('Rol_model');
        $this->load->model('Informe_model');
        if ($this->session->userdata('email') != null && $this->Empleado_model->comprobarEmail($this->session->userdata('email'))) {
            $this->empleado = new Empleado_model(null, $this->session->userdata('email'));
            $this->informe = new Informe_model($this->empleado->id);
            $this->rol = new Rol_model($this->empleado->rolID);
        } else redirect('/login');
    }

    public function index()
    {
        $this->load->view('informes.php', array("empleado" => $this->empleado, "rol" => $this->rol, "informe" => $this->informe));
    }

    public function actualizarFiltros()
    {
        $fechaInicio = $_POST['fechaInicio'];
        $fechaFinal = $_POST['fechaFinal'];
        $tablas = $_POST['tablas'];
        $graficos = $_POST['graficos'];

        $this->informe->actualizar(array("fechaInicio" => $fechaInicio, "fechaFinal" => $fechaFinal, "tablas" => $tablas, "graficos" => $graficos, "byEmpleadoID" => $this->empleado->id));
        $this->obtenerDatosGraficos();
    }

    public function obtenerDatosGraficos()
    {
        exit($this->informe->obtenerDatosGraficos($_POST['fechaInicio'], $_POST['fechaFinal']));
    }


    //TABLAS
    public function obtenerGastosPorClienteTabla()
    {
        $fechaInicio = $_POST['fechaInicio'];
        $fechaFinal = $_POST['fechaFinal'];
        $draw = intval($this->input->get("draw"));

        $sentencia = "SELECT SUM(total) as total,nombre,apellidos FROM sge_albaranes WHERE str_to_date(fechaCreacion,'%d/%m/%Y') 
                      BETWEEN str_to_date('$fechaInicio','%d/%m/%Y') AND str_to_date('$fechaFinal','%d/%m/%Y')
                      GROUP BY nombre,apellidos";
        $query = $this->db->query($sentencia);

        $data = array();
        foreach ($query->result_array() as $r) {

            $data[] = array(
                $r['nombre'] . ' ' . $r['apellidos'],
                number_format((float)$r['total'], 2, ',', '.') . '€',
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

    public function obtenerPartesPorObraTabla()
    {
        $fechaInicio = $_POST['fechaInicio'];
        $fechaFinal = $_POST['fechaFinal'];
        $draw = intval($this->input->get("draw"));

        $sentencia = "SELECT COUNT(o.id) as total,o.nombre as nombre FROM sge_obras as o,sge_partes as p WHERE str_to_date(o.fechaCreacion,'%d/%m/%Y') 
                      BETWEEN str_to_date('$fechaInicio','%d/%m/%Y') AND str_to_date('$fechaFinal','%d/%m/%Y')
                      AND p.obraID=o.id
                      GROUP BY o.id ORDER BY 1 DESC";
        $query = $this->db->query($sentencia);

        $data = array();
        foreach ($query->result_array() as $r) {

            $data[] = array(
                $r['nombre'],
                $r['total'] . ' partes',
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

    public function obtenerHorasPorEmpleadoTabla()
    {
        $fechaInicio = $_POST['fechaInicio'];
        $fechaFinal = $_POST['fechaFinal'];
        $draw = intval($this->input->get("draw"));

        $sentencia = "SELECT SUM(p.horas) as horas,e.nombre,e.apellidos FROM sge_empleados as e,sge_partes as p WHERE str_to_date(fechaCreacion,'%d/%m/%Y') 
                      BETWEEN str_to_date('$fechaInicio','%d/%m/%Y') AND str_to_date('$fechaFinal','%d/%m/%Y')
                      AND JSON_CONTAINS(p.empleadosID, CONCAT('[','\"',e.id,'\"',']'))
                      GROUP BY nombre,apellidos ORDER BY 1 DESC";
        $query = $this->db->query($sentencia);

        $data = array();
        foreach ($query->result_array() as $r) {

            $data[] = array(
                $r['nombre'] . ' ' . $r['apellidos'],
                intval($r['horas']),
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

    public function obtenerObrasTabla()
    {
        $fechaInicio = $_POST['fechaInicio'];
        $fechaFinal = $_POST['fechaFinal'];
        $draw = intval($this->input->get("draw"));

        $obras = $this->informe->obtenerObras($fechaInicio, $fechaFinal);

        $data = array();
        $x = 0;
        foreach ($obras as $r) {

            $data[] = array(
                date('d/m/Y', $r['x'] / 1000),
                $r['y']
            );

            $x++;
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $x,
            "recordsFiltered" => $x,
            "data" => $data
        );

        exit(json_encode($output));
    }

    public function obtenerPartesTabla()
    {
        $fechaInicio = $_POST['fechaInicio'];
        $fechaFinal = $_POST['fechaFinal'];
        $draw = intval($this->input->get("draw"));

        $partes = $this->informe->obtenerPartes($fechaInicio, $fechaFinal);

        $data = array();
        $x = 0;
        foreach ($partes as $r) {

            $data[] = array(
                date('d/m/Y', $r['x'] / 1000),
                $r['y']
            );

            $x++;
        }

        $output = array(
            "draw" => $draw,
            "recordsTotal" => $x,
            "recordsFiltered" => $x,
            "data" => $data
        );

        exit(json_encode($output));
    }

    //obtenerObrasPorCliente
    public function obtenerObrasPorClienteTabla()
    {
        $fechaInicio = $_POST['fechaInicio'];
        $fechaFinal = $_POST['fechaFinal'];
        $draw = intval($this->input->get("draw"));

        $sentencia = "SELECT COUNT(clienteID) as total, C.nombre as nombre, C.apellidos as apellidos FROM sge_obras as O, sge_clientes as C 
                      WHERE str_to_date(O.fechaCreacion,'%d/%m/%Y') BETWEEN str_to_date('$fechaInicio','%d/%m/%Y') AND str_to_date('$fechaFinal','%d/%m/%Y')
                      AND C.id = O.clienteID
                      GROUP BY O.clienteID";
        $query = $this->db->query($sentencia);

        $data = array();
        foreach ($query->result_array() as $r) {

            $data[] = array(
                $r['nombre'] . ' ' . $r['apellidos'],
                'x' . $r['total'],
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
