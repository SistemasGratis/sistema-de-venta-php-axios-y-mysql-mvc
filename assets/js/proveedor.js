const frm = document.querySelector('#frmProveedor');
const telefono = document.querySelector('#telefono');
const nombre = document.querySelector('#nombre');
const direccion = document.querySelector('#direccion');
const id_proveedor = document.querySelector('#id_proveedor');
const btn_nuevo = document.querySelector('#btn-nuevo');
const btn_save = document.querySelector('#btn-save');
document.addEventListener('DOMContentLoaded', function () {
  $('#table_proveedores').DataTable({
    ajax: {
      url: ruta + 'controllers/proveedorController.php?option=listar',
      dataSrc: ''
    },
    columns: [
      { data: 'idproveedor' },
      { data: 'nombre' },
      { data: 'telefono' },
      { data: 'direccion' },
      { data: 'accion' }
    ],
    language: {
      url: 'https://cdn.datatables.net/plug-ins/1.13.1/i18n/es-ES.json'
    },
    "order": [[0, 'desc']]
  });
  frm.onsubmit = function (e) {
    e.preventDefault();
    if (telefono.value == '' || nombre.value == ''
      || direccion.value == '') {
      message('error', 'TODO LOS CAMPOS CON * SON REQUERIDOS')
    } else {
      const frmData = new FormData(frm);
      axios.post(ruta + 'controllers/proveedorController.php?option=save', frmData)
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
    id_proveedor.value = '';
    btn_save.innerHTML = 'Guardar';
    nombre.focus();
  }
})

function deleteProveedor(id) {
  Snackbar.show({
    text: 'Esta seguro de eliminar',
    width: '475px',
    actionText: 'Si eliminar',
    backgroundColor: '#FF0303',
    onActionClick: function (element) {
      axios.get(ruta + 'controllers/proveedorController.php?option=delete&id=' + id)
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

function editProveedor(id) {
  axios.get(ruta + 'controllers/proveedorController.php?option=edit&id=' + id)
    .then(function (response) {
      const info = response.data;
      telefono.value = info.telefono;
      nombre.value = info.nombre;
      direccion.value = info.direccion;
      id_proveedor.value = info.idproveedor;
      btn_save.innerHTML = 'Actualizar';
    })
    .catch(function (error) {
      console.log(error);
    });
}