@extends('layouts.backend')

@section('content')
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css">

    <div class="container">
        <div class="row">
            @include('admin.sidebar')

            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">Участники</div>

                    <div class="card-body">
                        <div class="row mb-5 pb-3 d-flex justify-content-between container">
                            <button class="btn btn-success" data-toggle="modal" data-target="#addParticipantsModal" >добавить</button>
                            <button class="btn btn-success">импортировать</button>

                        </div>

                        <table id="participants">
                            <thead>
                            <tr>
                                <th>id</th>
                                <th> Имя / Фамилия</th>
                                <th> почта </th>
                                <th> Дата участие </th>
                            </tr>
                            </thead>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="addParticipantsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Добавление участника</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="{{route("addParticipants")}}" method="post">
                        @csrf
                        <input type="text" required class="form-control mb-2" placeholder="Имя" name="name">
                        <input type="text" required class="form-control mb-2" placeholder="фамилия" name="surname">
                        <input type="email" required class="form-control mb-2" placeholder="почта" name="email">
                        <input type="date" required class="form-control" dataformatas="dmy" name="participated_date" placeholder="время провождения">
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
                            <button type="submit" class="btn btn-primary">Создать</button>
                        </div>

{{--                        <button type="submit" class="btn btn-primary text-right">добавить</button>--}}
                    </form>
                </div>

            </div>
        </div>
    </div>


@endsection
