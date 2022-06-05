$(function () {
    $('.stats').counter();

    var becados = parseInt($("#txtBecados").val());
    var noBecados = (parseInt($("#txtAlumnos").val()) - becados);
    var uAdmin = parseInt($("#txtUAdmin").val());
    var uAlumn = parseInt($("#txtUAlum").val());

    const ctx = document.getElementById('chartBecados').getContext('2d');
    const myChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: ['Alumnos Becados', 'Alumnos No becados'],
            datasets: [{
                label: '# of Votes',
                data: [becados, noBecados],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                /*title: {
                    display: true,
                    text: 'Chart.js Pie Chart'
                }*/
            }
        }
    });

    const ctx2 = document.getElementById('chartUsuarios').getContext('2d');
    const myChart2 = new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: ['Administradores', 'Alumnos'],
            datasets: [{
                label: ['Administradores', 'Alumnos'],
                data: [uAdmin, uAlumn],
                backgroundColor: [
                    '#9D71E0',
                    '#DBE071'
                ],
                borderRadius: 5,
                borderColor: [
                    '#9D71E0',
                    '#DBE071'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                /*title: {
                    display: true,
                    text: 'Chart.js Bar Chart'
                }*/
            }
        }
    });
});