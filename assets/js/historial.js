document.addEventListener('DOMContentLoaded', function () {
    $('#table_ventas').DataTable({
        ajax: {
          url: ruta + 'controllers/ventasController.php?option=historial',
          dataSrc: ''
        },
        columns: [
          { data: 'id' },
          { data: 'nombre' },
          { data: 'producto' },
          { data: 'total' },
          { data: 'fecha' },
          { data: 'accion' }
        ],
        language: {
          url: 'https://cdn.datatables.net/plug-ins/1.13.1/i18n/es-ES.json'
        }
      });
})