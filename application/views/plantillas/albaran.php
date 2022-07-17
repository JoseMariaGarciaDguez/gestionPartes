<!DOCTYPE html>
<html>
<head>
    <title>ALB<?php echo($albaran->id . " | " . $albaran->fechaCreacion); ?></title>
    <meta charset="UTF-8">
    <style>
        .productos {
            margin: 0;
        }

        .productos th {
            background-color: #4CAF50;
            color: white;
        }

        .productos tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        tbody {
            max-width: 10px;
        }
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
                            <span class="card-title">Albarán de Trabajo</span>
                            <p>Referencia:<span class="badge">ALB<?php echo $albaran->id; ?></span></p>
                            <p>Fecha: <span class="badge"><?php echo $albaran->fechaCreacion; ?></span></p>
                        </div>
                        <div class="col-xs-6">
                            <span class="card-title">Información del albarán.</span>
                            <h6>Cliente:</h6>
                            <p>Nombre:<span class="badge"><?php echo $albaran->nombre; ?></span></p>
                            <p>CIF:<span class="badge">ALB<?php echo $albaran->nif; ?></span></p>
                            <p>Dirección:<span class="badge"><?php echo $cliente->getDireccionCompleta() ?></span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row sp">
        <div class="col-xs-12">
            <table class="productos">
                <thead>
                <tr>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th class="p-xs-2">Precio</th>
                </tr>
                </thead>
                <tbody>
                <?php $subtotal = 0.00;
                if ($albaran->articulos != null) {
                    foreach (json_decode($albaran->articulos) as $articulo) { ?>
                        <?php
                        $subtotal = floatval($subtotal) + floatval($articulo[2] * $articulo[1]);
                        ?>
                        <tr>
                            <td><?php echo $articulo[0][2]; ?></td>
                            <td><?php echo $articulo[1]; ?></td>
                            <td><?php echo $articulo[2]; ?>€</td>
                        </tr>
                    <?php }
                } ?>
                </tbody>
            </table>
        </div>
        <div class="col-xs-12">
            <p class="lead"></p>
            <div class="table-responsive">
                <table class="table">
                    <tbody>
                    <tr>
                        <th>Subtotal:</th>
                        <td><?php echo $subtotal; ?>€</td>
                    </tr>
                    <tr>
                        <th>Total:</th>
                        <td><?php echo $subtotal; ?>€</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>