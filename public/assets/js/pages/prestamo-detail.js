let formEstado = document.querySelectorAll(".formEstado");
const btnCorreo = document.querySelector("#btnCorreo");
const btnWhatsApp = document.querySelector("#btnWhatsApp");
const btnWhatsappWeb = document.querySelector("#btnWhatsappWeb");
const mensaje_whatsapp = document.querySelector("#mensaje-whatsapp");
const num_whatsapp = document.querySelector("#num-whatsapp");
const btnAdelanto = document.querySelector("#btnAdelanto");

const myModal = new bootstrap.Modal(document.getElementById('modalMensaje'));
const modalWhatsApp = new bootstrap.Modal(document.getElementById('modalWhatsApp'));
const custom_payment = new bootstrap.Modal(document.getElementById('custompayment'))
document.addEventListener("DOMContentLoaded", function () {
  for (let i = 0; i < formEstado.length; i++) {
    formEstado[i].addEventListener("submit", function (e) {
      e.preventDefault();
      cambiarEstado(this);
    });
  }

//custompayment modal
  btnAdelanto.addEventListener('click', function(){
    custom_payment.show();
  })

  //modal correo
  btnCorreo.addEventListener('click', function(){
    myModal.show();
  })

  //modal WhatsApp
  btnWhatsApp.addEventListener('click', function(){
    modalWhatsApp.show();
  })

  btnWhatsappWeb.addEventListener('click', function(){
    if (mensaje_whatsapp.value == '' || num_whatsapp.value == '') {
      Swal.fire({
        position: 'top-end',
        icon: 'warning',
        title: 'EL MENSAJE Y WHATSAPP ES REQUERIDO',
        showConfirmButton: false,
        timer: 1500
      })
    } else {
      window.open(`https://api.whatsapp.com/send?phone=${num_whatsapp.value}&text=${mensaje_whatsapp.value}`, '_blank');
    }
  })

});

function cambiarEstado(form) {
  Swal.fire({
    title: "Mensaje?",
    text: "Esta seguro cambiar el estado!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si!",
  }).then((result) => {
    if (result.isConfirmed) {
      form.submit();
    }
  });
}
$(document).ready(function(){
  $("#out").click(function(){
    var outValue = Number.parseFloat($("#out").val()); // Get the value of #out
    $("#customamount").val(outValue); // Set the value of #customamount to the value of #out
    console.log($("#customize").val())
  });
  $("#next").click(function(){
    var outValue = Number.parseFloat($("#next").val()); // Get the value of #out
    $("#customamount").val(outValue); // Set the value of #customamount to the value of #out
    console.log($("#customize").val())
  });
  $("#customize").click(function(){
    $("#customamount").prop('disabled',false);
    // $("#customamount").val(""); // Set the value of #customamount to the value of #out
    console.log($("#customamount").val());

  });
  $("#next_with_interest").click(function(){
    var outValue = Number.parseFloat($("#next_with_interest").val()); // Get the value of #out
    $("#customamount").val(outValue);
    console.log($("#customize").val())
  });
  $("#customamount").keyup(function(){
    $("#customize").val($("#customamount").val());
    console.log($("#customize").val());
  });
});



document.getElementById('formcustompay').addEventListener('submit', function(event) {
  if($("#customamount").val()>Number.parseFloat($("#total_amount").val())){
    
    event.preventDefault();
    console.log((Number.parseFloat($("#total_amount").val())))
    Swal.fire({
      title: "Warning?",
      text: "el importe debe ser menor que la deuda actua",
      icon: "warning",
      confirmButtonColor: "#3085d6",
      confirmButtonText: "OK!",
    })
  }else if(($("#customamount").val()<=0)){
    event.preventDefault();
    Swal.fire({
      title: "Warning?",
      text: "Ingresa un pago personalizado superior a 0",
      icon: "warning",
      confirmButtonColor: "#3085d6",
      confirmButtonText: "OK!",
    })
  }
  else{
    // $("#formcustompay").submit();
    console.lof($("#customamount").val());
  }
});

