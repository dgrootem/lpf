@guest
    <li class="nav-item">
        <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
    </li>
@else
    <li class="nav-item dropdown">
        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
            {{ Auth::user()->name }} <span class="caret"></span>
        </a>
        {{--  TODO: add 'admin' flag to users table to check for this--}}


        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
            @if ((Auth::user()->id == 1) || (Auth::user()->id==7))
            <a class="dropdown-item " href="{{ url('/user/'.Auth::user()->id.'/edit') }}">Wijzig profiel</a>
            @endif
            <a class="dropdown-item " href="{{ route('logout') }}"
               onclick="event.preventDefault();
                             document.getElementById('logout-form').submit();">
                {{ __('Logout') }}
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </div>
    </li>
@endguest
