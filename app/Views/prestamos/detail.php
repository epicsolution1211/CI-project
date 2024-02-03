<?= $this->extend('layouts/main'); ?>
<?= $this->section('title'); ?>
Detalle del Finaciamiento
<?= $this->endSection('title'); ?>

<?= $this->section('content'); ?>
<div class="card">
    <div class="card-header">
        <h4>Detalle del Finaciamiento</h4>
    </div>
    <div class="card-body">
        <?php if (!empty(session()->getFlashdata('respuesta'))) { ?>
            <div class="alert alert-<?php echo session()->getFlashdata('respuesta')['type']; ?>">
                <?php echo session()->getFlashdata('respuesta')['msg']; ?>
            </div>
        <?php } ?>
        <div class="row">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <ul class="list-group">
                            <li class="list-group-item"><i class="fas fa-calendar"></i> Fecha:
                                <?php
                                $dato = $prestamo['fecha'];
                                $fecha = date('Y-m-d', strtotime($dato));
                                // $hora = date('h:i A', strtotime($dato));
                                // echo fechaPerzo($fecha) . ' ' . $hora;
                                echo fechaPerzo($fecha);
                                ?></li>
                            <li class="list-group-item"><i class="fas fa-id-card"></i> N° OCR: <?php echo $prestamo['num_identidad']; ?></li>
                            <li class="list-group-item"><i class="fas fa-user"></i> Cliente: <?php echo $prestamo['cliente'] . ' ' . $prestamo['apellido']; ?></li>
                            <li class="list-group-item"><i class="fas fa-hashtag"></i> N° VIN: <?php echo $prestamo['vin']; ?></li>
                            <li class="list-group-item"><i class="fas fa-car"></i> Vehículo: <?php echo $prestamo['id_fabricante'] . ' ' . $prestamo['id_modelo']; ?></li>
                            <li class="list-group-item"><i class="fas fa-money-check-dollar"></i> Precio de Venta: <?php echo number_format($prestamo['precio']); ?> MXN</li>
                            <li class="list-group-item"><i class="fas fa-file-invoice-dollar"></i> Enganche: <?php echo number_format($prestamo['enganche']); ?> MXN</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <ul class="list-group">
                            <li class="list-group-item"><i class="fas fa-user"></i> Atendido: <?php echo $prestamo['usuario'] . ' ' . $prestamo['user_apellido']; ?></li>
                            <li class="list-group-item"><i class="fas fa-tag"></i> Cuotas: <?php echo $prestamo['cuotas']; ?></li>
                            <li class="list-group-item"><i class="fas fa-calendar"></i> Modalidad: <?php echo $prestamo['modalidad']; ?></li>   
                            <li class="list-group-item"><i class="fas fa-money-bill-wave"></i> Importe sin Interés: <?php echo number_format($prestamo['importe'], 2); ?> MXN</li>
                            <li class="list-group-item"><i class="fas fa-money-bill-trend-up"></i> Importe con Interés: <?php echo number_format(($prestamo['importe'] + ($prestamo['importe'] * ($prestamo['tasa_interes'] / 100))), 2); ?> MXN</li>
                            <li class="list-group-item"><i class="fas fa-percent"></i> Interés Mensual: <?php echo number_format(($prestamo['importe'] * ($prestamo['tasa_interes'] / 100) / $prestamo['cuotas']), 2); ?> MXN</li>
                            <li class="list-group-item"><i class="fas fa-percent"></i> Interés Total: <?php echo number_format(($prestamo['importe'] * ($prestamo['tasa_interes'] / 100)), 2); ?> MXN</li>
                            
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="mb-3">
                    <a href="<?php echo base_url('prestamos/' . $prestamo['id'] . '/reporte'); ?>" target="_blank" class="btn btn-primary"><i class="fas fa-file-pdf"></i> Reporte de Crédito</a>
                    <button type="button" id="btnCorreo" class="btn btn-warning"><i class="fas fa-envelope"></i> Enviar correo</button>
                    <button type="button" id="btnWhatsApp" class="btn btn-success"><i class="fab fa-whatsapp-square"></i> WhatsApp</button>
                    <button type="button" id="btnAdelanto" class="btn btn-info text-white"><i class="fas fa-money-bill"></i> Pago Personalizado</button>
                </div>
                <div style="display:flex; justify-content: space-between;">
                    <h6>Saldo Pendiente con Interés : <?php echo number_format($outstanding_balance_with,2);?></h6>
                    <h6>Saldo Pendiente sin Interés : <?php echo number_format($outstanding_balance_without,2);?></h6>
                    <h6>Interés Pagado : <?php echo number_format($paid_interest['interest'],2);?></h6>
                    <h6>Próximo Pago : <?php echo number_format($next_pay,2);?></h6>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">Cuotas</th>
                                <th scope="col">Vencimiento</th>
                                <th scope="col">Importe x cuota</th>
                                <th scope="col">Interés x cuota</th>
                                <th scope="col">Mensualidad</th>
                                <th scope="col">Estado</th>
                                <!-- <th scope="col"></th> -->
                            </tr>
                        </thead>
                        <tbody>
                            <?php $total = 0;

                            $cuotaLibre = ($prestamo['importe'] / $prestamo['cuotas']);
                            $interesMensual = ($prestamo['importe'] * ($prestamo['tasa_interes'] / 100) / $prestamo['cuotas']);
                            // $date = date('Y-m-d');
                            $date = $prestamo['fecha'];
                            foreach ($detalles as $detalle) {
                                $total += $detalle['importe_cuota'];
                                $color = substr(md5($detalle['id']), 0, 6);
                                $estado = '<span id="estadoCuota" class="badge badge-danger">PENDIENTE</span>';
                                if ($date > $detalle['fecha_venc'] && $detalle['estado'] == 1) {
                                    $class = 'bg-danger';
                                } else if ($date == $detalle['fecha_venc'] && $detalle['estado'] == 1) {
                                    $class = 'bg-warning';
                                } else {
                                    $class = '';
                                    if ($detalle['estado'] == 1) {
                                        $estado = '<span class="badge badge-danger">PENDIENTE</span>';
                                    } else {
                                        $estado = '<span class="badge badge-success">PAGADO</span>';
                                    }
                                }
                            ?>
                                <tr class="<?php echo $class; ?>">
                                    <td scope="row">
                                        <button type="button" class="btn" style="background-color: #<?php echo $color; ?>">
                                            Cuota <span class="badge badge-transparent text-dark"><?php echo $detalle['cuota']; ?></span>
                                        </button>
                                    </td>
                                    <td scope="row"><?php echo fechaPerzo($detalle['fecha_venc']); ?></td>
                                    <td scope="row">
                                        <span class="badge  text-dark"><?php echo number_format(($detalle['paid_installment']==0? $detalle['installment']: $detalle['paid_installment']),2); ?> MXN</span>
                                    </td>
                                    <!-- <td scope="row">
                                        <span class="badge  text-dark"><?php echo  number_format($cuotaLibre); ?> MXN</span>
                                    </td> -->
                                    <td scope="row">
                                        <span class="badge  text-dark"><?php echo number_format($detalle['interest'],2); ?> MXN</span>
                                    </td>

                                    </td>
                                    <td scope="row">
                                        <span class="badge badge-success text-dark"><?php echo number_format(($detalle['interest']+($detalle['paid_installment']==0? $detalle['installment']: $detalle['paid_installment'])),2); ?> MXN</span>
                                    </td>
                                    <td scope="row" class="estado_pago"><?php echo $estado; ?></td>
                                    <!-- <td>
                                        <?php if ($detalle['estado'] == 1) { ?>
                                            <form action="<?php echo base_url('prestamos/' . $detalle['id']); ?>" method="post" class="formEstado">
                                                <input type="hidden" name="_method" value="PUT">
                                                <?php echo csrf_field(); ?>
                                                <button type="submit" class="btn btn-primary"><i class="fas fa-check-circle"></i></button>
                                            </form>
                                        <?php } ?>
                                    </td> -->
                                </tr>
                            <?php } ?>
                            <!-- <tr>
                                <td colspan="3" class="text-end">
                                    <h3>Importe sin Interés: <?php echo number_format($total, 2); ?></h3>
                                </td>
                                <td colspan="3" class="text-end">
                                    <h3>Importe sin Interés: <?php echo number_format($prestamo['importe'], 2); ?></h3>
                                </td>
                                <td colspan="2"></td>
                            </tr> -->
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>
<?= $this->endSection('content'); ?>

