@extends('layouts.backend')

@section('content')
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">

    <div class="container">
        <div class="row">
            @include('admin.sidebar')

            <div class="col-md-9">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div>Партеры</div>
                        <div class="p-2 mb-0 alert alert-info">
                            кол-о: {{sizeof($partners)}}
                        </div>
                    </div>
                    @if(session('message'))
                        <div class="alert alert-primary">
                            {!! session('message') !!}
                        </div>
                    @endif
                    <div class="card-body">
                        <div class="row mb-5 pb-3 d-flex justify-content-between container">
                            {{--                            <button class="btn btn-success" data-toggle="modal" data-target="#addSpeakersModal" >добавить</button>--}}
                            <button class="btn btn-success" data-toggle="modal" data-target="#createCertificateModal" >Сгенерировать сертификат</button>
                            <button class="btn btn-success" data-toggle="modal" data-target="#importPartnersModal">импортировать</button>

                        </div>
                        <form action="">
                            <div class="row">
                                <div class="form-group mr-2">
                                    <label>Поиск по имени</label>
                                    <input type="text" value="{{request()->get("search", null)}}" name="search" placeholder="name" class="form-control" >
                                </div>

                                <div class="form-group mr-2">
                                    <label>Тип партнеров </label>
                                    <select name="testing" class="form-control">
                                        <option value="all" @if(request()->get("testing") == "all") selected @endif >Все</option>
                                        <option value="0" @if(request()->get("testing") == "0") selected @endif>Не тестовые</option>
                                        <option value="1" @if(request()->get("testing") == "1") selected @endif>Тестовые</option>
                                    </select>
                                </div>
                                <div class="form-group mr-2">
                                    <label>Наличие сертификата</label>
                                    <select name="certificate" class="form-control">
                                        <option value="all" @if(request()->get("certificate") == "all") selected @endif>Все</option>
                                        <option value="1" @if(request()->get("certificate") == "1") selected @endif>С сертификатом</option>
                                        <option value="0" @if(request()->get("certificate") == "0") selected @endif>Без сертификата</option>
                                    </select>
                                </div>
                                <div class="form-group mr-2">
                                    <label>Язык спикера</label>
                                    <select name="language" class="form-control">
                                        <option value="all" @if(request()->get("language") == "all") selected @endif>Все</option>
                                        <option value="ru" @if(request()->get("language") == "ru") selected @endif>Рус</option>
                                        <option value="en" @if(request()->get("language") == "en") selected @endif>Eng</option>
                                    </select>
                                </div>
                                <div class="form-group mr-2">
                                    <label >Дата приглашения</label>
                                    <select name="session_date" class="form-control">
                                        <option value="all" @if(request()->get("session_date") == "all") selected @endif>Все</option>
                                        @foreach($invitation_date as $date)
                                            <option value="{{$date}}" @if(request()->get("session_date") == $date) selected @endif>{{$date}}</option>
                                        @endforeach
                                    </select>
                                </div>

                            </div>
                            <div class="form-group mr-2">
                                <div class="btn-group">
                                    <button class="btn btn-info">фильтр</button>
                                    <a href="/admin/partners" class="btn btn-outline-info">Сбросить</a>
                                </div>
                            </div>
                        </form>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Имя/Фамилия/Отчество</th>
                                    <th scope="col">Почта</th>
                                    <th scope="col">Организация</th>
                                    <th scope="col">Cert</th>
                                    <th scope="col">Дата приглашения</th>
                                    <th scope="col">Действие</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($partners as $partner)
                                    <tr>
                                        <th scope="row">{{$partner->id}}</th>
                                        <td>{{$partner->name . "/".$partner->surname . "/" . $partner->fathersname}}</td>
                                        <td>{{$partner->email}}</td>

                                        <td>{{$partner->organization}}</td>
                                        <td><a target="_blank" href="{{ asset($partner->certificate_url) }}"> {{$partner->certificate_url ? "cert" : "No"}} </a> </td>
                                        <td>{{$partner->invitation_date}}</td>
                                        <td>
                                            <div class="btn-group">
                                                <button class="btn btn-warning">O</button>
                                                <a href="{{route("deleteSpeakers", $partner->id)}}" class="btn btn-danger">X</a>
                                                <a href="{{route("testingSpeakers", $partner->id)}}" class="btn btn-info">
                                                    @if($partner->testing == 1)
                                                        {{$partner->testing}}
                                                    @else
                                                        {{$partner->testing}}
                                                    @endif
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="createCertificateModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Сгенерировать сертификат для Партнеров</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group container d-flex flex-column">
                            <a href="{{ asset('templates/test.docx') }}">Шаблон для примера</a>
                            <a href="#">Правила генерации</a>
                        </div>
                    </div>
                    <form action="{{route("generate_cert_partners")}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="partners_count" value="{{sizeof($partners)}}">
                        <input type="hidden" name="partners_id" value="{{$partners_ids}}">
                        <div class="form-group mt-3 mb-3">
                            <label for="partners_file">Выбрать файл(doc/docx)</label>
                            <input type="file" aria-required="true" required class="form-control mb-2" placeholder="doc file" accept=".docx, .doc," name="cert_generate_file">
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
                            <button type="submit" class="btn btn-primary">сгенерировать</button>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="importPartnersModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Импортировать партнеров </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="form-group container d-flex flex-column">
                            <a href="{{asset('templates/test_speakers.xlsx')}}">Шаблон для примера</a>
                            <a href="#">Правила импорта</a>
                        </div>
                    </div>
                    <form action="{{route("importPartners")}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group mt-3 mb-3">
                            <label for="partners_file">Выбрать файл</label>
                            <input type="file" aria-required="true" required class="form-control mb-2" placeholder="xls file" accept=".xlsx, .xls, .csv" name="partners_file">
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
                            <button type="submit" class="btn btn-primary">импорт</button>
                        </div>

                    </form>
                </div>

            </div>
        </div>
    </div>


@endsection
