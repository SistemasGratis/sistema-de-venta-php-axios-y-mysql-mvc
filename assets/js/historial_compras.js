let minDate, maxDate, table;
document.addEventListener('DOMContentLoaded', function () {
  table = $('#table_compras').DataTable({
    ajax: {
      url: ruta + 'controllers/comprasController.php?option=historial',
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

  minDate = new DateTime($('#desde'), {
    format: 'YYYY-MM-DD'
  });
  maxDate = new DateTime($('#hasta'), {
    format: 'YYYY-MM-DD'
  });

  // Refilter the table
  $('#desde, #hasta').on('change', function () {
    table.draw();
  });
})