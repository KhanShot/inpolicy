<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Certificate</title>
</head>
<style>
    .first{
        margin: 0;
    }
    body{
        margin: 0 auto;
    }
    .body{
        margin: 0 auto;
        background: #a0aec0;
        display: flex;
        justify-content: center;
        flex-direction: column;
        align-items: center;
        padding: 50px;

    }
    .container{
        background: white;
        border-radius: 15px;
        padding: 20px;
        width: 50%;
    }
</style>
<body>

<div class="body">
    <div class="container">
        <p class="first">Уважаемый/ая {{$participants->name }} ,</p>

        <p>От команды Фонда Инклюзивного Развития, благодарим Вас за участие в II Central Asia Nobel Fest. Мы очень рады, что Вы стали частью нашего мероприятия, и надеемся увидеть Вас в октябре 2021 года на третьем Нобелевском фестивале, где Вы сможете увидеть еще больше увлекательных дискуссий и интервью.</p>

        <p>Ваше мнение для нас очень важно, поэтому мы просим Вас заполнить форму обратной связи, которая доступна по следующей ссылке.</p>

        <p>Во вложении Вы можете скачать свой именной сертификат участника.</p>
        <a href="http://localhost/projects/admin.inpolicy.net/public/download/pdf/{{$participants->id . "/". $participants->name . "?download"}}">Ваш сертификат</a>
        <hr>
        <p>Спасибо за Вашу поддержку!</p>

        С уважением,

        Команда II Нобелевского фестиваля
    </div>
    <div style="margin-top: 25px">
        © 2021 Inpolicy.net. All rights reserved.
    </div>
</div>

</body>
</html>
