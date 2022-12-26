const frm = document.querySelector('#frmProductos');
const barcode = document.querySelector('#barcode');
const nombre = document.querySelector('#nombre');
const precio = document.querySelector('#precio');
const stock = document.querySelector('#stock');
const id_product = document.querySelector('#id_product');
const btn_nuevo = document.querySelector('#btn-nuevo');
const btn_save = document.querySelector('#btn-save');
document.addEventListener('DOMContentLoaded', function () {
  $('#table_productos').DataTable({
    ajax: {
      url: ruta + 'controllers/productosController.php?option=listar',
      dataSrc: ''
    },
    columns: [
      { data: 'codproducto' },
      { data: 'codigo' },
      { data: 'descripcion' },
      { data: 'precio' },
      { data: 'existencia' },
      { data: 'accion' }
    ],
    language: {
      url: 'https://cdn.datatables.net/plug-ins/1.13.1/i18n/es-ES.json'
    },
    "order": [[0, 'desc']]
  });
  frm.onsubmit = function (e) {
    e.preventDefault();
    if (barcode.value == '' || nombre.value == ''
      || precio.value == '' || stock.value == '') {
      message('error', 'TODO LOS CAMPOS CON * SON REQUERIDOS')
    } else {
      const frmData = new FormData(frm);
      axios.post(ruta + 'controllers/productosController.php?option=save', frmData)
        .then(function (response) {
          const info = response.data;
          message(info.tipo, info.mensaje);
          if (info.tipo == 'success') {
            setTimeout(() => {
              window.location.reload();
            }, 1500);
          }
        })
        .catch(function (error) {
          console.log(error);
        });
    }
  }
  btn_nuevo.onclick = function () {
    frm.reset();
    id_product.value = '';
    btn_save.innerHTML = 'Guardar';
    barcode.focus();
  }
})

function deleteProducto(id) {
  Snackbar.show({
    text: 'Esta seguro de eliminar',
    width: '475px',
    actionText: 'Si eliminar',
    backgroundColor: '#FF0303',
    onActionClick: function (element) {
      axios.get(ruta + 'controllers/productosController.php?option=delete&id=' + id)
        .then(function (response) {
          const info = response.data;
          message(info.tipo, info.mensaje);
          if (info.tipo == 'success') {
            setTimeout(() => {
              window.location.reload();
            }, 1500);
          }
        })
        .catch(function (error) {
          console.log(error);
        });
    }
  });

}

function editProducto(id) {
  axios.get(ruta + 'controllers/productosController.php?option=edit&id=' + id)
    .then(function (response) {
      const info = response.data;
      barcode.value = info.codigo;
      nombre.value = info.descripcion;
      precio.value = info.precio;
      stock.value = info.existencia;
      id_product.value = info.codproducto;
      btn_save.innerHTML = 'Actualizar';
    })
    .catch(function (error) {
      console.log(error);
    });
}