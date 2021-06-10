@extends('layouts.backend')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">


<link rel="stylesheet" href="{{ asset("/editor/css/froala_editor.css") }}">
<link rel="stylesheet" href="{{ asset("/editor/css/froala_style.css") }}">
<link rel="stylesheet" href="{{ asset("/editor/css/plugins/code_view.css") }}">
<link rel="stylesheet" href="{{ asset("/editor/css/plugins/colors.css") }}">
<link rel="stylesheet" href="{{ asset("/editor/css/plugins/emoticons.css") }}">
<link rel="stylesheet" href="{{ asset("/editor/css/plugins/image_manager.css") }}">
<link rel="stylesheet" href="{{ asset("/editor/css/plugins/image.css") }}">
<link rel="stylesheet" href="{{ asset("/editor/css/plugins/line_breaker.css") }}">
<link rel="stylesheet" href="{{ asset("/editor/css/plugins/table.css") }}">
<link rel="stylesheet" href="{{ asset("/editor/css/plugins/char_counter.css") }}">
<link rel="stylesheet" href="{{ asset("/editor/css/plugins/video.css") }}">
<link rel="stylesheet" href="{{ asset("/editor/css/plugins/fullscreen.css") }}">
<link rel="stylesheet" href="{{ asset("/editor/css/plugins/file.css") }}">
<link rel="stylesheet" href="{{ asset("/editor/css/plugins/quick_insert.css") }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.3.0/codemirror.min.css">

<style>
    .full-sized{
        width: 70%;
    }

