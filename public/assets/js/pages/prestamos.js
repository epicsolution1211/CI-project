const id_cliente = document.querySelector('#id_cliente');
const id_vehiculo = document.querySelector('#id_vehiculo');
const id_valor_vehiculo = document.querySelector('#id_valor_vehiculo');
const valor_vehiculo = document.querySelector('#valor_vehiculo');
const importe_credito = document.querySelector('#importe_credito');
const tasa_mensual = document.querySelector('#tasa_mensual');
const tasa_interes = document.querySelector('#tasa_interes');
const cuotas = document.querySelector('#cuotas');
const enganche = document.querySelector('#enganche');
const importe_cuota = document.querySelector('#importe_cuota');
const total_pagar = document.querySelector('#total_pagar');
const interes_generado = document.querySelector('#interes_generado');
const errorCliente = document.querySelector('#errorCliente');
const errorVehiculo = document.querySelector('#errorVehiculo');
const errorValorVehiculo = document.querySelector('#errorValorVehiculo');


//calcular interes total
function calcularInteresTotal() {
  const tasaM = tasa_mensual.value;
  const numCuotas = cuotas.value;
  const calculo = tasaM * numCuotas;

  tasa_interes.value = calculo.toFixed(1);
}

function autoVin() {

  const currentVin = document.querySelector('#vehiculo').value;
  valor_vehiculo.value = currentVin.substring(0,16);

}

//Prellenar VIN
function substractValue() {
  if (valor_vehiculo.value !='') {
    
    let str = valor_vehiculo.value;
    const n = 27;

    valor_vehiculo.value = str.slice(n);

    const importeV = valor_vehiculo.value * 0.5;
    importe_credito.value = importeV;

    calcularEnganche()

  }

}

function calcularEnganche() {
  //Calcular Enganche
  let engancheTotal;
  let valorV = valor_vehiculo.value;
  let valorI = importe_credito.value;

  if (valorI == (valor_vehiculo.value * 0.5)) {
    engancheTotal = valorI;
    enganche.value =  engancheTotal;
  } else {
    if (valorV > 0) {
      engancheTotal = valorV - importe_credito.value;
      enganche.value = engancheTotal;
    } else {
        engancheTotal = 0;
        enganche.value =  engancheTotal;
    }
  }

}

document.addEventListener('DOMContentLoaded', function(){

    /* Cargar Cliente */
    $("#cliente").autocomplete({
        source: function( request, response ) {
          $.ajax( {
            url: base_url + 'prestamos/buscarCliente',
            dataType: "json",
            data: {
              term: request.term
            },
            success: function( data ) {
              response( data );
              if (data.length > 0) {
                errorCliente.textContent = '';
              } else {
                errorCliente.textContent = 'NO EXISTE EL CLIENTE';
              }
            }
          } );
        },
        minLength: 2,
        select: function( event, ui ) {
            id_cliente.value = ui.item.id;
            console.log( "Selected: " + ui.item.value + " aka " + ui.item.id );
        }
      } );
      
      
      /* Cargar Vehiculo */  
      $("#vehiculo").autocomplete({
        source: function( request, response ) {
          $.ajax( {
            url: base_url + 'prestamos/buscarVehiculo',
            dataType: "json",
            data: {
              term: request.term
            },
            success: function( data ) {
              response( data );
              if (data.length > 0) {
                errorVehiculo.textContent = '';
              } else {
                errorVehiculo.textContent = 'NO EXISTE EL VEHÍCULO';
              }
            }
          } );
        },
        minLength: 2,
        select: function( event, ui ) {
          id_vehiculo.value = ui.item.id;
            console.log( "Selected: " + ui.item.value + " aka " + ui.item.id );
        }
      } );

      /* Cargar Valor Vehículo */
      $("#valor_vehiculo").autocomplete({
        source: function( request, response ) {
          $.ajax( {
            url: base_url + 'prestamos/valorVehiculo',
            dataType: "json",
            data: {
              term: request.term
            },
            success: function( data ) {
              response( data );
              if (data.length > 0) {
                errorValorVehiculo.textContent = '';
              } else {
                errorValorVehiculo.textContent = 'EL VIN DEBE COINCIDIR';
              }
            }
          } );
        } //}, 
        // minLength: 2,
        // select: function( event, ui ) {
        //     id_cliente.value = ui.item.id;
        //     console.log( "Selected: " + ui.item.value + " aka " + ui.item.id );
        // }
      } );

      //calcular importe
      importe_credito.addEventListener('keyup', function(e){
        if (e.target.value != '') {
            //validacion de interes
            let interes = (tasa_interes.value == '' || tasa_interes.value < 1)
             ? 0 : tasa_interes.value;
            //validacion de cuotas
            let cuotas_total = (cuotas.value == '' || cuotas.value < 0) 
            ? 0 : cuotas.value;
            calcularTotal(e.target.value, cuotas_total, interes);
            // console.log('Importe:' + e.target.value);
        } else {
            limpiarCampos()
        }
      })

      // calcular cuotas
      cuotas.addEventListener('change', function(e){
        if (e.target.value != '') {
            //validacion de interes
            let interes = (tasa_interes.value == '' || tasa_interes.value < 1)
             ? 0 : tasa_interes.value;
            //validacion de importe
            let importe = (importe_credito.value == '' || importe_credito.value < 0) 
            ? 0 : importe_credito.value;
            calcularTotal(importe, e.target.value, interes);
            // console.log('Cuotas:' + e.target.value);
        } else {
            limpiarCampos()
        }
      })

      // calcular interes
      tasa_interes.addEventListener('keyup', function(e){
        if (e.target.value != '') {
            //validacion de cuotas
            let cuotas_total = (cuotas.value == '' || cuotas.value < 0) 
            ? 0 : cuotas.value;
            //validacion de importe
            let importe = (importe_credito.value == '' || importe_credito.value < 0) 
            ? 0 : importe_credito.value;
            calcularTotal(importe, cuotas_total, e.target.value);
            // console.log('Interés:' + e.target.value);
        } else {
            limpiarCampos();
        }
      })
})

function calcularTotal(importe, cuotas, interes) {
    
    let ganacia = parseFloat(importe) * (parseFloat(interes) / 100);
    // alert(ganacia);
    //calcular importe por cuotas
    let importeCuota = 0;
    if (cuotas > 0) {
        importeCuota = (parseFloat(importe) / parseInt(cuotas)) + (parseFloat(ganacia) / parseInt(cuotas));
    }
    //asignar el value en el input
    importe_cuota.value = importeCuota.toFixed(2);
    interes_generado.value = ganacia.toFixed(2);

    const totalPagar = parseFloat(importe_cuota.value) * parseInt(cuotas); // Multiplica el número de cuotas por el monto mensual.
    total_pagar.value = Intl.NumberFormat({ style: 'currency'}).format(totalPagar.toFixed(2));
}

function limpiarCampos() {
    importe_cuota.value = '0.00';
    total_pagar.value = '0.00';
    interes_generado.value = '0.00';
}


