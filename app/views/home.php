<!DOCTYPE html>
<html lang="en">

<head>
<link rel="shortcut icon" href="<?= PROJECT_ROOT ?>/public/images/flaundry-logo-icon.png" type="image/png" />
    <link rel="stylesheet" href="<?= PROJECT_ROOT ?>/public/css/global.css">
    <link rel="stylesheet" href="public/css/home.css">
    <link rel="stylesheet" href="public/css/layouts/navbar.css">
    <title>FLaundry</title>
</head>

<body>
    <?php includeFile("$base/layouts/navbar.php") ?>
    <main>
        <div class="container">
            <div class="content">
                <div class="content-left">
                    <canvas class="sample-chart"></canvas>
                </div>
                <div class="content-right">
                    <img src="public/images/laundry.png" alt="">
                </div>
            </div>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="public/js/homeChart.js"></script>
</body>

</html>