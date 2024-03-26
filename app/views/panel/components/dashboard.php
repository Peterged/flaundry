<?php

use App\Services\FlashMessage as fm;
use App\Libraries\Essentials\Session;

Session::startToken();
$role = Session::get('role');

?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="<?= PROJECT_ROOT ?>/public/images/flaundry-logo-icon.png" type="image/png" />
    <link rel="stylesheet" href="<?= PROJECT_ROOT ?>/public/css/global.css">
    <link rel="stylesheet" href="<?= PROJECT_ROOT ?>/public/css/panel/sidebar.css">
    <link rel="stylesheet" href="<?= PROJECT_ROOT ?>/public/css/panel/sidebar.css">
    <link rel="stylesheet" href="<?= PROJECT_ROOT ?>/public/css/panel/navbar.css">
    <link rel="stylesheet" href="<?= PROJECT_ROOT ?>/public/css/panel/card.css">
    <link rel="stylesheet" href="<?= PROJECT_ROOT ?>/public/css/services/flashMessage.css">
    <link rel="stylesheet" href="<?= PROJECT_ROOT ?>/public/css/panel/components/dashboard.css">
    <script src="<?= PROJECT_ROOT ?>/public/js/chart.js"></script>
    <title>Dashboard | FLaundry</title>
</head>
<?php includeFile("$base/panel/inc/sidebar.php") ?>
<?php includeFile("$base/panel/inc/navbar.php") ?>

<div class="container">
    <div class="content-box">
        <div class="title">
            <h1 class="title-text">Dashboard</h1>
            <div class="title-date">
                <p class="title-date-text"></p>
            </div>
        </div>
        <span class="divider"></span>
        <div class="introduction">
            <div class="card card-introduction">
                <div class="card-content">
                    <div class="card-body">
                        <div class="card-title">
                            <p>Welcome back, <?= ucfirst($_SESSION['username']) ?></p>
                        </div>
                        <div class="card-chart">
                            <p>Selamat datang di FLaundry!</p>
                        </div>
                        <div class="card-button">
                            <a href="<?= routeTo("/panel/report") ?>" class="card-button-link">View Report</a>
                        </div>
                    </div>
                    <div class="card-chart">
                        <canvas class="report-income-statistics">

                        </canvas>
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
fm::displayPopMessagesByContext('welcome-message', 'bottom-right', 5000);
?>
<script src="<?= PROJECT_ROOT ?>/public/js/services/flashMessageCloseDelay.js"></script>
<!-- Random Shit -->
<script>
    var ctx = document.querySelector('canvas.report-income-statistics').getContext('2d');
    let gradient = ctx.createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, "rgba(72, 149, 239, 0.22)");
    gradient.addColorStop(1, "transparent");
    
</script>

<!-- Welcome column chart -->
<?php if($role == 'admin'): ?>
<script>
    const baseProjectFolder = "/flaundry";
    // console.log(window.location.origin + `${baseProjectFolder}/api/panel/dashboard`);
    fetch(window.location.origin + `${baseProjectFolder}/api/panel/dashboard`, {
            method: "GET",
            headers: {
                "Content-Type": "application/json",
            },
        })
        .then((response) => response.json())
        .then((data) => {
            console.log(data);
            var ctx = document.querySelector('canvas.report-income-statistics');
            let delayed;
            let curr = new Date();
            
            let first = curr.getDate() - (curr.getDay() === 0 ? 6 : curr.getDay() - 1);
            let last = first + 6;
            var firstDay = new Date(curr.setDate(first)).toLocaleDateString();
            let lastDay = new Date(curr.setDate(last)).toLocaleDateString();
            
            var chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
                    datasets: [{
                        label: `Penghasilan dari ${firstDay} - ${lastDay}`,
                        data: [...data.data],   
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
                            // display: false,
                        },
                        tooltip: {
                            callbacks: {
                                label: ((tooltipItem, data) => {

                                    return `Rp. ${tooltipItem.formattedValue}`
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
                        },
                        y: {
                            border: {
                                display: false
                            },
                            grid: {
                                // display: false,
                                // drawOnChartArea: false,
                                // drawTicks: false,
                            },
                            ticks: {
                                display: false
                            }
                        }
                    }
                }
            });
        })
</script>
<?php endif; ?>