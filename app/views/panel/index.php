<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="shortcut icon" href="<?= PROJECT_ROOT ?>/public/images/flaundry-logo-icon.png" type="image/png" />
    <link rel="stylesheet" href="<?= PROJECT_ROOT ?>/public/css/global.css">
    <link rel="stylesheet" href="<?= PROJECT_ROOT ?>/public/css/panel/sidebar.css">
    <link rel="stylesheet" href="<?= PROJECT_ROOT ?>/public/css/panel/sidebar.css">
    <link rel="stylesheet" href="<?= PROJECT_ROOT ?>/public/css/panel/navbar.css">
    <link rel="stylesheet" href="<?= PROJECT_ROOT ?>/public/css/panel/card.css">
    <link rel="stylesheet" href="<?= PROJECT_ROOT ?>/public/css/panel/components/dashboard.css">
    <title>Dashboard | FLaundry</title>
</head>

<body>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        Chart.defaults.font.family = "Arial";
        Chart.defaults.font.weight = "bold";
    </script>
    
    <?php
    include_once 'inc/sidebar.php';
    include_once 'inc/navbar.php';
    // if(routeTo('/dashboard'))
    //     require_once 'app/views/panel/dashboard.php';
    // else if(routeTo('order'))
    //     require_once 'app/views/panel/order.php';
    // else if(routeTo('order-detail'))
    //     require_once 'app/views/panel/order-detail.php';
    // else if(routeTo('order-add'))
    //     require_once 'app/views/panel/order-add.php';
    // else if(routeTo('order-edit'))
    //     require_once 'app/views/panel/order-edit.php';
    // else if(routeTo('customer'))
    //     require_once 'app/views/panel/customer.php';
    // else if(routeTo('customer-detail'))
    //     require_once 'app/views/panel/customer-detail.php';
    // else if(routeTo('customer-add'))
    //     require_once 'app/views/panel/customer-add.php';
    // else if(routeTo('customer-edit'))
    //     require_once 'app/views/panel/customer-edit.php';
    // else if(routeTo('employee'))
    //     require_once 'app/views/panel/employee.php';
    // else if(routeTo('employee-detail'))
    //     require_once 'app/views/panel/employee-detail.php';
    // else if(routeTo('employee-add'))
    //     require_once 'app/views/panel/employee-add.php';
    // else if(routeTo('employee-edit'))
    //     require_once 'app/views/panel/employee-edit.php';
    // else if(routeTo('service'))
    //     require_once 'app/views/panel/service.php';
    // else if(routeTo('service-detail'))
    //     require_once 'app/views/panel/service-detail.php';
    // else if(routeTo('service-add'))
    //     require_once 'app/views/panel/service-add.php';
    // else if(routeTo('service-edit'))
    //     require_once 'app/views/panel/service-edit.php';
    // else if(routeTo('report'))
    //     require_once 'app/views/panel/report.php';
    // else if(routeTo('report-detail'))
    //     require_once 'app/views/panel/report-detail.php';
    // else if(routeTo('report-add'))
    //     require_once 'app/views/panel/report-add.php';
    // else if(routeTo('report-edit'))
    //     require_once 'app/views/panel/report-edit.php';
    // else if(routeTo('setting'))
    //     require_once 'app/views/panel/setting.php';
    // else if(routeTo('setting-detail'))
    //     require_once 'app/views/panel/setting-detail.php';
    // else if(routeTo('setting-add'))
    //     require_once 'app/views/panel/setting-add.php';
    // else if(routeTo('setting-edit'))
    //     require_once 'app/views/panel/setting-edit.php';
    // else if(routeTo('profile'))
    //     require_once 'app/views/panel/profile.php';
    // else if(routeTo('profile-detail'))
    //     require_once 'app/views/panel/profile-detail.php';
    // else if(routeTo('profile-add'))
    //     require_once 'app/views/panel/profile-add.php';
    // else if(routeTo('profile-edit'))
    //     require_once 'app/views/panel/profile-edit.php';
    // else if(routeTo('logout'))
    //     require_once 'app/views/panel/logout.php';
    // else
    include_once 'app/views/panel/components/dashboard.php';
    ?>

</body>

</html>
