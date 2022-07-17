<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends CI_Controller
{

    public function index()
    {
        $this->load->model('Empleado_model');

        if ($this->session->userdata('email') == null)
            $this->load->view('login.php');
        else {
            if (!$this->Empleado_model->comprobarEmail($this->session->userdata('email'))) {
                $this->Empleado_model->cerrarSesion();
                $this->load->view('login.php');
            } else {
                redirect('/panel');
            }

        }
    }

    public function revisarFormulario()
    {
        $this->load->model('Empleado_model');

        $enviar = true;
        $titulo = '¡Algo es incorrecto!';

        $mensaje = '<ul>';

        if (empty($_POST['email']) || empty($_POST['password'])) {
            $mensaje .= '<li>No puedes dejar campos vacíos.</li>';
            $enviar = false;
        }

        if (!empty($_POST['email']) && !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $mensaje .= '<li>El correo electrónico introducido no es válido.</li>';
            $enviar = false;
        }

        if ($enviar && !$this->Empleado_model->comprobarEmail($_POST['email'])) {
            $mensaje .= '<li>El correo electrónico es erróneo, no existe en el sistema.</li>';
            $enviar = false;
        } elseif (!$this->Empleado_model->comprobarCredenciales($_POST['email'], $_POST['password'])) {
            $mensaje .= '<li>La contraseña es incorrecta.</li>';
            $enviar = false;
        }

        $mensaje .= '<ul>';

        if ($enviar) {
            $usuario = new Empleado_model(null, $_POST['email']);
            $usuario->iniciarSesion();
        }

        exit(json_encode(array("titulo" => $titulo, "mensaje" => $mensaje, "enviar" => $enviar)));
    }


    function cerrarSesion()
    {
        $this->load->model('Empleado_model');
        $usuario = new Empleado_model(null, $this->session->userdata('email'));
        $usuario->cerrarSesion();
        redirect('/login');
    }
}
