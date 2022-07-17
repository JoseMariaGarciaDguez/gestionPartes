<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Perfil extends CI_Controller
{

    public $empleado;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Empleado_model');
        if ($this->session->userdata('email') != null) $this->empleado = new Empleado_model(null, $this->session->userdata('email'));
        else redirect('/login');
    }

    public function index()
    {
        $this->load->view('perfil.php', array("empleado" => $this->empleado));
    }

    public function CambiarClaveFormulario()
    {
        $enviar = true;

        $mensaje = '<ul>';

        if (empty($_POST['oldpassword']) || empty($_POST['newpassword']) || empty($_POST['repassword'])) {
            $mensaje .= '<li>No puedes dejar campos vacíos.</li>';
            $enviar = false;
        }

        if ($enviar && !$this->empleado->comprobarCredenciales(null,$_POST['oldpassword'])) {
            $mensaje .= '<li>La contraseña actual es incorrecta.</li>';
            $enviar = false;
        } elseif ($enviar && $_POST['newpassword'] != $_POST['repassword']) {
            $mensaje .= '<li>Las contraseñas no coinciden.</li>';
            $enviar = false;
        } elseif ($enviar && $_POST['newpassword'] == $_POST['oldpassword']) {
            $mensaje .= '<li>Debes cambiarla por una nueva contraseña.</li>';
            $enviar = false;
        }

        if ($enviar) {
            $this->empleado->cambiarClave($_POST['repassword']);
            $mensaje .= '<li>Las contraseña se ha cambiado correctamente.</li>';
            $titulo = '¡Cambiado correctamente!';
        } else {
            $titulo = '¡Ha ocurrido un error!';
        }

        $mensaje .= '</ul>';

        exit(json_encode(array("titulo" => $titulo, "mensaje" => $mensaje, "enviar" => $enviar)));
    }

    public function CambiarDatosPerfilFormulario()
    {
        $enviar = true;

        $mensaje = '<ul>';

        if (empty($_POST['email']) || empty($_POST['nombre'])) {
            $mensaje .= '<li>No puedes dejar campos vacíos.</li>';
            $enviar = false;
        }

        if ($_POST['email'] != $this->empleado->email && $this->empleado->comprobarEmail($_POST['email'])) {
            $mensaje .= '<li>Ya existe ese correo electrónico.</li>';
            $enviar = false;
        }

        if ($enviar) {
            $this->empleado->insertarAvatar($_POST['avatarName']);

            if ($this->empleado->cambiarDatos($_POST['email'], $_POST['nombre'], $_POST['apellidos'])) {
                exit(json_encode(array("titulo" => 'RECARGAR_WEB', "mensaje" => $mensaje, "enviar" => $enviar)));
            }

            $mensaje .= '<li>El perfíl se ha actualizado correctamente.</li>';
            $titulo = '¡Actualizado correctamente!';
        } else {
            $titulo = '¡Ha ocurrido un error!';
        }

        $mensaje .= '</ul>';

        exit(json_encode(array("titulo" => $titulo, "mensaje" => $mensaje, "enviar" => $enviar)));
    }
}
