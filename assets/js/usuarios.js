const frm = document.querySelector('#frmUser');
const permiso = document.querySelector('#frmPermiso');
const correo = document.querySelector('#correo');
const nombre = document.querySelector('#nombre');
const clave = document.querySelector('#clave');
const id_user = document.querySelector('#id_user');
const btn_nuevo = document.querySelector('#btn-nuevo');
const btn_save = document.querySelector('#btn-save');
document.addEventListener('DOMContentLoaded', function () {
  $('#table_users').DataTable({
    ajax: {
      url: ruta + 'controllers/usuariosController.php?option=listar',
      dataSrc: ''
    },
    columns: [
      { data: 'idusuario' },
      { data: 'nombre' },
      { data: 'correo' },
      { data: 'accion' }
    ],
    language: {
      url: 'https://cdn.datatables.net/plug-ins/1.13.1/i18n/es-ES.json'
    },
    "order": [[0, 'desc']]
  });
  frm.onsubmit = function (e) {
    e.preventDefault();
    if (correo.value == '' || nombre.value == ''
      || clave.value == '') {
      message('error', 'TODO LOS CAMPOS CON * SON REQUERIDOS')
    } else {
      const frmData = new FormData(frm);
      axios.post(ruta + 'controllers/usuariosController.php?option=save', frmData)
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
    id_user.value = '';
    btn_save.innerHTML = 'Guardar';
    clave.removeAttribute('readonly');
    nombre.focus();
  }

  permiso.onsubmit = function (e) {
    e.preventDefault();
    const frmData = new FormData(this);
      axios.post(ruta + 'controllers/usuariosController.php?option=savePermiso', frmData)
        .then(function (response) {
          const info = response.data;
          message(info.tipo, info.mensaje);
        })
        .catch(function (error) {
          console.log(error);
        });
  }

})

function deleteUser(id) {
  Snackbar.show({
    text: 'Esta seguro de eliminar',
    width: '475px',
    actionText: 'Si eliminar',
    backgroundColor: '#FF0303',
    onActionClick: function (element) {
      axios.get(ruta + 'controllers/usuariosController.php?option=delete&id=' + id)
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

function editUser(id) {
  axios.get(ruta + 'controllers/usuariosController.php?option=edit&id=' + id)
    .then(function (response) {
      const info = response.data;
      correo.value = info.correo;
      nombre.value = info.nombre;
      clave.value = '*********************';
      clave.setAttribute('readonly', 'readonly');
      id_user.value = info.idusuario;
      btn_save.innerHTML = 'Actualizar';
    })
    .catch(function (error) {
      console.log(error);
    });
}

function permisos(id) {
  axios.get(ruta + 'controllers/usuariosController.php?option=permisos&id=' + id)
    .then(function (response) {
      const info = response.data;
      let html = '';
      info.permisos.forEach(permiso => {
        let accion = info.asig[permiso.id] ? 'checked' : '';
        html += `<div>
            <label class="mb-2">
                <input type="checkbox" name="permisos[]" value="${permiso.id}" ${accion}> ${permiso.nombre}
            </label>
            </div>`;
      });
      html += `<input name="id_usuario" type="hidden" value="${id}" />
      <button class="btn btn-outline-success float-right" type="submit">Guardar</button>`;
      permiso.innerHTML = html;
      $('#modalPermiso').modal('show');
    })
    .catch(function (error) {
      console.log(error);
    });
}