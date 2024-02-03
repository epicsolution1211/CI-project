<?= $this->extend('layouts/main'); ?>
<?= $this->section('title'); ?>
Editar Fabricante
<?= $this->endSection('title'); ?>

<?= $this->section('content'); ?>
<div class="card">
    <div class="card-header">
        <h4>Editar Fabricante</h4>
    </div>
    <div class="card-body">
        <form action="<?php echo base_url('fabricantes/' . $fabricante['id']); ?>" method="post" autocomplete="off">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="_method" value="PUT">
            <input type="hidden" name="id_fabricante" value="<?php echo $fabricante['id']; ?>">
            <div class="row">
                <div class="form-group col-lg-4">
                    <label>Nombre Fabricante</label>
                    <input type="text" name="nombre_fabricante" class="form-control" value="<?php echo set_value('nombre_fabricante', $fabricante['nombre_fabricante']); ?>" placeholder="Nombre Fabricante">
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