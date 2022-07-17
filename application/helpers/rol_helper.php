<?php
function tieneRol($seg1, $seg2, $seg3, $empleado)
{

    if ($empleado->estado == "B") redirect(base_url() . 'panel/bloqueado');
    if (empty($seg2)) $seg2 = 'ver';
    if ($seg2 == 'editar' || $seg2 == 'ver') {

        if (!empty($seg3)) {
            //Comprobar creaciones
            if ($seg1 == "cliente") {
                $modelo = new Cliente_model($seg3);
                if ($empleado->id == $modelo->byEmpleadoID) $seg3 = 'creados'; else $seg3 = 'todos';
            } else if ($seg1 == "albaran") {
                $modelo = new Albaran_model($seg3);
                if ($empleado->id == $modelo->byEmpleadoID) $seg3 = 'creados'; else $seg3 = 'todos';
            } else if ($seg1 == "parte") {
                $modelo = new Parte_model($seg3);
                if ($seg2 == "editar" && $empleado->rol->tieneRol('parte_ver_participado', $empleado)) $seg3 = 'participado';
                elseif ($seg2 == "ver" && $empleado->rol->tieneRol('parte_ver_participado', $empleado)) $seg3 = 'participado';
                elseif ($seg2 == "borrar" && $empleado->rol->tieneRol('parte_borrar_participado', $empleado)) $seg3 = 'participado';
                elseif ($empleado->id == $modelo->byEmpleadoID) $seg3 = 'creados';
                else $seg3 = 'todos';
            } else if ($seg1 == "obra") {
                $modelo = new Obra_model($seg3);
                if ($empleado->id == $modelo->byEmpleadoID) $seg3 = 'creados'; else $seg3 = 'todos';
            } else if ($seg1 == "empleado") {
                $modelo = new Empleado_model($seg3);
                if ($empleado->id == $modelo->byEmpleadoID) $seg3 = 'creados'; else $seg3 = 'todos';
            } else if ($seg1 == "vehiculo") {
                $modelo = new Vehiculo_model($seg3);
                if ($empleado->id == $modelo->byEmpleadoID) $seg3 = 'creados'; else $seg3 = 'todos';
            } else if ($seg1 == "asignador") {
                $modelo = new Asignador_model();
                if ($empleado->id == $modelo->obtenerByEmpleadoID($seg3)) $seg3 = 'creados'; else $seg3 = 'todos';
            } else if ($seg1 == "rol") {
                $modelo = new Rol_model($seg3);
                if ($empleado->id == $modelo->byEmpleadoID) $seg3 = 'creados'; else $seg3 = 'todos';
            }
        } else {
            if (isset($empleado->rol->permisos))
                if (in_array($seg1 . '_' . $seg2 . '_' . 'todos', json_decode($empleado->rol->permisos))) $seg3 = 'todos'; else $seg3 = 'creados';
        }
    }


    $permisoCompuesto = $seg1 . '_' . $seg2 . '_' . $seg3;
    $permisoCompuesto = rtrim($permisoCompuesto, '_');
    //print $permisoCompuesto.'</br>';

    if (
        $seg1 == 'panel' ||
        $seg1 == 'perfil' ||
        $seg1 == 'login' ||
        $empleado->rolID == -1
    ) return array(true, 'todos');

    return array(in_array($permisoCompuesto, json_decode($empleado->rol->permisos)), $seg3);
}

function obtenerPermisos($tipo = null, $rol = null, $marcable = true)
{
    $permisos = array(
        "cliente_ver_todos",
        "cliente_ver_creados",
        "cliente_crear",
        "cliente_editar_todos",
        "cliente_editar_creados",
        "cliente_borrar_creados",
        "cliente_borrar_todos",
        "empleado_ver_todos",
        "empleado_ver_creados",
        "empleado_crear",
        "empleado_editar_todos",
        "empleado_editar_creados",
        "empleado_borrar_creados",
        "empleado_borrar_todos",
        "empleado_editar_nombre",
        "empleado_editar_email",
        "empleado_ver_última_conexión",
        "empleado_editar_estado",
        "empleado_poder_participar",
        "albaran_ver_todos",
        "albaran_ver_creados",
        "albaran_crear",
        "albaran_editar_todos",
        "albaran_editar_creados",
        "albaran_borrar_creados",
        "albaran_borrar_todos",
        "parte_ver_todos",
        "parte_ver_creados",
        "parte_ver_participado",
        "parte_crear",
        "parte_crear_fecha",
        "parte_editar_todos",
        "parte_editar_creados",
        "parte_editar_fecha",
        "parte_editar_participado",
        "parte_borrar_creados",
        "parte_borrar_participado",
        "parte_borrar_todos",
        "obra_ver_todos",
        "obra_ver_creados",
        "obra_crear",
        "obra_editar_todos",
        "obra_editar_creados",
        "obra_borrar_creados",
        "obra_borrar_todos",
        "vehiculo_ver_todos",
        "vehiculo_ver_creados",
        "vehiculo_crear",
        "vehiculo_editar_todos",
        "vehiculo_editar_creados",
        "vehiculo_borrar_creados",
        "vehiculo_borrar_todos",
        "informes_ver_todos",
        "asignador_ver_todos",
        "asignador_ver_creados",
        "asignador_editar_todos",
        "asignador_editar_creados",
        "asignador_borrar_todos",
        "asignador_borrar_creados",
        "asignador_historial",
        "asignador_ser_asignado",
        "rol_ver_creados",
        "rol_ver_todos",
        "rol_editar_creados",
        "rol_editar_todos",
        "rol_borrar_creados",
        "rol_borrar_todos",
        "rol_crear"
    );

    $html = "<ul class='list-group permisosLista' >";
    foreach ($permisos as $perm) {
        $explode = explode("_", $perm);
        $explode2 = "";
        $selected = "";
        if (count($explode) > 2) $explode2 = $explode[2];
        if (isset($rol) && in_array($perm, json_decode($rol->permisos))) $selected = "selected";
        if ($tipo == null || $tipo == $explode[0]) {
            if ($marcable)
                $html .= '<li class="list-group-item"><i style="padding-left: 15px;" data-permiso="' . $perm . '" class="checkboxTabla fa fa-square permInput ' . $selected . '"></i><label>' . ucfirst($explode[1]) . ' ' . $explode2 . '</label></li>';
            else {
                if ($selected == "selected")
                    $html .= '<li class="list-group-item"><label>' . $explode[1] . ' ' . $explode2 . '</label></li>';
            }
        }
    }
    $html .= "</ul>";
    return $html;
}

