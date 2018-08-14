@extends('layouts.master')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Register') }}</div>

                <div class="card-body">

                  <form class="form-horizontal" role="form" method="POST" action="{{ route('register') }}">
                  <!--Other form fields above the button-->

                    <div class="form-group">
                        <div class="col-md-6 col-md-offset-4">
                          <a href="{{ url('/auth/google') }}" class="btn btn-block btn-social btn-google">
                            <span class="fa fa-lg fa-google"></span> Aanmelden met Google
                          </a>
                        </div>
                    </div>
                  </form>


                </div>
            </div>
        </div>
    </div>
</div>
@endsection
