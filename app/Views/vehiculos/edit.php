<?php include('handler.php') ?>
<?= $this->extend('layouts/main'); ?>
<?= $this->section('title'); ?>
Editar Vehículo
<?= $this->endSection('title'); ?>

<?= $this->section('content'); ?>
<div class="card">
    <div class="card-header">
        <h4>Editar Vehículo</h4>
    </div>
    <div class="card-body">
        <form action="<?php echo base_url('vehiculos/' . $vehiculo['id']); ?>" method="post" autocomplete="off">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="_method" value="PUT">
            <input type="hidden" name="id_vehiculo" value="<?php echo $vehiculo['id']; ?>">
            <div class="row">
                <div class="form-group col-lg-4">
                    <label>VIN</label>
                    <input type="text" maxlength="17" name="vin" class="form-control" value="<?php echo set_value('vin', $vehiculo['vin']); ?>" placeholder="VIN">
                    <?php if (!empty($errors['vin'])) { ?>
                        <span class="text-danger"><?php echo $errors['vin']; ?></span>
                    <?php } ?>
                </div>
                <div class="form-group col-lg-4">
                    <label>Fabricante</label>

                    <select name="id_fabricante" class="form-control">
                        <?php while($row2 = mysqli_fetch_array($result1)):;?>
                            <option value="<?php echo $vehiculo['id_fabricante']; ?>" <?php if ($vehiculo['id_fabricante'] == $row2[1] ) echo 'selected="selected"'?> ><?php echo $row2[1];?></option>    
                        <?php endwhile;?>          
                    </select>
                        

                    <?php if (!empty($errors['id_fabricante'])) { ?>
                        <span class="text-danger"><?php echo $errors['id_fabricante']; ?></span>
                    <?php } ?>
                </div>
                <div class="form-group col-lg-4">
                    <label>Modelo</label>
                    
                    <input type="text" name="id_modelo" class="form-control" value="<?php echo set_value('id_modelo', $vehiculo['id_modelo']); ?>" placeholder="Modelo" required>
                    <!-- <select name="id_modelo" class="form-control">
                        <?php while($row2 = mysqli_fetch_array($result2)):;?>
                            <option <?php if ($vehiculo['id_modelo'] == $row2[2] ) echo 'selected="selected"'?> ><?php echo $row2[2];?></option>    
                        <?php endwhile;?>          
                    </select> -->

                    <?php if (!empty($errors['id_modelo'])) { ?>
                        <span class="text-danger"><?php echo $errors['id_modelo']; ?></span>
                    <?php } ?>
                </div>
                <div class="form-group col-lg-4">
                    <label>Color</label>
                    <input type="text" name="color" class="form-control" value="<?php echo set_value('color', $vehiculo['color']); ?>" placeholder="Color" required>
                    <?php if (!empty($errors['color'])) { ?>
                        <span class="text-danger"><?php echo $errors['color']; ?></span>
                    <?php } ?>
                </div>
                <div class="form-group col-lg-4">
                    <label>Categoría</label>
                    <select name="categoria" class="form-control" id="categoria[categoria]">
                        <option value="Sedán" <?php if ($vehiculo['categoria'] == 'Sedán' ) echo 'selected="selected"'?>>Sedán</option>
                        <option value="Coupé" <?php if ($vehiculo['categoria'] == 'Coupé' ) echo 'selected="selected"'?>>Coupé</option>
                        <option value="Minivan" <?php if ($vehiculo['categoria'] == 'Minivan' ) echo 'selected="selected"'?>>Minivan</option>
                        <option value="Hatchback" <?php if ($vehiculo['categoria'] == 'Hatchback' ) echo 'selected="selected"'?>>Hatchback</option>
                        <option value="SUV" <?php if ($vehiculo['categoria'] == 'SUV' ) echo 'selected="selected"'?>>SUV</option>
                        <option value="Pick-Up" <?php if ($vehiculo['categoria'] == 'Pick-Up' ) echo 'selected="selected"'?>>Pick-Up</option>
                        <option value="Crossover" <?php if ($vehiculo['categoria'] == 'Crossover' ) echo 'selected="selected"'?>>Crossover</option>
                        <option value="Todoterreno" <?php if ($vehiculo['categoria'] == 'Todoterreno' ) echo 'selected="selected"'?>>Todoterreno</option>
                    </select>
                    <?php if (!empty($errors['categoria'])) { ?>
                        <span class="text-danger"><?php echo $errors['categoria']; ?></span>
                    <?php } ?>
                </div>
                <div class="form-group col-lg-4">
                    <label>Transmisión</label>
                    <select name="transmision" class="form-control" id="transmision[transmision]">
                        <option value="Automática"  <?php if ($vehiculo['transmision'] == 'Automática' ) echo 'selected="selected"'?> >Automática</option>
                        <option value="Manual" <?php if ($vehiculo['transmision'] == 'Manual' ) echo 'selected="selected"'?>>Manual</option>
                    </select>
                    <?php if (!empty($errors['transmision'])) { ?>
                        <span class="text-danger"><?php echo $errors['transmision']; ?></span>
                    <?php } ?>

                </div>
                <div class="form-group col-lg-4">
                    <label>Motor</label>
                    <select name="motor" class="form-control" id="motor[motor]">
                        <option value="Gasolina" <?php if ($vehiculo['motor'] == 'Gasolina' ) echo 'selected="selected"'?>>Gasolina</option>
                        <option value="Diesel" <?php if ($vehiculo['motor'] == 'Diesel' ) echo 'selected="selected"'?>>Diesel</option>
                    </select>
                    <?php if (!empty($errors['motor'])) { ?>
                        <span class="text-danger"><?php echo $errors['motor']; ?></span>
                    <?php } ?>
                </div>
                <div class="form-group col-lg-4">
                    <label>Año</label>
                    <input type="text" name="año" class="form-control" value="<?php echo set_value('año', $vehiculo['año']); ?>" placeholder="Año">
                    <?php if (!empty($errors['año'])) { ?>
                        <span class="text-danger"><?php echo $errors['año']; ?></span>
                    <?php } ?>
                </div>

                <div class="form-group col-lg-4">
                    <label>Kilometraje</label>
                    <input type="text" name="kilometraje" class="form-control" value="<?php echo set_value('kilometraje', $vehiculo['kilometraje']); ?>" placeholder="Kilometraje">
                    <?php if (!empty($errors['kilometraje'])) { ?>
                        <span class="text-danger"><?php echo $errors['kilometraje']; ?></span>
                    <?php } ?>
                </div>
                <div class="form-group col-lg-4">
                    <label>Nº de Puertas</label>
                    <input type="text" name="numero_puertas" class="form-control" value="<?php echo set_value('numero_puertas', $vehiculo['numero_puertas']); ?>" placeholder="Nº de Puertas">
                    <?php if (!empty($errors['numero_puertas'])) { ?>
                        <span class="text-danger"><?php echo $errors['numero_puertas']; ?></span>
                    <?php } ?>
                </div>
                <div class="form-group col-lg-4">
                    <label>Precio</label>
                    <input type="text" name="precio" class="form-control" value="<?php echo set_value('precio', $vehiculo['precio']); ?>" placeholder="Precio">
                    <?php if (!empty($errors['precio'])) { ?>
                        <span class="text-danger"><?php echo $errors['precio']; ?></span>
                    <?php } ?>
                </div>
            </div>
            <div class="text-end">
                <a href="<?php echo base_url('vehiculos'); ?>" class="btn btn-danger">Cancelar</a>
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection('content'); ?>