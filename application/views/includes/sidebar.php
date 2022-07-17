<?php
$segmento = $this->uri->segment(1);
?>


<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">NAVEGACIÓN PRINCIPAL</li>

            <li>
                <a href="<?= base_url() ?>panel">
                    <i class="fa fa-tachometer-alt"></i> <span>Panel</span>
                </a>
            </li>
            <?php if (($empleado->rol->tieneRol("cliente_ver_todos", $empleado) || $empleado->rol->tieneRol("cliente_ver_creados", $empleado))) { ?>
                <li class="treeview <?php if ($segmento == 'cliente') echo "menu-open"; ?>">
                    <a href="#">
                        <i class="fa fa-user"></i> <span>Clientes</span>
                        <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
                </span>
                    </a>
                    <ul class="treeview-menu" style="<?php if ($segmento == 'cliente') echo "display:block;"; ?>">
                        <?php if (($empleado->rol->tieneRol("cliente_ver_todos", $empleado) || $empleado->rol->tieneRol("cliente_ver_creados", $empleado))) { ?>
                            <li><a href="<?= base_url() ?>cliente"><i class="fa fa-user"></i> Ver clientes</a></li>
                        <?php } ?>
                        <?php if ($empleado->rol->tieneRol("cliente_crear", $empleado)) { ?>
                            <li><a href="<?= base_url() ?>cliente/crear"><i class="fa fa-user-plus"></i> Crear
                                    cliente</a></li>
                        <?php } ?>
                    </ul>
                </li>
            <?php } ?>

            <?php if (($empleado->rol->tieneRol("albaran_ver_todos", $empleado) || $empleado->rol->tieneRol("albaran_ver_creados", $empleado))) { ?>
                <li class="treeview">
                    <a href="#">
                        <i class="fa fa-file-invoice-dollar "></i> <span>Albaranes</span>
                        <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                    </a>
                    <ul class="treeview-menu">
                        <?php if (($empleado->rol->tieneRol("albaran_ver_todos", $empleado) || $empleado->rol->tieneRol("albaran_ver_creados", $empleado))) { ?>
                            <li><a href="<?= base_url() ?>albaran"><i class="fa fa-file-invoice-dollar "></i> Ver
                                    albaranes</a></li>
                        <?php } ?>
                        <?php if ($empleado->rol->tieneRol("albaran_crear", $empleado)) { ?>
                            <li><a href="<?= base_url() ?>albaran/crear"><i class="fa fa-plus-square "></i> Crear
                                    albaran</a></li>
                        <?php } ?>
                    </ul>
                </li>
            <?php } ?>

            <?php if (($empleado->rol->tieneRol("parte_ver_todos", $empleado) || $empleado->rol->tieneRol("parte_ver_creados", $empleado))) { ?>
                <li class="treeview <?php if ($segmento == 'parte') echo "menu-open"; ?>">
                    <a href="#">
                        <i class="fa fa-calendar"></i> <span>Partes</span>
                        <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                    </a>
                    <ul class="treeview-menu" style="<?php if ($segmento == 'parte') echo "display:block;"; ?>">
                        <?php if (($empleado->rol->tieneRol("parte_ver_todos", $empleado) || $empleado->rol->tieneRol("parte_ver_creados", $empleado))) { ?>
                            <li><a href="<?= base_url() ?>parte"><i class="fa fa-calendar "></i> Ver partes</a></li>
                        <?php } ?>
                        <?php if ($empleado->rol->tieneRol("parte_crear", $empleado)) { ?>
                            <li><a href="<?= base_url() ?>parte/crear"><i class="fa fa-calendar-plus"></i> Crear
                                    parte</a></li>
                        <?php } ?>
                    </ul>
                </li>
            <?php } ?>

            <?php if (($empleado->rol->tieneRol("obra_ver_todos", $empleado) || $empleado->rol->tieneRol("obra_ver_creados", $empleado))) { ?>
                <li class="treeview <?php if ($segmento == 'obra') echo "menu-open"; ?>">
                    <a href="#">
                        <i class="fa fa-file-contract"></i> <span>Obras</span>
                        <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                    </a>
                    <ul class="treeview-menu" style="<?php if ($segmento == 'obra') echo "display:block;"; ?>">
                        <?php if (($empleado->rol->tieneRol("obra_ver_todos", $empleado) || $empleado->rol->tieneRol("obra_ver_creados", $empleado))) { ?>
                            <li><a href="<?= base_url() ?>obra"><i class="fa fa-file-contract "></i> Ver obras</a></li>
                        <?php } ?>
                        <?php if ($empleado->rol->tieneRol("obra_crear", $empleado)) { ?>
                            <li><a href="<?= base_url() ?>obra/crear"><i class="fa fa-file-signature "></i> Crear
                                    obra</a></li>
                        <?php } ?>
                    </ul>
                </li>
            <?php } ?>

            <?php if (($empleado->rol->tieneRol("empleado_ver_todos", $empleado) || $empleado->rol->tieneRol("empleado_ver_creados", $empleado))) { ?>
                <li class="treeview <?php if ($segmento == 'empleado') echo "menu-open"; ?>">
                    <a href="#">
                        <i class="fa fa-user-tie"></i> <span>Empleados</span>
                        <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                    </a>
                    <ul class="treeview-menu" style="<?php if ($segmento == 'empleado') echo "display:block;"; ?>">
                        <?php if ($empleado->rol->tieneRol("empleado_ver_todos", $empleado) || $empleado->rol->tieneRol("empleado_ver_creados", $empleado)) { ?>
                            <li><a href="<?= base_url() ?>empleado"><i class="fa fa-user-tie"></i> Ver empleados</a>
                            </li>
                        <?php } ?>
                        <?php if ($empleado->rol->tieneRol("empleado_crear", $empleado)) { ?>
                            <li><a href="<?= base_url() ?>empleado/crear"><i class="fa fa-file-signature "></i> Crear
                                    empleado</a></li>
                        <?php } ?>

                        <?php if ($empleado->rol->tieneRol("rol_ver_todos", $empleado) || $empleado->rol->tieneRol("rol_ver_creados", $empleado)) { ?>
                            <li class="treeview">
                                <a href="#"><i class="fa fa-user-circle"></i> Roles
                                    <span class="pull-right-container"><i
                                                class="fa fa-angle-left pull-right"></i></span>
                                </a>
                                <ul class="treeview-menu">
                                    <li><a href="<?= base_url() ?>rol"><i class="fa fa-user-circle "></i> Ver roles</a>
                                    </li>
                                    <?php if ($empleado->rol->tieneRol("rol_crear", $empleado)) { ?>
                                        <li><a href="<?= base_url() ?>rol/crear"><i class="fa fa-plus-circle"></i> Crear
                                                rol</a></li>
                                    <?php } ?>
                                </ul>
                            </li>
                        <?php } ?>
                    </ul>
                </li>
            <?php } ?>


            <?php if (($empleado->rol->tieneRol("vehiculo_ver_todos", $empleado) || $empleado->rol->tieneRol("vehiculo_ver_creados", $empleado)) && $empleado->rol->tieneRol("vehiculo_crear", $empleado)) { ?>
                <li class="treeview <?php if ($segmento == 'vehiculo') echo "menu-open"; ?>">
                    <a href="#">
                        <i class="fa fa-truck"></i> <span>Vehiculos</span>
                        <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                    </a>
                    <ul class="treeview-menu" style="<?php if ($segmento == 'vehiculo') echo "display:block;"; ?>">
                        <?php if (($empleado->rol->tieneRol("vehiculo_ver_todos", $empleado) || $empleado->rol->tieneRol("vehiculo_ver_creados", $empleado))) { ?>
                            <li><a href="<?= base_url() ?>vehiculo"><i class="fa fa-eye"></i> Ver vehiculos</a></li>
                        <?php } ?>
                        <?php if ($empleado->rol->tieneRol("vehiculo_crear", $empleado)) { ?>
                            <li><a href="<?= base_url() ?>vehiculo/crear"><i class="fa fa-plus-square"></i> Crear
                                    vehiculo</a></li>
                        <?php } ?>
                    </ul>
                </li>
            <?php } ?>


            <?php if (($empleado->rol->tieneRol("asignador_ver_todos", $empleado) || $empleado->rol->tieneRol("asignador_ver_creados", $empleado)) || $empleado->rol->tieneRol("asignador_historial", $empleado) || $empleado->rol->tieneRol("asignador_editar_todos", $empleado) || $empleado->rol->tieneRol("asignador_editar_creados", $empleado)) { ?>
                <li class="treeview <?php if ($segmento == 'asignador') echo "menu-open"; ?>">
                    <a href="#">
                        <i class="fa fa-hand-paper"></i> <span>Asignaciones</span>
                        <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
                    </a>
                    <ul class="treeview-menu" style="<?php if ($segmento == 'asignador') echo "display:block;"; ?>">
                        <?php if (($empleado->rol->tieneRol("asignador_ver_todos", $empleado) || $empleado->rol->tieneRol("asignador_ver_creados", $empleado))) { ?>
                            <li><a href="<?= base_url() ?>asignador"><i class="fa fa-eye"></i> Ver asignaciones</a></li>
                        <?php } ?>
                        <?php if ($empleado->rol->tieneRol("asignador_editar_todos", $empleado) || $empleado->rol->tieneRol("asignador_editar_creados", $empleado)) { ?>
                            <li><a href="<?= base_url() ?>asignador/editar"><i class="fa fa-plus-square"></i> Editar
                                    asignaciones</a>
                            </li>
                        <?php } ?>

                        <?php if ($empleado->rol->tieneRol("asignador_historial", $empleado)) { ?>
                        <li><a href="<?= base_url() ?>asignador/historial"><i class="fa fa-search"></i> Historial de
                                asignaciones</a>
                            <?php } ?>


                        </li>
                    </ul>
                </li>
            <?php } ?>

            <?php if (($empleado->rol->tieneRol("informes_ver_todos", $empleado) || $empleado->rol->tieneRol("informes_ver_creados", $empleado))) { ?>
                <li><a href="<?= base_url() ?>informes"><i class="fa fa-receipt"></i> <span>Informes</span></a></li>
            <?php } ?>
            <li><a href="<?= base_url() ?>perfil"><i class="fa fa-id-card-alt"></i> <span>Perfíl</span></a></li>


        </ul>
    </section>
    <!-- /.sidebar -->
</aside>