<!DOCTYPE html>
<html>
<?php include 'includes/head.php'; ?>
<?php
$id = null;
if (!is_null($this->uri->segment(3))) $id = $this->uri->segment(3);
?>

<body class="hold-transition skin-green sidebar-mini">
<div class="wrapper">

    <?php include 'includes/header.php'; ?>

    <!-- Left side column. contains the logo and sidebar -->
    <?php include 'includes/sidebar.php'; ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <?= $asignador->getTitleHTML('Asignador ' . $asignador->obtenerFecha($id, true)); ?>

        <!-- Main content -->
        <section class="content">
            <div class="scroll-bar">
                <div class="row asignadorCards">
                    <?= $asignador->obtenerVisorHTML($id) ?>
                </div>
            </div>
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <?php include 'includes/footer.php'; ?>
    <?php include 'includes/sidebar_config.php'; ?>
</div>

</body>

<script src="<?= base_url() ?>assets/js/asignador.js"></script>
</html>
