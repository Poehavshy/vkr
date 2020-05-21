<!-- Footer -->
<footer class="page-footer font-small stylish-color-dark pt-4 w-75">

    <!-- Footer Links -->
    <div class="container text-center text-md-left">

        <!-- Grid row -->
        <div class="row">

            <!-- Grid column -->
            <div class="col-md-4 mx-auto">

                <!-- Content -->
                <h5 class="font-weight-bold text-uppercase mt-3 mb-4">VKR</h5>
                <p>Сайт для создания смарт-контрактов по заданным шаблонам.</p>

            </div>
            <!-- Grid column -->

            <hr class="clearfix w-100 d-md-none">

            <!-- Grid column -->
            <div class="col-md-4 mx-auto">

                <!-- Links -->
                <h5 class="font-weight-bold text-uppercase mt-3 mb-4">Ссылки</h5>

                <ul class="list-unstyled">
                    <li>
                        <a href="/">Главная страница</a>
                    </li>
                    <li>
                        <a href="/profile">Личный кабинет</a>
                    </li>
                    <li>
                        <a href="#!">Link 3</a>
                    </li>
                    <li>
                        <a href="#!">Link 4</a>
                    </li>
                </ul>

            </div>
            <!-- Grid column -->



        </div>
        <!-- Grid row -->

    </div>
    <!-- Footer Links -->

    <hr>

    <!-- Call to action -->
    @guest
        @if (Route::has('register'))
            <ul class="list-unstyled list-inline text-center py-2">
                <li class="list-inline-item">
                    <h5 class="mb-1">Бесплатная регистрагия</h5>
                </li>
                <li class="list-inline-item">
                    <a href="{{ route('register') }}" class="btn btn-danger btn-rounded">Присоедениться</a>
                </li>
            </ul>
            <hr>
    @endif
    @endguest

    <!-- Call to action -->


    <!-- Copyright -->
    <div class="footer-copyright text-center py-3">© 2020 Copyright: Denis Demirchyan
    </div>
    <!-- Copyright -->

</footer>
<!-- Footer -->
