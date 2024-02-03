<?= $this->extend('layouts/main'); ?>
<?= $this->section('title'); ?>
Nuevo Fabricante
<?= $this->endSection('title'); ?>

<?= $this->section('content'); ?>
<div class="card">
    <div class="card-header">
        <h4>Nuevo Fabricante</h4>
    </div>
    <div class="card-body">
        <form action="<?php echo base_url('fabricantes'); ?>" method="post" autocomplete="off">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="id_fabricante" value="0">
            <div class="row">
                <div class="form-group col-lg-4">
                    <label>Nombre Fabricante</label>
                    <input type="text" minlength="3" name="nombre_fabricante" class="form-control" value="<?php echo set_value('nombre_fabricante'); ?>" placeholder="Nombre Fabricante" required>
                    <?php if (!empty($errors['nombre_fabricante'])) { ?>
                        <span class="text-danger"><?php echo $errors['nombre_fabricante']; ?></span>
                    <?php } ?>
                </div>
                
            </div>
            <div class="text-end">
                <a href="<?php echo base_url('fabricantes'); ?>" class="btn btn-danger">Cancelar</a>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection('content'); ?>