@extends('layouts.base')
@section('content')
    <div>
        <img src="{{ asset('/images/smart-contract.jpg') }}" class="w-75">
        <h1>Смарт-контракты</h1>
        <p class="ta-j smart-text"><strong>Смарт-контракт</strong> — компьютерный алгоритм, предназначенный для заключения и поддержания коммерческих контрактов в технологии блокчейн.
            <br><br>
            Технология смарт-контрактов предлагает программный способ описания взаимоотношений и автоматических действий между сторонами. Одной из особенностей смарт-контракта является его децентрализованная природа и однозначная автоматическая исполняемость — единожды размещенный на децентрализованной платформе, такой контракт будет существовать и исполняться всегда (при наступлении соответствующих событий), пока будет существовать такая платформа.</p>
        <div class="box">
            <div>
                <div>
                    <img src="{{ asset('/images/key.svg') }}" class="w-100px">
                    <p class="img-text">Автономность</p>
                </div>
                <p class="fs-18">Единожды размещенный в блокчейн сети, смарт-контракт будет всегда выполняться единообразно и автоматически, при наступлении запрограммированных событий.</p>
            </div>

            <div>
                <div>
                    <img src="{{ asset('/images/credit-card.svg') }}" class="w-100px">
                    <p class="img-text">Безопаснотсь</p>
                </div>
                <p class="fs-18">Безопасность и единообразность исполнения смарт-контрактов обеспечивается децентрализованной природой и алгоритмами консенсуса блокчейн сети. Ни одна из сторон смарт-контракта не имеет возможности внести изменения в смарт-контракт, после момента размещения его в блокчейн сети.</p>
            </div>
            <div>
                <div>
                    <img src="{{ asset('/images/computer-server.svg') }}" class="w-100px">
                    <p class="img-text">Эффективность</p>
                </div>
                <p class="fs-18">Смарт-контракты позволяют исключить неэффективных, не несущих полезной нагрузки посредников из цепочек поставок. Транзакции проходят автоматически без дополнительного одобрения сторонами смарт-контракта.</p>
            </div>
        </div>
    </div>
@stop
