<head>
    <link rel="stylesheet" href="<?= PROJECT_ROOT ?>/public/css/panel/components/dashboard.css">
</head>
<div class="container">
    <div class="content-box">
        <div class="title">
            <h1 class="title-text">Dashboard</h1>
            <div class="title-date">
                <p class="title-date-text">Minggu, 28 Januari 2023 · 19:14 PM</p>
            </div>
        </div>
        <span class="divider"></span>
        <div class="introduction">
            <div class="card card-introduction">
                <div class="card-content">
                    <div class="card-body">
                        <div class="card-title">
                            <p>Welcome back, Kreshna</p>
                        </div>
                        <div class="card-description">
                            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Reiciendis totam autem amet necessitatibus assumenda voluptatum omnis nostrum qui</p>
                        </div>
                        <div class="card-button">
                            <a href="#" class="card-button-link">View Report</a>
                        </div>
                    </div>
                    <div class="card-chart">
                        <canvas class="report-income-statistics">

                        </canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="first-column">
            <div class="card card-1">
                <div class="card-content">
                    <div class="card-body">
                        <div class="card-title">
                            <p>Today's Income</p>
                        </div>
                        <div class="card-description">
                            <canvas class="income"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <div class="card-title">
                            <p>Outlet Terlaris</p>
                        </div>
                        <div class="card-description">
                            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quasi atque consequuntur alias dolores quae iure sit consequatur, enim ab sapiente asperiores, distinctio optio culpa illum, in ullam totam porro eos.</p>
                        </div>
                        <div class="card-button">
                            <a href="#" class="card-button-link">Lihat Outlet</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-content">
                    <div class="card-body">
                        <div class="card-title">
                            <p>Aktivitas Terkini</p>
                        </div>
                        <div class="card-description">
                            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Praesentium tempore ab saepe corrupti consectetur libero modi voluptatibus ad ratione quae repudiandae alias qui itaque, ipsam fugiat sequi. Eum, sequi deleniti!</p>
                        </div>
                        <div class="card-button">
                            <a href="#" class="card-button-link">Lihat Aktivitas</a>
                        </div>
                    </div>
                </div>
            </div>


        </div>
        <div class="second-column">

        </div>
        <div class="third-column">

        </div>
    </div>

    <!-- Random Shit -->
    <script>
        var ctx = document.querySelector('canvas.income');
        var chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei'],
                datasets: [{
                    label: 'Income',
                    data: [100, 200, 150, 300, 250],
                    backgroundColor: '#4895ef',
                    borderColor: '#4895ef',
                    borderWidth: 2
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: false,
                            fontColor: '#fff',
                            fontSize: 10,
                            padding: 10,
                            fontFamily: 'Poppins'
                        }
                    }],
                    xAxes: [{
                        ticks: {
                            beginAtZero: false,
                            fontColor: '#fff',
                            fontSize: 10,
                            padding: 10,
                            fontFamily: 'Poppins'
                        }
                    }]
                },
                legend: {
                    labels: {
                        fontColor: '#fff',
                        fontSize: 10
                    }
                }
            }
        });
    </script>

    <!-- Welcome column chart -->
    <script>
        var ctx = document.querySelector('canvas.report-income-statistics');
        var chart = new Chart(ctx, {
            type: 'bar', // Change the type to 'bar'
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei'],
                datasets: [{
                    label: 'Income',
                    data: [100, 200, 150, 300, 250],
                    backgroundColor: '#4895ef', // Set the background color for the bars
                    borderWidth: 0, // Remove the border width
                }]
            },
            options: {
                scales: {
                    x: {
                        border: {
                            display: false
                        },
                        grid: {
                            display: false,
                            drawOnChartArea: false,
                            drawTicks: false,
                        },
                        ticks: {
                            display: false
                        }
                    },
                    y: {
                        border: {
                            display: false
                        },
                        grid: {
                            display: false,
                            drawOnChartArea: false,
                            drawTicks: false,
                        },
                        ticks: {
                            display: false
                        }
                    }
                }
            }
        });
    </script>