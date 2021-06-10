

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css" integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">

<div class="card container">
    <div class="card-header">
        Фестиваль
    </div>
    <div class="card-body">
        {!! $content !!}

        @if(request()->get("with_certificate") == 1)
            <a href="{{asset($user->certificate_url)}}">Вложение</a>
        @endif
    </div>
</div>
