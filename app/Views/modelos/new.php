<?php include('handler.php') ?>
<?= $this->extend('layouts/main'); ?>
<?= $this->section('title'); ?>
Nuevo Modelo
<?= $this->endSection('title'); ?>

<?= $this->section('content'); ?>
<div class="card">
    <div class="card-header">
        <h4>Nuevo Modelo</h4>
    </div>
    <div class="card-body">
        <form action="<?php echo base_url('modelos'); ?>" method="post" autocomplete="off">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="id_modelo" value="0">
            <div class="row">
                <div class="form-group col-lg-4">
                    <label>Fabricante</label>


                    <select name="id_fabricante" id="id_fabricante[id_fabricante]" class="form-control">
                        <?php while($row2 = mysqli_fetch_array($result1)):;?>
                        <option><?php echo $row2[1];?></option>    
                        <?php endwhile;?>    
                    </select>
                        
               
                    <?php if (!empty($errors['id_fabricante'])) { ?>
                        <span class="text-danger"><?php echo $errors['id_fabricante']; ?></span>
                    <?php } ?>
                </div>
                <div class="form-group col-lg-4">
                    <label>Nombre Modelo</label>
                    <input type="text" minlength="3" name="nombre_modelo" class="form-control" value="<?php echo set_value('nombre_modelo'); ?>" placeholder="Nombre Modelo" required>
                    <?php if (!empty($errors['nombre_modelo'])) { ?>
                        <span class="text-danger"><?php echo $errors['nombre_modelo']; ?></span>
                    <?php } ?>
                </div>
                
            </div>
            <div class="text-end">
                <a href="<?php echo base_url('modelos'); ?>" class="btn btn-danger">Cancelar</a>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection('content'); ?>
