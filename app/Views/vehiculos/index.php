<?= $this->extend('layouts/main'); ?>
<?= $this->section('title'); ?>
Gestión Vehículos
<?= $this->endSection('title'); ?>

<?= $this->section('content');

if (verificar('nuevo vehiculo', $_SESSION['permisos'])) { ?>
    <a href="<?php echo base_url('vehiculos/new'); ?>" class="btn btn-primary mb-2">Nuevo</a>
<?php } ?>
<div class="card">
    <div class="card-header">
        <h4>Gestión Vehículos</h4>
    </div>
    <div class="card-body">
        <?php if (!empty(session()->getFlashdata('respuesta'))) { ?>
            <div class="alert alert-<?php echo session()->getFlashdata('respuesta')['type']; ?>">
                <?php echo session()->getFlashdata('respuesta')['msg']; ?>
            </div>
        <?php } ?>
        <div class="table-responsive">
            <table class="table table-striped nowrap" id="tblVehiculos" style="width:100%">
                <thead>
                    <tr>
                        <th>Acciones</th>
                        <th class="text-center">
                            #
                        </th>
                        <th>VIN</th>
                        <th>Fabricante</th>
                        <th>Modelo</th>
                        <th>Color</th>
                        <th>Categoría</th>
                        <th>Transmisión</th>
                        <th>Motor</th>
                        <th>Año</th>
                        <th>Kilometraje</th>
                        <th>Nº Puertas</th>
                        <th>Precio</th>
                        <th>Estatus</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection('content'); ?>

<?= $this->section('js'); ?>
<script src="<?php echo base_url('assets/js/pages/vehiculos.js'); ?>"></script>
<?= $this->endSection('js'); ?>