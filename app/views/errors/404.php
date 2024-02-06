<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= PROJECT_ROOT ?>/public/css/global.css">
    <title>404 | Not Found</title>
</head>
<body>
    <style>
        * {
            box-sizing: border-box;
        }
        body {
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            height: 100vh;
            overflow: hidden;

            display: grid;
            place-items: center;

            background-color: #121212;
            color: rgba(255, 255, 255, 0.5);
        }

        .container .error-box {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 1rem;
        }

        .container .error-box span {
            height: 100%;
        }

        .container .error-box p,
        .container .error-box span {
            font-size: 1.4rem;
            font-weight: 300;
        }
    </style>
    <div class="container">

        <div class="error-box">
            <p>404</p>
            <span>|</span>
            <p>Not Found</p>
        </div>
    </div>
</body>
</html>
