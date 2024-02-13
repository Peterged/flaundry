<?php

use App\Services\FlashMessage as fm;

?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="shortcut icon" href="<?= PROJECT_ROOT ?>/public/images/flaundry-logo-icon.png" type="image/png" />
    <link rel="stylesheet" href="<?= PROJECT_ROOT ?>/public/css/global.css">
    <link rel="stylesheet" href="<?= PROJECT_ROOT ?>/public/css/panel/sidebar.css">
    <link rel="stylesheet" href="<?= PROJECT_ROOT ?>/public/css/panel/navbar.css">
    <link rel="stylesheet" href="<?= PROJECT_ROOT ?>/public/css/panel/card.css">
    <link rel="stylesheet" href="<?= PROJECT_ROOT ?>/public/css/panel/table.css">
    <link rel="stylesheet" href="<?= PROJECT_ROOT ?>/public/css/services/flashMessage.css">
    <link rel="stylesheet" href="<?= PROJECT_ROOT ?>/public/css/panel/components/dashboard.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title>Outlet | FLaundry</title>
</head>
<?php includeFile("$base/panel/inc/sidebar.php") ?>
<?php includeFile("$base/panel/inc/navbar.php") ?>

<div class="container">
    <div class="content-box">
        <div class="title">
            <h1 class="title-text">Outlet</h1>
            <div class="title-date">
                <p class="title-date-text">Selasa, 30 Januari 2024 · 11:12 PM</p>
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
                        <div class="card-chart">
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
                        <div class="card-description" style="aspect-ratio: 16/9">
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
</div>
<?php 
fm::displayPopMessagesByContext('outlet_testing', 'bottom-right');
?>
<script src="<?= PROJECT_ROOT ?>/public/js/services/flashMessageCloseDelay.js"></script>
    <!-- Random Shit -->
    <script>
        var ctx = document.querySelector('canvas.income').getContext('2d');
        let gradient = ctx.createLinearGradient(0, 0, 0, 400);
        gradient.addColorStop(0, "rgba(72, 149, 239, 0.22)");
        gradient.addColorStop(1, "transparent");
        var chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['J', 'Feb', 'Mar', 'Apr', 'Mei'],
                datasets: [{
                    label: 'Income',
                    data: [100, 200, 150, 300, 250, 400, 350],
                    fill: true,
                    backgroundColor: gradient,
                    borderColor: '#4895ef',
                    pointBackgroundColor: "#fff",
                    pointBorderColor: "#4C8EF0",
                    pointBorderWidth: 2,
                    borderWidth: 2,
                    tension: 0.5
                }]
            },
            options: {
                plugins: {
                    legend: {
                        display: false,
                    },
                },
                radius: 5,
                hitRadius: 30,
                hoverRadius: 12,
                scales: {
                    y: {
                        ticks: {
                            display: false,
                            beginAtZero: false,
                            fontColor: '#fff',
                            fontSize: 10,
                            padding: 10,
                            fontFamily: 'Poppins'
                        },

                        border: {
                            display: false
                        },
                    },
                    x: {
                        ticks: {

                            beginAtZero: false,
                            color: 'rgba(0, 0, 0, 0.45)',
                            fontSize: 10,
                            padding: 10,
                            fontFamily: 'Poppins'
                        },
                        grid: {
                            display: false,
                            drawOnChartArea: false,
                            drawTicks: false,
                        },
                        border: {
                            display: false
                        }
                    }
                },

            }
        });
    </script>

    <!-- Welcome column chart -->
    <script>
        var ctx = document.querySelector('canvas.report-income-statistics');
        let delayed;
        var chart = new Chart(ctx, {
            type: 'bar', // Change the type to 'bar'
            data: {
                labels: ['S', 'S', 'R', 'K', 'J', 'S', 'M'],
                datasets: [{
                    label: 'Income',
                    data: [100, 200, 150, 300, 250, 400, 350],
                    backgroundColor: '#4895ef', // Set the background color for the bars
                    borderWidth: 0, // Remove the border width,
                    barThickness: 30,
                    barPercentage: 1,
                    borderSkipped: false,
                    barBorderRadius: 7,
                }]
            },
            options: {
                responsive: true,
                responsiveAnimationDuration: 5000,
                animation: {
                    onComplete: () => {
                        delayed = true;
                    },
                    delay: (context) => {
                        let delay = 0;
                        if (context.type === 'data' && context.mode === 'default' && !delayed) {
                            delay = context.dataIndex * 500 + context.datasetIndex * 350;
                        }
                        return delay;
                    },
                },
                plugins: {
                    legend: {
                        display: false,
                    },
                    tooltip: {
                        callbacks: {
                            label: ((tooltipItem, data) => {
                                return tooltipItem.formattedValue
                            })
                        }
                    }
                },
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
                            padding: 10
                        }
                        // ticks: {
                        //     display: false
                        // }
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