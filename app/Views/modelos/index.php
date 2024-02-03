<?= $this->extend('layouts/main'); ?>
<?= $this->section('title'); ?>
Gestión Modelos
<?= $this->endSection('title'); ?>

<?= $this->section('content');

if (verificar('nuevo modelo', $_SESSION['permisos'])) { ?>
    <a href="<?php echo base_url('modelos/new'); ?>" class="btn btn-primary mb-2">Nuevo</a>
<?php } ?>
<div class="card">
    <div class="card-header">
        <h4>Gestión Modelos</h4>
    </div>
    <div class="card-body">
        <?php if (!empty(session()->getFlashdata('respuesta'))) { ?>
            <div class="alert alert-<?php echo session()->getFlashdata('respuesta')['type']; ?>">
                <?php echo session()->getFlashdata('respuesta')['msg']; ?>
            </div>
        <?php } ?>
        <div class="table-responsive">
            <table class="table table-striped nowrap" id="tblModelos" style="width:100%">
                <thead>
                    <tr>
                        <th>Acciones</th>
                        <th class="text-center">
                            #
                        </th>
                        <th>Fabricante</th>
                        <th>Modelo</th>
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
<script src="<?php echo base_url('assets/js/pages/modelos.js'); ?>"></script>
<?= $this->endSection('js'); ?>