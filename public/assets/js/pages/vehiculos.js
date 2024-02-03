let tblVehiculos;
document.addEventListener("DOMContentLoaded", function () {
  tblVehiculos = $("#tblVehiculos").DataTable({
    ajax: {
      url: base_url + "vehiculos/list",
      dataSrc: "",
    },
    columns: [
      {
        data: null,
        render: function (data, type) {
          if (type === "display") {
            return `<a class="btn btn-primary" href="${
              base_url + "vehiculos/" + data.id + "/edit"
            }"><i class="fas fa-edit"></i></a>
                        <form action="${
                          base_url + "vehiculos/" + data.id
                        }" method="post" class="d-inline eliminar">
                            <input type="hidden" name="${csrf_token.getAttribute(
                              "content"
                            )}" value="${csrf_hash.getAttribute(
              "content"
            )}" />    
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i></button>
                        </form>`;
          }
          return data;
        },
      },
      { data: "id" },
      { data: "vin" },
      { data: "id_fabricante" },
      { data: "id_modelo" },
      { data: "color" },
      { data: "categoria" },
      { data: "transmision" },
      { data: "motor" },
      { data: "año" },
      { data: "kilometraje" },
      { data: "numero_puertas" },
      { data: "precio",  /* Mostrar cantidades en pesos mexicanos */
        render: function (data, type) {
        if (type === "display") {
          return Intl.NumberFormat({ style: 'currency'}).format(data);
        }

        return data;
      } },
      {
        data: null,
        render: function (data, type) {
          if (type === "display") {
            return `<span class="badge bg-success">Disponible</span>`;
          }
          return data;
        },
      },
    ],
    responsive: true,
    language: {
      url: "//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json",
    },
    dom,
    buttons,
    order: [[1, "desc"]],
  });

  tblVehiculos.on("draw", function () {
    let lista = document.querySelectorAll(".eliminar");
    for (let i = 0; i < lista.length; i++) {
      lista[i].addEventListener("submit", function (e) {
        e.preventDefault();
        eliminarRegistro(this);
      });
    }
  });
});

function eliminarRegistro(form) {
  Swal.fire({
    title: "Mensaje?",
    text: "Esta seguro de eliminar!",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    confirmButtonText: "Si, Eliminar!",
  }).then((result) => {
    if (result.isConfirmed) {
      form.submit();
    }
  });

  let x = document.querySelectorAll(".dtr-hidden");
  for (let i = 0, len = x.length; i < len; i++) {
      let num = Number(x[i].innerHTML)
          .toLocaleString('en');
      x[i].innerHTML = num;
      x[i].classList.add("currSign");
  }
//   $.ajax({
//     type: "GET",
//     url: base_url + 'modelos/list', 
//     dataType: "json",
//     success: function(data){
//       $.each(data,function(key, registro) {
//         $("#optManufacturers").append('<option value='+registro.id+'>'+registro.nombre_fabricante+'</option>');
//       });        
//     },
//     error: function(data) {
//       alert('error');
//     }
//   });

}





