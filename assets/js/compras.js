let table_temp = document.querySelector('#table_temp tbody');

const nombre_proveedor = document.querySelector('#nombre-proveedor');
const dir_proveedor = document.querySelector('#dir-proveedor');
const id_proveedor = document.querySelector('#id-proveedor');

const btn_save = document.querySelector('#btn-guardar');

const seacrh = document.querySelector('#seacrh');

let table_proveedores;

document.addEventListener('DOMContentLoaded', function () {
  $('#table_compra').DataTable({
    ajax: {
      url: ruta + 'controllers/comprasController.php?option=listar',
      dataSrc: ''
    },
    columns: [
      { data: 'codigo' },
      { data: 'descripcion' },
      { data: 'cantidad' },
      { data: 'precio' },
      { data: 'addcart' }
    ],
    language: {
      url: 'https://cdn.datatables.net/plug-ins/1.13.1/i18n/es-ES.json'
    }
  });

  temp()

  table_proveedores = $('#table_proveedores').DataTable({
    ajax: {
      url: ruta + 'controllers/comprasController.php?option=listar-proveedores',
      dataSrc: ''
    },
    columns: [
      { data: 'nombre' },
      { data: 'telefono' },
      { data: 'direccion' }
    ],
    language: {
      url: 'https://cdn.datatables.net/plug-ins/1.13.1/i18n/es-ES.json'
    }
  });

  $('#table_proveedores tbody').on('dblclick', 'tr', function () {
    let datos = table_proveedores.row(this).data();
    id_proveedor.value = datos.idproveedor;
    nombre_proveedor.value = datos.nombre;
    dir_proveedor.value = datos.direccion;
    $('#modal-proveedor').modal('hide');
  })

  btn_save.onclick = function () {
    axios.post(ruta + 'controllers/comprasController.php?option=savecompra', {
      idProveedor: id_proveedor.value
    })
      .then(function (response) {
        const info = response.data;
        if (info.tipo == 'success') {
          window.location = ruta + 'plantilla.php?pagina=reporte_compra&shoping=' + info.shoping;
          temp();
        }
        message(info.tipo, info.mensaje);
      })
      .catch(function (error) {
        console.log(error);
      });
  }

  seacrh.onkeyup = function (e) {
    if (e.key === "Enter") {
      if (e.target.value == '') {
        message('error', 'INGRESE BARCODE');
      } else {
        axios.get(ruta + 'controllers/comprasController.php?option=searchbarcode&barcode=' + e.target.value)
          .then(function (response) {
            const info = response.data;
            seacrh.value = '';
            message(info.tipo, info.mensaje);
            temp();
          })
          .catch(function (error) {
            console.log(error);
          });
      }
    }
  }

})


function addCart(codProducto) {
  axios.get(ruta + 'controllers/comprasController.php?option=addcart&id=' + codProducto)
    .then(function (response) {
      const info = response.data;
      message(info.tipo, info.mensaje);
      temp();
    })
    .catch(function (error) {
      console.log(error);
    });
}


function temp() {
  axios.get(ruta + 'controllers/comprasController.php?option=listarTemp')
    .then(function (response) {
      const info = response.data;
      let tempProductos = '';
      info.forEach(pro => {
        tempProductos += `<tr>
                    <td>${pro.descripcion}</td>
                    <td><input class="form-control" type="number" value="${pro.precio}" onchange="addPrecio(event, ${pro.id})" /></td>
                    <td><input class="form-control" type="number" value="${pro.cantidad}" onchange="addCantidad(event, ${pro.id})" /></td>
                    <td>${parseFloat(pro.precio) * parseInt(pro.cantidad)}</td>
                    <td><i class="fas fa-eraser text-danger" onclick="deleteproducto(${pro.id})"></i></td>
                </tr>`;
      });
      table_temp.innerHTML = tempProductos;
    })
    .catch(function (error) {
      console.log(error);
    });
}

function addCantidad(e, idTemp) {
  axios.post(ruta + 'controllers/comprasController.php?option=addcantidad', {
    id: idTemp,
    cantidad: e.target.value
  })
    .then(function (response) {
      const info = response.data;
      if (info.tipo == 'error') {
        message(info.tipo, info.mensaje);
        return;
      }
      temp();
    })
    .catch(function (error) {
      console.log(error);
    });
}

function addPrecio(e, idTemp) {
  axios.post(ruta + 'controllers/comprasController.php?option=addprecio', {
    id: idTemp,
    precio: e.target.value
  })
    .then(function (response) {
      const info = response.data;
      if (info.tipo == 'error') {
        message(info.tipo, info.mensaje);
        return;
      }
      temp();
    })
    .catch(function (error) {
      console.log(error);
    });
}

function deleteproducto(idTemp) {
  axios.get(ruta + 'controllers/comprasController.php?option=delete&id=' + idTemp)
    .then(function (response) {
      const info = response.data;
      message(info.tipo, info.mensaje);
      temp();
    })
    .catch(function (error) {
      console.log(error);
    });
}