<?= $this->section('modal'); ?>
<!-- custompayment modal -->
<div class="modal fade" id="custompayment" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="formModal">Cantidad personalizada</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?php echo base_url('prestamos/updateinstallment'); ?>" method="post" id="formcustompay">
                <div class="modal-body">
                    <?php echo csrf_field(); ?>
                
                    <input type="hidden" name="correo" value="<?php echo $prestamo['correo']; ?>">
                    <input type="hidden" name="id_prestamo" value="<?php echo $prestamo['id']; ?>">
                    <input type="hidden" id="total_amount" value="<?php echo ($outstanding_balance_without);?>">
                        

                    <div class="mb-3">
                        <input type="radio" id="out" class="mb-4" name="customize" value="<?php echo $outstanding_balance_without;?>">
                        <label for="out">Saldo Pendiente sin Interés : <?php echo $outstanding_balance_without;?></label><br>

                        <input type="radio" id="next" class="mb-4"  name="customize"  value="<?php echo ($next_pay > 0 ? $next_pay : 0);?>">
                        <label for="next">Próximo Pago sin Interés : <?php echo ($next_pay > 0 ? $next_pay : 0);?></label><br>
                        <p>Próximo Pago con Interés :<?php echo ($next_pay_with);?></p>

                        <div style="position: relative;display:flex;margin-top: -10px;">

                            <input type="radio" id="customize" class="mb-4" name="customize" value="" style= "margin-bottom:0px!important">
                            <label for="customize" style="align-self:center" >Cantidad personalizada</label><br>
                            
                            <input type="number" step="0.0001" class="form-control" name="mensaje" id="customamount" style="width:30%; margin-left:20px;" disabled></input>
                        </div>
                        
                        <?php if (isset($validator)) { ?>
                            <span class="text-danger"><?php echo $validator->getError('mensaje'); ?></span>
                        <?php } ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <button  class="btn btn-primary m-t-15 waves-effect buttoncustompay">Enviar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalMensaje" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="formModal">Mensaje</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="<?php echo base_url('prestamos/enviarCorreo'); ?>" method="post">
                <div class="modal-body">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="id_prestamo" value="<?php echo $prestamo['id']; ?>">
                    <input type="hidden" name="correo" value="<?php echo $prestamo['correo']; ?>">
                    <?php if (isset($validator)) { ?>
                        <span class="text-danger"><?php echo $validator->getError('correo'); ?></span>
                    <?php } ?>
                    <div class="mb-3">
                        <label for="" class="form-label">Mensaje</label>
                        <textarea class="form-control" name="mensaje" id="mensaje" rows="3"></textarea>
                        <?php if (isset($validator)) { ?>
                            <span class="text-danger"><?php echo $validator->getError('mensaje'); ?></span>
                        <?php } ?>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary m-t-15 waves-effect">Enviar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalWhatsApp" tabindex="-1" role="dialog" aria-labelledby="formModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Mensaje</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="num-whatsapp" value="<?php echo $prestamo['whatsapp']; ?>">
                <div class="mb-3">
                    <label for="" class="form-label">Mensaje</label>
                    <textarea class="form-control" id="mensaje-whatsapp" rows="3"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnWhatsappWeb" class="btn btn-primary m-t-15 waves-effect">Enviar</button>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection('modal'); ?>

<?= $this->section('js'); ?>
<script src="<?php echo base_url('assets/js/pages/prestamo-detail.js'); ?>"></script>
<script>
    <?php if (isset($validator)) { ?>
        myModal.show();
    <?php } ?>
</script>
<?= $this->endSection('js'); ?>