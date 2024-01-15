<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 | Not Found</title>
</head>
<body>
    <style>
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
            <p>500</p>
            <span>|</span>
            <p>Internal Server Error</p>
        </div>
    </div>
</body>
</html>
