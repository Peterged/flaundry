<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        nav {
            background-color: #583838;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .logo img {
            width: 50px;
            height: auto;
            border-radius: 1000px;
        }


        .kotak-tombol {
            display: flex;
        }

        .kotak-tombol a {
            color: white;
            display: block;
            float: right;
            text-align: center;
            padding: 14px 16px;
            text-decoration: none;
            border-bottom: 2px solid transparent;
        }

        .kotak-tombol a:hover {
            color: #ddd;
            border-bottom: 2px solid #fff;
        }


        body {
            font-family: 'Arial', sans-serif;
            background-color: #f5f5f5;
            color: #161414;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            text-align: center;
            padding: 20px;
        }
    </style>
</head>

<body>
    <nav>
        <div class="logo">
            <img src="img/logo.jpg" alt="">
        </div>
        <div class="kotak-tombol">
            <a href="#Home">Home</a>
            <a href="#Menu">Menu</a>
            <a class="active" href="#">About Us</a>
            <a href="#Contact">Contact</a>
        </div>
    </nav>

    <div style="padding:20px">
        <h3>Isi Konten</h3>
        <p>Contoh halaman dengan navbar sederhana.</p>
    </div>

</body>

</html>