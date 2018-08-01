@extends('layouts.master')

@section('content')



<h2>Plan een periode </h2>

  {{-- {{ Form::open(array('url' => 'periodes','class' => 'col-md-6 needs-validation')) }} --}}
  {{ Form::model($periode, array('class' => 'col-md-6 needs-validation','route' => array('periodes.update', $periode->id), 'method' => 'PUT')) }}
  {{-- <form method="POST" action="/periodes" class="col-md-6 needs-validation"> --}}

    {{-- {!! method_field('patch') !!} --}}
    @include('periode.basis_top')
    <hr>
    @include('periode.startstop')
    @include('periode.opdrachtbreuk')
    <hr>
    @include('periode.type_voor_nieuwe_periode')
    <hr>

    @include('periode.basis_bottom')

    {{ Form::close() }}
{{-- </form> --}}

@endsection
