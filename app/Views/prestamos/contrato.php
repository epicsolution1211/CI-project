<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Crédito</title>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/reporte.css'); ?>">
</head>

<body>
    <table id="datos-empresa">
        <tr>
            <td class="logo">
                <img src="<?php echo base_url('assets/img/logo.png'); ?>" alt="">
            </td>
            <td class="info-empresa">
                <p><?php echo $empresa['nombre']; ?></p>
                <p><?php echo $empresa['identidad']; ?></p>
                <p>Telefono: <?php echo $empresa['telefono']; ?></p>
                <p>Dirección: <?php echo $empresa['direccion']; ?></p>
            </td>
            <td class="info-fecha">
                <div class="container-fecha">
                    <span class="contrato">Reporte de Crédito</span>
                    <p>N° <strong><?php echo $prestamo['id']; ?></strong></p>
                    <p>Fecha: <?php
                                $dato = $prestamo['fecha'];
                                $fecha = date('Y-m-d', strtotime($dato));
                                // $hora = date('h:i A', strtotime($dato));
                                echo fechaPerzo($fecha);
                                ?></p>
                </div>
            </td>
        </tr>
    </table>
    <h5 class="title">Datos del cliente</h5>
    <div class="container-cliente">
        <table id="container-info">
            <tr>
                <td>
                    <strong style="width:180px"><?php echo $prestamo['identidad']; ?></strong>
                    <p><?php echo $prestamo['num_identidad']; ?></p>
                </td>
                <td>
                    <strong style="width:180px">Nombre</strong>
                    <p><?php echo $prestamo['cliente'] . ' ' . $prestamo['apellido']; ?></p>
                </td>
            </tr>
            <tr>
                <td>
                    <strong style="width:180px">Teléfono</strong>
                    <p><?php echo $prestamo['telefono']; ?></p>
                </td>
                <td>
                    <strong style="width:180px">Dirección</strong>
                    <p><?php echo $prestamo['direccion']; ?></p>
                </td>
            </tr>
            <tr>
                <td>
                    <strong style="width:180px">Nº VIN</strong>
                    <p><?php echo $prestamo['vin']; ?></p>
                </td>
                <td>
                    <strong style="width:180px">Vehículo</strong>
                    <p><?php echo $prestamo['id_fabricante'] . ' ' . $prestamo['id_modelo']; ?></p>
                </td>
            </tr>
            <tr>
                <td>
                    <strong style="width:180px">Precio</strong>
                    <p><?php echo number_format($prestamo['precio']); ?> MXN</p>
                </td>
                <td>
                    <strong style="width:180px">Enganche</strong>
                    <p><?php echo number_format($prestamo['enganche']); ?> MXN</p>
                </td>
            </tr>
            <tr>
                <td>
                    <strong style="width:180px">Modalidad</strong>
                    <p><?php echo $prestamo['modalidad']; ?></p>
                </td>
                <td>
                    <strong style="width:180px">Cuotas</strong>
                    <p><?php echo $prestamo['cuotas']; ?></p>
                </td>
            </tr>

            <tr>
                <td>
                    <strong style="width:180px">Importe</strong>
                    <p><?php echo number_format($prestamo['importe'], 2); ?> MXN</p>
                </td>
                <td>
                    <strong style="width:180px">Crédito</strong>
                    <p><?php echo number_format(($prestamo['importe'] + ($prestamo['importe'] * ($prestamo['tasa_interes'] / 100))), 2); ?> MXN</p>
                </td>
            </tr>

            <tr>
                <td>
                    <strong style="width:180px">Interés M</strong>
                    <p><?php echo number_format(($prestamo['importe'] * ($prestamo['tasa_interes'] / 100) / $prestamo['cuotas']), 2); ?> MXN</p>
                </td>
                <td>
                    <strong style="width:180px">Interés T</strong>
                    <p><?php echo number_format(($prestamo['importe'] * ($prestamo['tasa_interes'] / 100)), 2); ?> MXN</p>
                </td>
            </tr>
            <tr>
                <td>
                    <strong style="width:180px">Tasa %</strong>
                    <p><?php echo $prestamo['tasa_interes']; ?> %</p>
                </td>
            </tr>
            <tr>
                <td>
                    <strong style="width:180px">Saldo Pendiente con Interés</strong>
                    <p><?php echo number_format($outstanding_balance_with,2); ?></p>
                </td>
                <td>
                    <strong style="width:180px">Saldo Pendiente sin Interés</strong>
                    <p><?php echo number_format($outstanding_balance_without,2); ?></p>
                </td>
            </tr>
            <tr>
                <td>
                    <strong style="width:180px">Interés Pagado</strong>
                    <p><?php echo number_format($paid_interest['interest']); ?></p>
                </td>
                <td>
                    <strong style="width:180px">Próximo Pago</strong>
                    <p><?php echo number_format($next_pay); ?></p>
                </td>
            </tr>

            
        </table>
    </div>
    <h5 class="title">Datos de las cuotas</h5>
    <table id="container-cuotas">
        <thead>
            <tr>
                <th class="text-left">Item</th>
                <th class="text-left">Cuota</th>
                <th class="text-left">Vencimiento</th>
                <th class="text-left">Importe x cuota</th>
                <th class="text-left">Interés x cuota</th>
                <th class="text-left">Mensualidad</th>
                <th class="text-left">Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php $item = 1;
            $total = 0;
            $date = date('Y-m-d');
            foreach ($detalles as $detalle) {
                $total += $detalle['importe_cuota'];
                $estado = '<span class="text-danger">PENDIENTE</span>';
                if ($date > $detalle['fecha_venc'] && $detalle['estado'] == 1) {
                    $class = 'bg-danger';
                } else if ($date == $detalle['fecha_venc'] && $detalle['estado'] == 1) {
                    $class = 'bg-warning';
                } else {
                    $class = '';
                    if ($detalle['estado'] == 1) {
                        $estado = '<span class="text-danger">PENDIENTE</span>';
                    } else {
                        $estado = '<span class="text-success">PAGADO</span>';
                    }
                }
            ?>
                <tr class="<?php echo $class; ?>">
                    <td><?php echo $item; ?></td>
                    <td>Cuota <?php echo $detalle['cuota']; ?></td>
                    <td><?php echo fechaPerzo($detalle['fecha_venc']); ?></td>
                    <td><?php echo number_format(($detalle['paid_installment']==0? $detalle['installment']: $detalle['paid_installment']),2); ?></td>
                    <td><?php echo number_format($detalle['interest'],2); ?></td>
                    <td><?php echo number_format(($detalle['interest']+($detalle['paid_installment']==0? $detalle['installment']: $detalle['paid_installment'])),2); ?></td>
                    <td><?php echo $estado; ?></td>
                </tr>
            <?php $item++;
            } ?>
            <tr>
                <td colspan="4" class="text-right">
                    <h3>Total <?php echo number_format($total, 2); ?></h3>
                </td>
                <td></td>
            </tr>
        </tbody>
    </table>
    <div class="mensaje">
        <?php
        echo $empresa['mensaje'];
        if ($prestamo['estado'] == 0) {
            echo '<h1>CONTRATO FINALIZADO</h1>';
        }
        ?>
    </div>
</body>

</html>