let minDate, maxDate, table;
document.addEventListener('DOMContentLoaded', function () {
  table =  $('#table_ventas').DataTable({
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

// Custom filtering function which will search data in column four between two values
$.fn.dataTable.ext.search.push(
  function (settings, data, dataIndex) {
    var min = minDate.val();
    var max = maxDate.val();
    var date = new Date(data[4]);

    if (
      (min === null && max === null) ||
      (min === null && date <= max) ||
      (min <= date && max === null) ||
      (min <= date && date <= max)
    ) {
      return true;
    }
    return false;
  }
);