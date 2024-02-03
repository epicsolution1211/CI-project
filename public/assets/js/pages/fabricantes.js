let tblFabricantes;
let optFabricantes;

document.addEventListener('DOMContentLoaded', function(){

    tblFabricantes = $('#tblFabricantes').DataTable( {
        ajax: {
            url: base_url + 'fabricantes/list',
            dataSrc: ''
        },
        columns: [
            {
                data: null,
                render: function (data, type) {
                    if (type === 'display') { 
                        return `<a class="btn btn-primary" href="${ base_url + 'fabricantes/' + data.id + '/edit' }"><i class="fas fa-edit"></i></a>
                        <form action="${ base_url + 'fabricantes/' + data.id }" method="post" class="d-inline eliminar">
                            <input type="hidden" name="${csrf_token.getAttribute('content')}" value="${csrf_hash.getAttribute('content')}" />    
                            <input type="hidden" name="_method" value="DELETE">
                            <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i></button>
                        </form>`;
                    }
                    return data;
                },
            },
            { data: 'id' },
            { data: 'nombre_fabricante' }
            // {
            //     data: null,
            //     render: function (data, type) {
            //         if (type === 'display') { 
            //             return `<span class="badge bg-success">Disponible</span>`;
            //         }
            //         return data;
            //     },
            // },
        ],
        responsive: true,
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json',
        },
        dom,
        buttons,
        order: [[1, 'desc']]
    } );

    tblFabricantes.on('draw', function () {
        let lista = document.querySelectorAll('.eliminar');
        for (let i = 0; i < lista.length; i++) {
            lista[i].addEventListener('submit', function(e){
                e.preventDefault();
                eliminarRegistro(this);
            });          
        }
    });
})

function eliminarRegistro(form){
    Swal.fire({
        title: 'Mensaje?',
        text: "Esta seguro de eliminar!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, Eliminar!'
      }).then((result) => {
        if (result.isConfirmed) {
          form.submit();
        }
      })
}

function model_filter(str) {
  if(window.XMLHttpRequest) {
    xmlhttp = new XMLHttpRequest();
  } else {
    xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
  }

  xmlhttp.onreadystatechange = function() {

    if (this.readyState==4 && this.status==200) {
      document.getElementById('id_modelo').innerHTML = this.responseText;
    }

  }
    
  xmlhttp.open("GET", "helper.php?value="+str, true);
  xmlhttp.send();
   
}