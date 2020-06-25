@extends('layouts.base')
@section('content')
    <div class="title m-b-md flex-center">
        Биржа контрактов
    </div>
    <div id="error_message"><p class="error-text"></p></div>
    <table id="contracts_table" class="table table-hover table-striped table-bordered">
        <caption>Смарт-контракты</caption>
        <thead>
        <tr>
            <th scope="col"></th>
            <th scope="col">Контракт</th>
            <th scope="col">Адрес контракта</th>
            <th scope="col">Инструкция</th>
            <th scope="col">Статус</th>
        </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
    <div id="place_instruction_form"></div>
@stop
