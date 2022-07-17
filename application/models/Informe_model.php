<?php

class Informe_model extends CI_Model
{

    public $id;
    public $fechaInicio;
    public $fechaFinal;
    public $tablas;
    public $graficos;
    public $byEmpleadoID;

    public function __construct($byEmpleadoID = null)
    {
        parent::__construct();
        if ($byEmpleadoID != null) $this->get($byEmpleadoID);
    }

    //Obtiene una rol
    public function get($byEmpleadoID)
    {
        $query = $this->db->get_where('informes', array('byEmpleadoID' => $byEmpleadoID));

        $query = $query->result_array();

        foreach ($query as $row) {
            $this->id = $row['id'];
            $this->fechaInicio = $row['fechaInicio'];
            $this->fechaFinal = $row['fechaFinal'];
            $this->byEmpleadoID = $row['byEmpleadoID'];
            $this->tablas = json_decode($row['tablas']);
            $this->graficos = json_decode($row['graficos']);
            break;
        }

    }

    public function obtenerTabla($selector)
    {
        return $this->db->select($selector)->get("informes");
    }

    public function seleccionTablas($idTabla)
    {
        if (in_array($idTabla, $this->tablas)) return "selected";
    }

    public function seleccionGraficos($idGrafico)
    {
        if (in_array($idGrafico, $this->graficos)) return "selected";

    }

    public function actualizar(array $array)
    {

        $query = $this->db->get_where("informes", array("byEmpleadoID" => $array['byEmpleadoID']))->num_rows();
        if (!isset($query) || $query == 0) {
            $this->db->insert('informes', $array);
        } else {
            $this->db->where('byEmpleadoID', $array['byEmpleadoID']);
            $this->db->update('informes', $array);
        }

        $this->get($array['byEmpleadoID']);
    }

    public function obtenerDatosGraficos($fInicio, $fFinal)
    {
        return json_encode(array(
            "datGastosPorCliente" => $this->obtenerGastosPorCliente($fInicio, $fFinal),
            "datObrasPorCliente" => $this->obtenerObrasPorCliente($fInicio, $fFinal),
            "datObras" => $this->obtenerObras($fInicio, $fFinal),
            "datPartes" => $this->obtenerPartes($fInicio, $fFinal),
            "datHorasPorEmpleado" => $this->obtenerHorasPorEmpleado($fInicio, $fFinal),
            "datPartesPorObra" => $this->obtenerPartesPorObras($fInicio, $fFinal)
        ));
    }

    private function obtenerGastosPorCliente($fInicio, $fFinal)
    {
        $sentencia = "SELECT SUM(total) as total,nombre,apellidos FROM sge_albaranes WHERE str_to_date(fechaCreacion,'%d/%m/%Y') 
                      BETWEEN str_to_date('$fInicio','%d/%m/%Y') AND str_to_date('$fFinal','%d/%m/%Y')
                      GROUP BY nombre,apellidos";

        $query = $this->db->query($sentencia);
        $datos = array();
        foreach ($query->result_array() as $dato) {
            if (!empty($dato['nombre']))
                array_push($datos, array("name" => $dato["nombre"] . " " . $dato["apellidos"], "y" => floatval($dato["total"])));
        }
        return $datos;
    }

    private function obtenerObrasPorCliente($fInicio, $fFinal)
    {
        $sentencia = "SELECT COUNT(clienteID) as total, C.nombre as nombre, C.apellidos as apellidos FROM sge_obras as O, sge_clientes as C 
                      WHERE str_to_date(O.fechaCreacion,'%d/%m/%Y') BETWEEN str_to_date('$fInicio','%d/%m/%Y') AND str_to_date('$fFinal','%d/%m/%Y')
                      AND C.id = O.clienteID
                      GROUP BY O.clienteID";

        $query = $this->db->query($sentencia);
        $datos = array();
        foreach ($query->result_array() as $dato) {
            if (!empty($dato['nombre']))
                array_push($datos, array("name" => $dato["nombre"] . " " . $dato["apellidos"], "y" => floatval($dato["total"])));
        }
        return $datos;
    }

    public function obtenerObras($fInicio, $fFinal)
    {
        $sentencia = "SELECT COUNT(id) as cantidad, str_to_date(O.fechaCreacion,'%d/%m/%Y') as tiempo FROM sge_obras as O
                      WHERE str_to_date(O.fechaCreacion,'%d/%m/%Y') BETWEEN str_to_date('$fInicio','%d/%m/%Y') AND str_to_date('$fFinal','%d/%m/%Y')
                      GROUP BY tiempo ORDER BY tiempo";

        $query = $this->db->query($sentencia);
        $datos = array();
        foreach ($query->result_array() as $dato) {
            if (!empty($dato['cantidad']))
                array_push($datos, array("y" => intval($dato["cantidad"]), "x" => strtotime($dato["tiempo"]) * 1000));
        }
        return $datos;
    }

    public function obtenerPartes($fInicio, $fFinal)
    {
        $sentencia = "SELECT COUNT(id) as cantidad, str_to_date(O.fechaCreacion,'%d/%m/%Y')  as tiempo FROM sge_partes as O
                      WHERE str_to_date(O.fechaCreacion,'%d/%m/%Y') BETWEEN str_to_date('$fInicio','%d/%m/%Y') AND str_to_date('$fFinal','%d/%m/%Y')
                      GROUP BY tiempo ORDER BY tiempo";

        $query = $this->db->query($sentencia);
        $datos = array();
        foreach ($query->result_array() as $dato) {
            if (!empty($dato['cantidad']))
                array_push($datos, array("y" => intval($dato["cantidad"]), "x" => strtotime($dato["tiempo"]) * 1000));
        }
        return $datos;
    }

    private function obtenerHorasPorEmpleado($fInicio, $fFinal)
    {
        $sentencia = "SELECT SUM(p.horas) as horas,e.nombre,e.apellidos FROM sge_empleados as e,sge_partes as p WHERE str_to_date(p.fechaCreacion,'%d/%m/%Y') 
                      BETWEEN str_to_date('$fInicio','%d/%m/%Y') AND str_to_date('$fFinal','%d/%m/%Y')
                      AND JSON_CONTAINS(p.empleadosID, CONCAT('[','\"',e.id,'\"',']'))
                      GROUP BY nombre,apellidos ORDER BY 1 DESC";

        $query = $this->db->query($sentencia);
        $datos = array();
        foreach ($query->result_array() as $dato) {
            if (!empty($dato['nombre']))
                array_push($datos, array("name" => $dato["nombre"] . " " . $dato["apellidos"], "y" => floatval($dato["horas"])));
        }
        return $datos;
    }

    private function obtenerPartesPorObras($fInicio, $fFinal)
    {
        $sentencia = "SELECT COUNT(o.id) as total,o.nombre as nombre FROM sge_obras as o,sge_partes as p WHERE str_to_date(o.fechaCreacion,'%d/%m/%Y') 
                      BETWEEN str_to_date('$fInicio','%d/%m/%Y') AND str_to_date('$fFinal','%d/%m/%Y')
                      AND p.obraID=o.id
                      GROUP BY o.id ORDER BY 1 DESC";

        $query = $this->db->query($sentencia);
        $datos = array();
        foreach ($query->result_array() as $dato) {
            if (!empty($dato['nombre']))
                array_push($datos, array("name" => $dato["nombre"], "y" => intval($dato["total"])));
        }
        return $datos;
    }

}

