@extends('layouts.base')
@section('content')
    <div class="title m-b-md flex-center">
        Личный кабинет
    </div>
    <nav class="navbar navbar-light bg-light">
        <form class="form-inline">
            <button class="btn btn-outline-success child child" type="button" onclick="window.location = '/profile'" @if($page == '/') disabled @endif>Мои данные</button>
            <button class="btn btn-sm align-middle btn-outline-secondary child" type="button" onclick="window.location = '/profile/create-contract'" @if($page == '/create-contract') disabled @endif>Создать контракт</button>
            <button class="btn btn-sm align-middle btn-outline-secondary child" type="button" onclick="window.location = '/profile/my-contract'" @if($page == '/my-contract') disabled @endif>Мои контракты</button>
        </form>
    </nav>
    <div id="profile-info" class="marg-profile">
        @if($page == '/')
            @include('includes.profile-info.data')
        @elseif($page == '/create-contract')
            @include('includes.profile-info.create-contract')
        @elseif($page == '/my-contract')
            @include('includes.profile-info.my-contract')
        @else
            404
        @endif
    </div>
@stop
