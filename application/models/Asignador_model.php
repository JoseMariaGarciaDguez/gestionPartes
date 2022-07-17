<?php

class Asignador_model extends CI_Model
{

    public $id;
    public $vehiculo;

    public function __construct()
    {
        $this->load->model("Vehiculo_model");
        $this->vehiculo = new Vehiculo_model();
        parent::__construct();
    }

    public function getTitleHTML($texto)
    {
        return '
            <section class="content-header">
                <h1>
                    ' . $texto . '
                </h1>
            </section>
        ';
    }

    public function guardar($asignaciones, $fechaHoy = null, $empleadoID)
    {


        if (is_null($fechaHoy))
            $fechaHoy = date('d/m/Y');

        $this->db->where(array("fecha" => $fechaHoy));
        $this->db->delete('asignaciones');

        foreach (json_decode($asignaciones) as $a) {
            $datos = array(
                "empleadosID" => json_encode($a->empleadosID),
                "vehiculosID" => json_encode($a->vehiculosID),
                "fecha" => $fechaHoy,
                "byEmpleadoID" => $empleadoID,
                "obrasID" => json_encode($a->obrasID)
            );
            $this->db->insert('asignaciones', $datos);

            foreach ($a->vehiculosID as $v) {
                $datos = array(
                    "estado" => 'OC'
                );
                $this->db->where('id', $v);
                $this->db->update('vehiculos', $datos);
            }
        }

        exit(json_encode(array("titulo" => "¡Guardado correctamente!", "mensaje" => "Se ha guardado y actualizado los datos asignados.")));
    }

    public function borrar($fechaHoy = null)
    {
        if (is_null($fechaHoy))
            $fechaHoy = date('d/m/Y');

        $this->db->where(array("fecha" => $fechaHoy));
        $this->db->delete('asignaciones');


        exit(json_encode(array("titulo" => "¡Eliminado correctamente!", "mensaje" => "Se ha eliminado la asignación correctamente.")));
    }

    public function comprobar($vid = null, $eid = null, $oid = null, $fecha = null)
    {

        if (is_null($fecha)) {
            if (!is_null($this->uri->segment(3))) {
                $fecha = $this->obtenerFecha($this->uri->segment(3));
            } else
                $fecha = date('d/m/Y');
        }

        $query = $this->db->get_where('asignaciones', array("fecha" => $fecha))->result();
        foreach ($query as $a) {
            if (!is_null($vid)) {
                if (in_array(intval($vid), json_decode($a->vehiculosID))) return false;
            }
            if (!is_null($eid)) {
                if (in_array(intval($eid), json_decode($a->empleadosID))) return false;
            }
            if (!is_null($oid)) {
                if (in_array(intval($oid), json_decode($a->obrasID))) return false;
            }
        }
        return true;
    }

    public function obtenerFecha($id, $hoy = false)
    {
        if (is_null($id) && $hoy) return date('d/m/Y');
        if (strpos('-', $id) !== true) return str_replace('-', '/', $id);
        $array = $this->db->get_where('asignaciones', array("id" => $id))->result_array();
        foreach ($array as $r)
            return $r['fecha'];
    }

    public function obtenerVisorHTML($id = null, $empleadoID = null)
    {
        $this->comprobarEstadoVehiculos();
        if (!is_null($id))
            $query = $this->obtenerAsignaciones(false, $this->obtenerFecha($id));
        else
            $query = $this->obtenerAsignaciones(false, null);

        $html = '';
        foreach ($query as $r) {

            if (isset($_POST['empleadoID']) && !in_array($_POST['empleadoID'], json_decode($r['empleadosID']))) continue;
            else if (!is_null($empleadoID) && !in_array($empleadoID, json_decode($r['empleadosID']))) continue;

            $html .= '
                    <div class="col-md-3">
                        <div class="box box-primary">
                            <div class="box-header with-border">';
            $obrasID = json_decode($r['obrasID']);


            if ((!is_null($this->uri->segment(2)) && $this->uri->segment(2) == "editar")) $html .= '
                            <span class="fa fa-times cerrar"></span>
                            <span class="fa fa-minus minimizar"></span>
                             ';
            if (count($obrasID) == 0) {
                $html .= '<h3 class="box-title"><img class="img-icono" src="'.base_url().'assets/images/punteroRojo.gif"/> ASI' . str_replace('/', '', $r['fecha']) . '</h3>';
            } else {
                $obrTemp = new Obra_model($obrasID[0]);
                $html .= '<h3 class="box-title"><img class="img-icono" src="'.base_url().'assets/images/punteroRojo.gif"/> ' . $obrTemp->nombre . '</h3>';
            }
            $html .= '</div>

                            <div class="box-body">
                            
                            
                            <div class="row">
                                <b>Obras</b>
                                <div class="asignadoContenedor" data-id="OBR">';
            if (!empty($obrasID)) {
                foreach ($obrasID as $o) {

                    $obr = new Obra_model($o);
                    $html .= str_replace('col-md-3', 'col-md-12', $this->obtenerObrasHTML($obr));

                }
            }
            $html .= '
                                </div>
                            </div>
                            
                            
                            <div class="row">
                                <b>Empleados</b>
                                <div class="asignadoContenedor" data-id="EMP">';

            $array = json_decode($r['empleadosID']);
            if (!empty($array)) {
                foreach ($array as $e) {

                    $emp = new Empleado_model($e);
                    $html .= str_replace('col-md-3', 'col-md-12', $this->obtenerEmpleadosHTML($emp));

                }
            }
            $html .= '  
                                </div>
                        </div>
                        <div class="row">
                                <b>Vehiculos</b>
                                <div class="asignadoContenedor" data-id="VEH">';

            $array = json_decode($r['vehiculosID']);
            if (!empty($array)) {
                foreach (json_decode($r['vehiculosID']) as $v) {

                    $veh = new Vehiculo_model($v);
                    $html .= str_replace('col-md-3', 'col-md-12', $this->obtenerVehiculosHTML($veh));

                }
            }
            $html .= '
                                </div>
                            </div>

                            </div>
                        </div>
                    </div>';

        }

        if (isset($_POST['update'])) exit($html);

        return $html;

    }

