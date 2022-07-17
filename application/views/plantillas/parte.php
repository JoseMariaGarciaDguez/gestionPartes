<!DOCTYPE html>
<html>
<head>
    <title>PAR<?php echo($parte->id . " | " . $parte->fechaCreacion); ?></title>
    <meta charset="UTF-8">
    <style>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css"/>
        <?php include('css/bootstrap.min.css'); ?>
    </style>
</head>
<body>
<div class="container">
    <div class="row sp">
        <div class="col-xs-12">
            <div class="card ">
                <div class="card-content">
                    <div class="row">
                        <div class="col-xs-5 text-left">
                            <img src="<?php echo base_url()?>assets/images/A4PLUS.png">
                            <span class="card-title">4PLUSINT S.L</span>
                            <p>Calle Infanta Elena Nº12 P.I de Cho II</p>
                            <p>Arona, Santa Cruz de Tenerife.</p>
                            <?php if (!empty($canvas)) { ?>
                                <?php
                                $imageContent = file_get_contents($canvas);
                                $path = tempnam(sys_get_temp_dir(), '');
                                file_put_contents($path, $imageContent);
                                ?>
                            <?php } ?>
                            <span class="card-title">Parte de Trabajo</span>
                            <p>Referencia: <span class="badge">PAR<?php echo $parte->id; ?></span></p>
                            <p>Horas Empleadas: <span class="badge"><?php echo $parte->horas; ?></span>
                            </p>
                        </div>
                        <div class="col-xs-6">
                            <span class="card-title">Información General</span>
                            <p>Creado por: <?php echo $empleado->traducirEmpleado($parte->byEmpleadoID); ?></p>
                            <p>Fecha de creación: <?php echo $parte->fechaCreacion; ?></p>
                            <?php if (is_numeric($parte->obraID)) { ?>
                                <p><b>Obra asignada: </b> <a
                                            href="<?= base_url() ?>obra/ver/<?= $parte->obraID ?>"><?= $this->obra->traducirObra($parte->obraID) ?></a></br>
                                </p>
                            <?php } else { ?>
                                <p><b>Obra: </b><?= $parte->obraID ?></br></p>
                            <?php } ?>
                            <p>Tipo: <span
                                        class="badge"><?php echo $parte->traducirTipo($parte->tipo); ?></span>
                            </p>
                            <span class="card-title">Empleados asignados</span>
                            <ul><?php $query = $empleado->obtenerEmpleadosTabla()->result_array(); ?>
                                <?php foreach ($query as $row) { ?>
                                    <?php if (in_array($row['id'], $parte->empleadosID)) { ?>
                                        <li>
                                            <?php echo $row['nombre']; ?><?php echo $row['apellidos']; ?>
                                        </li>
                                    <?php } ?>
                                <?php } ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row sp">
        <?php if (!empty($parte->material)) { ?>
            <div class="col-xs-12">
                <div class="card">
                    <div class="card-content">
                        <span class="card-title">Material:</span>
                        <p><?php echo $parte->material; ?></p>
                    </div>
                </div>
            </div>
        <?php } ?>
        <?php if (!empty($parte->observaciones)) { ?>
            <div class="col-xs-12">
                <div class="card">
                    <div class="card-content">
                        <span class="card-title">Trabajo realizado:</span>
                        <p><?php echo $parte->observaciones; ?></p>
                    </div>
                </div>
            </div>
        <?php } ?>
        <?php if (!empty($parte->observacionesExtra)) { ?>
            <div class="col-xs-12">
                <div class="card">
                    <div class="card-content">
                        <span class="card-title">Observaciones extra:</span>
                        <p><?php echo $parte->observacionesExtra; ?></p>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
    <div class="row sp">
        <?php if (!empty($parte->firma)) { ?>
            <div class="col-xs-12">
                <div class="card">
                    <div class="card-content">
                        <span class="card-title">Firma:</span>
                        <p>DNI: <?php echo($parte->dni) ?></p>
                        <p><img src="<?php echo $path; ?>"></p>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
</body>
</html>