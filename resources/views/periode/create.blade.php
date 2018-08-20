@extends('periode.master')

@section('form_open')

{{ Form::model($periode, array('class' => 'col-md needs-validation',
    'route' => 'periodes.store','id' => 'periodeform' )) }}

@endsection

@section('delete_button')

@endsection
