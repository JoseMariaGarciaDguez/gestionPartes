<!DOCTYPE html>
<html>
<?php include('includes/head.php'); ?>

<body class="hold-transition skin-green sidebar-mini" data-id="<?=$empleado->id?>">
<div class="wrapper">

    <?php include('includes/header.php'); ?>

    <!-- Left side column. contains the logo and sidebar -->
    <?php include('includes/sidebar.php'); ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Panel de Control
            </h1>
        </section>

        <!-- Main content -->
        <section class="content">
            <!-- Small boxes (Stat box) -->
            <div class="row">
                <div class="col-lg-4 col-xs-4">
                    <!-- small box -->
                    <div class="small-box bg-aqua">
                        <div class="inner">
                            <h3><?php $array = $empleado->obtenerPartesAsignados(date('d/m/Y')); ?><?= count($array) ?></h3>

                            <p>Partes asignados hoy <?= date('d/m/Y'); ?></p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                        <?php foreach ($array as $p) { ?>
                            <?php $obraName = $obra->traducirObra($p->obraID);
                            if (empty($obraName)) $obraName = "Sin nombre"; ?>
                            <a href="<?=base_url()?>parte/ver/<?= $p->id ?>" class="small-box-footer"><?= $obraName ?> <i
                                        class="fa fa-arrow-circle-right"></i></a>
                        <?php } ?>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-4 col-xs-4">
                    <!-- small box -->
                    <div class="small-box bg-green">

                        <?php
                        $array = $asignador->contarEmpleado($empleado->id, date('d/m/Y'));
                        $html = '';
                        $x = 0;
                        foreach ($array as $p) {
                            foreach (json_decode($p->obrasID) as $o) {
                                $obraName = $obra->traducirObra($o);
                                if (empty($obraName)) $obraName = "VEH$o";
                                $html .= '<a href="'.base_url().'obra/ver/'.$o.'" class="small-box-footer">'.$obraName.' <i
                                            class="fa fa-arrow-circle-right"></i></a>';
                                $x++;
                            }
                            break;
                        }
                        ?>

                        <div class="inner">
                            <h3><?= $x ?></h3>

                            <p>Obras asignadas hoy <?= date('d/m/Y'); ?></p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                        <?=$html;?>
                    </div>
                </div>
                <!-- ./col -->
                <div class="col-lg-4 col-xs-4">
                    <!-- small box -->
                    <div class="small-box bg-yellow">

                        <?php
                        $array = $asignador->contarEmpleado($empleado->id, date('d/m/Y'));
                        $html = '';
                        $x = 0;
                        foreach ($array as $p) {
                            foreach (json_decode($p->vehiculosID) as $o) {
                                $veh = new Vehiculo_model($o);
                                $html .= '<a href="'.base_url().'vehiculo/ver/' . $o . '"
                                             class="small-box-footer">VEH' . $veh->id . ' <i
                                            class="fa fa-arrow-circle-right"></i></a>';
                                $x++;
                            }
                            break;
                        }
                        ?>

                        <div class="inner">
                            <h3><?= $x ?></h3>

                            <p>Vehículos asignados hoy <?= date('d/m/Y'); ?></p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                        <?= $html ?>
                    </div>
                </div>
                <!-- ./col -->
            </div>
            <!-- /.row -->
            <!-- Main row -->

            <div class="row asignadorCards">
                <?= $asignador->obtenerVisorHTML(null,$empleado->id) ?>
            </div>
            <!-- /.row (main row) -->

        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <?php include('includes/footer.php'); ?>
    <?php include('includes/sidebar_config.php'); ?>
</div>
<!-- ./wrapper -->

</body>
<script>
    var item = $('.asignadorCards .col-md-3');
    item.removeClass('col-md-3').addClass('col-md-12');
    item.find('.row').addClass('col-md-4');
    console.log(item.find('.asignadorContenedor').closest('div'));
    setInterval(function () {
        $.ajax({
            type: "POST",
            data: {update:true,empleadoID:$('body').attr('data-id')},
            url: BASE_URL+'/asignador/actualizarDatos',
            dataType: "html",
            success: function(data){
                console.log(data);
                $('.asignadorCards').html('');
                $('.asignadorCards').html(data);
                $('.asignadorCards .col-md-3').find('.row').addClass('col-md-4');
                $('.asignadorCards .col-md-3').removeClass('col-md-3').addClass('col-md-12');
            }
        });
    },5000);
</script>
</html>