    public function comprobarEstadoVehiculos()
    {

        $query = $this->db->get('vehiculos');
        foreach ($query->result() as $r)
            if ($this->vehiculo->comprobarEstadosAsignados($r->id)) $r->estado = 'LI';

    }

    public function obtenerAsignaciones($todas = false, $fecha = null)
    {
        if (is_null($fecha)) $fecha = date('d/m/Y');

        if (!$todas)
            return $this->db->get_where('asignaciones', array("fecha" => $fecha))->result_array();
        else
            return $this->db->group_by('fecha')->get('asignaciones');
    }

    public function obtenerEmpleadosHTML($r)
    {
        $rol = $ultimaConexion = "";

        if (!is_null($r->rolID)) {
            $rolModel = new Rol_model($r->rolID);
            if ($r->rolID == -1) $rolModel->nombre = "Super Administrador";
            $rol = '<b>Rol: </b>' . $rolModel->nombre . '</br>';
        }

        return '
            <div class="col-md-3 draggable" id="EMP' . $r->id . '">
                <ul class="products-list product-list-in-box">
                    <li class="item">
                        <div class="product-img">
                            <img class="img-asignador" src="' . base_url() . 'assets/images/empleado/' . $r->avatar . '">
                        </div>
                        <div class="product-info">
                            <span class="product-description">
                                <b>Referencia: </b>EMP' . $r->id . '</br>
                                <b>Nombre: </b>' . $r->nombre . ' ' . $r->apellidos . '</br>
                                <a href="' . base_url() . 'empleado/ver/' . $r->id . '" class="label verIcno btn-primary"><i class="fa fa-eye"></i> Ver</a>
                            </span>
                        </div>
                    </li>
                </ul>
            </div>
            ';


    }

    public function obtenerVehiculosHTML($r)
    {
        $nombre = "";
        if (!empty($r->nombre)) $nombre = '<b>Nombre: </b>' . $r->nombre . '</br>';
        return '
                        <div class="col-md-3 draggable" id="VEH' . $r->id . '">
                        <ul class="products-list product-list-in-box" >
                            <li class="item">
                                <div class="product-img">
                                    <img class="img-asignador" src="' . base_url() . 'assets/images/vehiculo/' . $r->avatar . '">
                                </div>
                                <div class="product-info">
                                        <span class="product-description">
                                            <b>Referencia: </b>VEH' . $r->id . '</br>
                                            ' . $nombre . '
                                            <b>Matrícula: </b>' . $r->matricula . '</br>
                                            <a href="' . base_url() . 'vehiculo/ver/' . $r->id . '" class="label verIcno btn-primary"><i class="fa fa-eye"></i> Ver</a>
                                         </span>
                                </div>
                            </li>
                        </ul>
                        </div>
            ';

    }

    public function obtenerObrasHTML($r)
    {

        $cliente = new Cliente_model($r->clienteID);
        $clienteHTML = '';
        if (!empty($cliente->nombre)) $clienteHTML = '<b>Cliente asignado: </b><a href="' . base_url() . 'cliente/ver/' . $cliente->id . '">' . $cliente->nombre . '</a></br>';

        return '
                <div class="col-md-3 draggable" id="OBR' . $r->id . '" nombre="' . $r->nombre . '" fecha="' . $r->fechaCreacion . '">
				<ul class="products-list product-list-in-box">
					<li class="item">
						<div class="product-img">
							<img class="img-asignador" src="' . base_url() . 'assets/images/obras/' . $r->avatar . '">
						</div>
						<div class="product-info">
								<span class="product-description">
										<b>Referencia: </b>OBR' . $r->id . '</br>
										<b>Nombre: </b>' . $r->nombre . '</br>
							            <a href="' . base_url() . 'obra/ver/' . $r->id . '" class="label verIcno btn-primary"><i class="fa fa-eye"></i> Ver</a>
								</span>
						</div>
					</li>
				</ul>
				</div>
			';
    }

    public function obtenerByEmpleadoID($id)
    {
        $array = $this->db->get_where('asignaciones', array("id" => $id))->result_array();
        foreach ($array as $r)
            return $r['byEmpleadoID'];
    }

    public function contar($fecha)
    {
        return $this->db->get_where('asignaciones', array("fecha" => $fecha))->num_rows();
    }

    public function contarEmpleado($empleadoID, $fecha)
    {
        $sentencia = "SELECT * FROM sge_asignaciones WHERE str_to_date(fecha,'%d/%m/%Y') 
                      = str_to_date('$fecha','%d/%m/%Y')
                      AND JSON_CONTAINS(empleadosID, CONCAT('[','\"'," . $empleadoID . ",'\"',']'))
                      ";

        $query = $this->db->query($sentencia);

        return $query->result();
    }

}