</style>
@section('content')
    <div class="container">
        <div class="row">
            @include('admin.sidebar')
            <div class="full-sized">
                <div>
                    <h2>Создать сообщение</h2>
                    <div class="d-flex flex-row  align-items-center justify-content-between">
                        <div class="btn-group">
                            <button class="btn btn-primary"> Подсказка </button>
                            <button class="btn btn-info" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                                Выбрать получателей
                            </button>

                        </div>
                        <div class="p-2 mb-0 alert alert-info">
                            кол. получателей: {{ sizeof($receiver_ids) ?? 0}}
                        </div>
                    </div>


                    @if(session('message'))
                        <div class="alert alert-primary">
                            {!! session('message') !!}
                        </div>
                    @endif

                    <div class="collapse show" id="collapseExample">
                        <div class="card card-body">
                            <form class="form" id="select_filter">
                                <div class="row d-flex justify-content-between">
                                    <div class="form-group">
                                        <?php
                                            $selected = request()->get("message_to") == "speakers";
                                        ?>
                                        <select name="message_to" id="message_to" class="form-control">
                                            <option value="speakers"  @if(request()->get('message_to') == 'speakers') selected @endif > Спикеры </option>
                                            <option value="participants"  @if(request()->get('message_to') == 'participants') selected @endif> Участники </option>
                                            <option value="partners" @if(request()->get('message_to') == 'partners') selected @endif > Партенры </option>
                                        </select>
                                    </div>
                                    @if(request()->get("message_to") == "speakers" )
                                        <div class="form-group">
                                            <select name="language" id="language" class="form-control">
                                                <option value="ru" @if(request()->get("language") == "ru") selected @endif > РУС </option>
                                                <option value="en" @if(request()->get("language") == "en") selected @endif > EN </option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <select name="certificate" id="certificate" class="form-control">
                                                <option value="all" @if(request()->get("certificate") == "all") selected @endif > Все </option>
                                                <option value="1" @if(request()->get("certificate") == "1") selected @endif > С сертификатом </option>
                                                <option value="0" @if(request()->get("certificate") == "0") selected @endif > Без сертификата </option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <select name="testing" id="language" class="form-control">
                                                <option value="0" @if(request()->get("testing") == "0") selected @endif > Не тестовые </option>
                                                <option value="1" @if(request()->get("testing") == "1") selected @endif > Тестовые </option>
                                            </select>
                                        </div>
                                        <select name="session_date" class="form-control">
                                            <option value="all" @if(request()->get("session_date") == "all") selected @endif>Все</option>
                                            @foreach($invitation_date as $date)
                                                <option value="{{$date}}" @if(request()->get("session_date") == $date) selected @endif>{{$date}}</option>
                                            @endforeach
                                        </select>
                                    @endif


                                </div>
                                <div class="row">
                                    <div class="form-group">
                                        <input type="submit" class="btn btn-success" value="Фильтр">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <form id="message_form" class="form-content" action="{{route('test_email')}}">

                    <textarea required class="content" name="content" id="edit" >

                    </textarea>

                    <input type="hidden" name="message_to" value="{{request()->get("message_to")}}">
                    <input type="hidden" name="receiver_ids" value="{{$receiver_ids}}">

                    <div class="btn-group">
                        <input type="submit" name="send" class="btn btn-success" value="Отправить">
                        <input type="submit" id="preview" name="preview" class="btn btn-info" value="Предосмотр">
                        <select class="form-control" name="with_certificate" id="">
                            <option value="0">отправить без вложении</option>
                            <option value="1">отправить со вложением</option>
                        </select>
                    </div>
                    <div>
                        </div>
                </form>
            </div>

        </div>
    </div>

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.3.0/codemirror.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.3.0/mode/xml/xml.min.js"></script>

    <script type="text/javascript" src="{{ asset("/editor/js/froala_editor.min.js") }}" ></script>
    <script type="text/javascript" src="{{ asset("/editor/js/plugins/align.min.js") }}"></script>
    <script type="text/javascript" src="{{ asset("/editor/js/plugins/char_counter.min.js") }}"></script>
    <script type="text/javascript" src="{{ asset("/editor/js/plugins/code_beautifier.min.js") }}"></script>
    <script type="text/javascript" src="{{ asset("/editor/js/plugins/code_view.min.js") }}"></script>
    <script type="text/javascript" src="{{ asset("/editor/js/plugins/colors.min.js") }}"></script>
    <script type="text/javascript" src="{{ asset("/editor/js/plugins/draggable.min.js") }}"></script>
    <script type="text/javascript" src="{{ asset("/editor/js/plugins/emoticons.min.js") }}"></script>
    <script type="text/javascript" src="{{ asset("/editor/js/plugins/entities.min.js") }}"></script>
    <script type="text/javascript" src="{{ asset("/editor/js/plugins/file.min.js") }}"></script>
    <script type="text/javascript" src="{{ asset("/editor/js/plugins/font_size.min.js") }}"></script>
    <script type="text/javascript" src="{{ asset("/editor/js/plugins/font_family.min.js") }}"></script>
    <script type="text/javascript" src="{{ asset("/editor/js/plugins/fullscreen.min.js") }}"></script>
    <script type="text/javascript" src="{{ asset("/editor/js/plugins/image.min.js") }}"></script>
    <script type="text/javascript" src="{{ asset("/editor/js/plugins/image_manager.min.js") }}"></script>
    <script type="text/javascript" src="{{ asset("/editor/js/plugins/line_breaker.min.js") }}"></script>
    <script type="text/javascript" src="{{ asset("/editor/js/plugins/inline_style.min.js") }}"></script>
    <script type="text/javascript" src="{{ asset("/editor/js/plugins/link.min.js") }}"></script>
    <script type="text/javascript" src="{{ asset("/editor/js/plugins/lists.min.js") }}"></script>
    <script type="text/javascript" src="{{ asset("/editor/js/plugins/paragraph_format.min.js") }}"></script>
    <script type="text/javascript" src="{{ asset("/editor/js/plugins/paragraph_style.min.js") }}"></script>
    <script type="text/javascript" src="{{ asset("/editor/js/plugins/quick_insert.min.js") }}"></script>
    <script type="text/javascript" src="{{ asset("/editor/js/plugins/quote.min.js") }}"></script>
    <script type="text/javascript" src="{{ asset("/editor/js/plugins/table.min.js") }}"></script>
    <script type="text/javascript" src="{{ asset("/editor/js/plugins/save.min.js") }}"></script>
    <script type="text/javascript" src="{{ asset("/editor/js/plugins/url.min.js") }}"></script>
    <script type="text/javascript" src="{{ asset("/editor/js/plugins/video.min.js") }}"></script>

    <script>

        (function(){
            new FroalaEditor("#edit",{
                // Define new link styles.
                linkStyles: {
                    class1: 'Class 1',
                    class2: 'Class 2'
                }
            })
        })()

    </script>
@endsection
