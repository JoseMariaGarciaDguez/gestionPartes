<?php
$title = "Panel de control";
$urlSegment1 = $this->uri->segment(1);
$urlSegment2 = $this->uri->segment(2);
$urlSegment3 = $this->uri->segment(3);

$this->load->helper('rol_helper');

if (!isset($urlSegment1)) $urlSegment1 = '';
if (!isset($urlSegment2)) $urlSegment2 = '';
if (!isset($urlSegment3)) $urlSegment3 = '';

if (!isset($empleado)) {
    $this->load->model('Empleado_model');
    $empleado = new Empleado_model($this->session->userdata('email'));
}

if (strpos($urlSegment1 . $urlSegment2 . $urlSegment3, "bloqueado") === false) {
    $resultado = tieneRol($urlSegment1, $urlSegment2, $urlSegment3, $empleado);

    if (!$resultado[0] && ($urlSegment2 != "sin_permiso" || $urlSegment2 != "perfil" || $urlSegment2 != "login")) redirect('/panel/sin_permiso');

    $tablaSoloCreados = true;
    if (strpos($resultado[1], 'todos') !== false) $tablaSoloCreados = false;
}

?>


<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Gestión A4Plus | <?= $title ?></title>

    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="<?= base_url() ?>assets/components/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>assets/components/adminlte/css/AdminLTE.css">
    <link rel="stylesheet" href="<?= base_url() ?>assets/components/font-awesome/css/all.css">

    <link rel="stylesheet" href="<?= base_url() ?>assets/components/adminlte/css/skins/_all-skins.css">
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/custom.css">

    <link rel="stylesheet" href="<?= base_url() ?>assets/components/font-awesome/css/googlefont.css">
</head>
<div style="display:none" id="tablaSoloCreados"><?php echo $tablaSoloCreados; ?></div>