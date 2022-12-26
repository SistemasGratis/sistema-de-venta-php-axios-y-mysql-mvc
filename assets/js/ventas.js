let table_temp = document.querySelector('#table_temp tbody');

const nombre_cliente = document.querySelector('#nombre-cliente');
const dir_cliente = document.querySelector('#dir-cliente');
const id_cliente = document.querySelector('#id-cliente');

const btn_save = document.querySelector('#btn-guardar');
const metodo = document.querySelector('#metodo');

const seacrh = document.querySelector('#seacrh');

let table_clientes;

document.addEventListener('DOMContentLoaded', function () {
  $('#table_venta').DataTable({
    ajax: {
      url: ruta + 'controllers/ventasController.php?option=listar',
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

  table_clientes = $('#table_clientes').DataTable({
    ajax: {
      url: ruta + 'controllers/ventasController.php?option=listar-clientes',
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

  $('#table_clientes tbody').on('dblclick', 'tr', function () {
    let datos = table_clientes.row(this).data();
    id_cliente.value = datos.idcliente;
    nombre_cliente.value = datos.nombre;
    dir_cliente.value = datos.direccion;
    $('#modal-cliente').modal('hide');
  })

  btn_save.onclick = function () {
    axios.post(ruta + 'controllers/ventasController.php?option=saveventa', {
      idCliente: id_cliente.value,
      metodo: metodo.value,
    })
      .then(function (response) {
        const info = response.data;
        if (info.tipo == 'success') {
          window.location = ruta + 'plantilla.php?pagina=reporte&sale=' + info.sale;
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
        axios.get(ruta + 'controllers/ventasController.php?option=searchbarcode&barcode=' + e.target.value)
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
  axios.get(ruta + 'controllers/ventasController.php?option=addcart&id=' + codProducto)
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
  axios.get(ruta + 'controllers/ventasController.php?option=listarTemp')
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
  axios.post(ruta + 'controllers/ventasController.php?option=addcantidad', {
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
  axios.post(ruta + 'controllers/ventasController.php?option=addprecio', {
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
  axios.get(ruta + 'controllers/ventasController.php?option=delete&id=' + idTemp)
    .then(function (response) {
      const info = response.data;
      message(info.tipo, info.mensaje);
      temp();
    })
    .catch(function (error) {
      console.log(error);
    });
}