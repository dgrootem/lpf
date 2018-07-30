@extends('layouts.master')

@section('content')



<h2>Plan een periode </h2>


  <form method="POST" action="/periodes" class="col-md-6 needs-validation">
    @include('periode.basis_top')
    <hr>
    @include('periode.startstop')
    @include('periode.opdrachtbreuk')
    <hr>
    @include('periode.type_voor_nieuwe_periode')
    <hr>

    @include('periode.basis_bottom')

</form>

@endsection
