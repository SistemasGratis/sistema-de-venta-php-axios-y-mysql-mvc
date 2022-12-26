document.addEventListener('DOMContentLoaded', function () {
    ventas()
    clientes()
    totales()
})
function totales() {
    axios.get(ruta + 'controllers/adminController.php?option=totales')
        .then(function (response) {
            const info = response.data;
            console.log(info);
            document.querySelector('#totalUsuarios').textContent = info.usuario.total;
            document.querySelector('#totalClientes').textContent = info.cliente.total;
            document.querySelector('#totalProductos').textContent = info.producto.total;
            document.querySelector('#totalVentas').textContent = info.venta.total;
        })
        .catch(function (error) {
            console.log(error);
        });
}
function ventas() {
    const dias = [        
        'lunes',
        'martes',
        'miércoles',
        'jueves',
        'viernes',
        'sábado',
        'domingo'
    ];

    const ctx = document.getElementById('ventas');

    axios.get(ruta + 'controllers/adminController.php?option=ventasSemana')
        .then(function (response) {
            const info = response.data;
            let fecha = [];
            let total = [];
            for (let i = 0; i < info.length; i++) {                
                total.push(info[i]['total']);
                const numeroDia = new Date(info[i]['fecha']).getDay();
                const nombreDia = dias[numeroDia];
                fecha.push(nombreDia + ' - ' + info[i]['fecha']);
            }
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: fecha,
                    datasets: [{
                        label: 'Ventas',
                        data: total,
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        })
        .catch(function (error) {
            console.log(error);
        });
}
function clientes() {
    const ctx = document.getElementById('topClientes');

    axios.get(ruta + 'controllers/adminController.php?option=topClientes')
        .then(function (response) {
            const info = response.data;
            let nombre = [];
            let total = [];
            for (let i = 0; i < info.length; i++) {
                nombre.push(info[i]['nombre']);
                total.push(info[i]['total']);
            }
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: nombre,
                    datasets: [{
                        label: 'Cliente',
                        data: total,
                        borderWidth: 1,
                        backgroundColor: '#FFB1C1',
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        })
        .catch(function (error) {
            console.log(error);
        });
}