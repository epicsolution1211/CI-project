<?php include('handler.php') ?>

<?= $this->extend('layouts/main'); ?>
<?= $this->section('title'); ?>
Nuevo Vehículo
<?= $this->endSection('title'); ?>

<?= $this->section('content'); ?>
<div class="card">
    <div class="card-header">
        <h4>Nuevo Vehículo</h4>
    </div>
    <div class="card-body">
        <form action="<?php echo base_url('vehiculos'); ?>" method="post" autocomplete="off">
            <?php echo csrf_field(); ?>
            <input type="hidden" name="id_vehiculo" value="0">
            <div class="row">
                <div class="form-group col-lg-4">
                    <label>VIN</label>
                    <input type="text" maxlength="17" name="vin" class="form-control" value="<?php echo set_value('vin'); ?>" placeholder="VIN">
                    <?php if (!empty($errors['vin'])) { ?>
                        <span class="text-danger"><?php echo $errors['vin']; ?></span>
                    <?php } ?>
                </div>
                <div class="form-group col-lg-4">
                    <label>Fabricante</label>
               
                    <select name="id_fabricante" id="id_fabricante[id_fabricante]" class="form-control" onchange="filterModel(this.value);">
                        <?php while($row2 = mysqli_fetch_array($result1)):;?>
                        <option value="<?php echo $row2[1];?>"><?php echo $row2[1];?></option>    
                        <?php endwhile;?>    
                    </select>
                    <?php if (!empty($errors['id_fabricante'])) { ?>
                        <span class="text-danger"><?php echo $errors['id_fabricante']; ?></span>
                    <?php } ?>
                </div>
                <div class="form-group col-lg-4">
                    <label>Modelo</label>

                    <input type="text" name="id_modelo" class="form-control" value="<?php echo set_value('id_modelo'); ?>" placeholder="Modelo" required>
            
                    <!-- <select name="id_modelo" id="optModels" class="form-control">

                        <?php while($row1 = mysqli_fetch_array($result)):;?>
                        <option value="<?php echo $row1[1];?>"><?php echo $row1[1];?></option>    
                        <?php endwhile;?>   

                    </select> -->

                    <!-- <select name="id_modelo" id="id_modelo[id_modelo]" class="form-control" >
                        <?php while($row2 = mysqli_fetch_array($result2)):;?>
                        <option><?php echo $row2[2];?></option>    
                        <?php endwhile;?>    
                    </select> -->

                    <?php if (!empty($errors['id_modelo'])) { ?>
                        <span class="text-danger"><?php echo $errors['id_modelo']; ?></span>
                    <?php } ?>
                </div>
                <div class="form-group col-lg-4">
                    <label>Color</label>
                    <input type="text" name="color" class="form-control" value="<?php echo set_value('color'); ?>" placeholder="Color" required>
                    <?php if (!empty($errors['color'])) { ?>
                        <span class="text-danger"><?php echo $errors['color']; ?></span>
                    <?php } ?>
                </div>
                <div class="form-group col-lg-4">
                    <label>Categoría</label>
                    <select name="categoria" class="form-control" id="categoria[categoria]">
                        <option value="Sedán">Sedán</option>
                        <option value="Coupé">Coupé</option>
                        <option value="Minivan">Minivan</option>
                        <option value="Hatchback">Hatchback</option>
                        <option value="SUV">SUV</option>
                        <option value="Pick-Up">Pick-Up</option>
                        <option value="Crossover">Crossover</option>
                        <option value="Todoterreno">Todoterreno</option>
                    </select>
                    <?php if (!empty($errors['categoria'])) { ?>
                        <span class="text-danger"><?php echo $errors['categoria']; ?></span>
                    <?php } ?>
                </div>
                <div class="form-group col-lg-4">
                    <label>Transmisión</label>
                    <select name="transmision" class="form-control" id="transmision[transmision]">
                        <option value="Automática">Automática</option>
                        <option value="Manual">Manual</option>
                    </select>
                    <?php if (!empty($errors['transmision'])) { ?>
                        <span class="text-danger"><?php echo $errors['transmision']; ?></span>
                    <?php } ?>

                </div>
                <div class="form-group col-lg-4">
                    <label>Motor</label>
                    <!-- <input type="text" name="motor" class="form-control" value="<?php echo set_value('motor'); ?>" placeholder="Motor"> -->
                    <select name="motor" class="form-control" id="motor[motor]">
                        <option value="Gasolina">Gasolina</option>
                        <option value="Diesel">Diesel</option>
                    </select>
                    <?php if (!empty($errors['motor'])) { ?>
                        <span class="text-danger"><?php echo $errors['motor']; ?></span>
                    <?php } ?>
                </div>
                <div class="form-group col-lg-4">
                    <label>Año</label>
                    <input type="text" name="año" class="form-control" value="<?php echo set_value('año'); ?>" placeholder="Año">
                    <?php if (!empty($errors['año'])) { ?>
                        <span class="text-danger"><?php echo $errors['año']; ?></span>
                    <?php } ?>
                </div>

                <div class="form-group col-lg-4">
                    <label>Kilometraje</label>
                    <input type="text" name="kilometraje" class="form-control" value="<?php echo set_value('kilometraje'); ?>" placeholder="Kilometraje">
                    <?php if (!empty($errors['kilometraje'])) { ?>
                        <span class="text-danger"><?php echo $errors['kilometraje']; ?></span>
                    <?php } ?>
                </div>
                <div class="form-group col-lg-4">
                    <label>Nº de Puertas</label>
                    <input type="text" name="numero_puertas" class="form-control" value="<?php echo set_value('numero_puertas'); ?>" placeholder="Nº de Puertas">
                    <?php if (!empty($errors['numero_puertas'])) { ?>
                        <span class="text-danger"><?php echo $errors['numero_puertas']; ?></span>
                    <?php } ?>
                </div>
                <div class="form-group col-lg-4">
                    <label>Precio</label>
                    <input type="text" name="precio" class="form-control" value="<?php echo set_value('precio'); ?>" placeholder="Precio">
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

<?= $this->section('js'); ?>
<script src="<?php echo base_url('assets/js/pages/vehiculos.js'); ?>"></script>
<script>
    function filterModel(str) {

        if (window.XMLHttpRequest) {
            xmlhttp = new XMLHttpRequest();
        } else{
            xmlhttp= new ActiveXObject("Microsoft.XMLHTTP");
        }

        xmlhttp.onreadystatechange= function(){

            if (this.readyState==4 && this.status==200) {
                document.getElementById('optModels').innerHTML = this.responseText;
            }

        }

        xmlhttp.open("GET","handler.php?value="+str, true);
        xmlhttp.send();

        let queryStrings = new URLSearchParams("GET","handler.php?value="+str, true);
        let parametrosGet = Object.fromEntries(queryStrings.entries());
        console.log(parametrosGet);
    }
</script>
<?= $this->endSection('js'); ?>