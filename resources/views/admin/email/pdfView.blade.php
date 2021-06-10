<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">

    <style>
        body {
            font-family: DejaVu Sans;
        }
        .image-background{
            margin-top: -45px;
            margin-bottom: -50px;
            margin-left: -45px;
            min-width: 1123px;
            min-height: 794px;
        }
        .image-container{
            position: relative;
            margin: 0 auto;
            display: inline-block
        }
        .participant_name{
            position: absolute;
            z-index: 2;
            font-size: 40px;
            left: 355px;
            top: 300px;
        }
    </style>
</head>

<body>
<div class="image-container">
    <img class="image-background" src="http://127.0.0.1/projects/admin.inpolicy.net/public/assets/cert_template.png" alt="">
    <div class="participant_name">
        {{ isset($name_surname) ? $name_surname : "Name Surname" }}
    </div>
</div>

</body>
</html>
