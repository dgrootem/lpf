<nav class="navbar navbar-expand-md navbar-dark bg-dark">
    <div class="navbar-collapse collapse w-100 order-1 order-md-0 dual-collapse2">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
              <a class="navbar-brand" href="{{ url('/') }}">
                <img src="http://www.skbl.be/joomla/images/logo/logo.png" height="80">
                  {{ config('app.name', 'LPF') }}

              </a>
            </li>
        </ul>
    </div>
    <div class="mx-auto order-0">
        <canvas id="my-chart-1" width="600" height="90"></canvas>
        {{-- <a class="navbar-brand mx-auto" href="#">Navbar 2</a> --}}

    </div>
    <div class="navbar-collapse collapse w-100 order-3 dual-collapse2">
        <ul class="navbar-nav ml-auto">
            @include('layouts.authlinks')
        </ul>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target=".dual-collapse2">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
</nav>
