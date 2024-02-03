<?= $this->extend('layouts/main'); ?>
<?= $this->section('title'); ?>
Historial Finaciamientos
<?= $this->endSection('title'); ?>

<?= $this->section('content'); ?>
<div class="card">
    <div class="card-header">
        <h4>Historial Finaciamientos</h4>
    </div>
    <div class="card-body">
        <input type="hidden" id="fecha_actual" value="<?php echo date('Y-m-d'); ?>">
        <?php if (!empty(session()->getFlashdata('respuesta'))) { ?>
            <div class="alert alert-<?php echo session()->getFlashdata('respuesta')['type']; ?>">
                <?php echo session()->getFlashdata('respuesta')['msg']; ?>
            </div>
        <?php } ?>
        <div class="">
            <table class="table table-striped nowrap" id="tblPrestamos" style="width:100%">
                <thead>
                    <tr>
                        <th>Acciones</th>
                        <th class="text-center">
                            #
                        </th>
                        <th>Cliente</th>
                        <th>Vehículo</th>
                        <!-- <th>Importe</th>
                        <th>Modalidad</th> -->
                        <th>F. venc.</th>
                        <th><p>Saldo Pendiente</p><p> con Interés</p></th>
                        <th>Ganacia</th>
                        <th>Usuario</th>
                        <th>Estado</th>
                    </tr>
                </thead>
                <tbody style="text-align:center">

                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection('content'); ?>

<?= $this->section('js'); ?>
<script src="<?php echo base_url('assets/js/pages/prestamos-historial.js'); ?>"></script>
<?= $this->endSection('js'); ?>