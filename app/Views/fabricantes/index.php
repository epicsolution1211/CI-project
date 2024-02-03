<?= $this->extend('layouts/main'); ?>
<?= $this->section('title'); ?>
Gestión Fabricantes
<?= $this->endSection('title'); ?>

<?= $this->section('content');

if (verificar('nuevo fabricante', $_SESSION['permisos'])) { ?>
    <a href="<?php echo base_url('fabricantes/new'); ?>" class="btn btn-primary mb-2">Nuevo</a>
<?php } ?>
<div class="card">
    <div class="card-header">
        <h4>Gestión Fabricantes</h4>
    </div>
    <div class="card-body">
        <?php if (!empty(session()->getFlashdata('respuesta'))) { ?>
            <div class="alert alert-<?php echo session()->getFlashdata('respuesta')['type']; ?>">
                <?php echo session()->getFlashdata('respuesta')['msg']; ?>
            </div>
        <?php } ?>
        <div class="table-responsive">
            <table class="table table-striped nowrap" id="tblFabricantes" style="width:100%">
                <thead>
                    <tr>
                        <th>Acciones</th>
                        <th class="text-center">
                            #
                        </th>
                        <th>Nombre Fabricante</th>
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
<script src="<?php echo base_url('assets/js/pages/fabricantes.js'); ?>"></script>
<?= $this->endSection('js'); ?>