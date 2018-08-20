@extends('layouts.master')

@section('content')
<div class="container">
  <div class="row">
    <div class="col">

      @yield('form_open')

      {{ Form::hidden('school_id', $periode->school_id) }}
      {{ Form::hidden('leerkracht_id', $periode->leerkracht_id) }}
      <input type="hidden" name="heleDag" value="1">

      <!-- @include('periode.basis_top') -->
      <h2 class="text-center"><span class="badge badge-primary">Plan een periode voor
        {{Form::label('leerkracht',$periode->leerkracht->naam, ['class' => 'badge badge-light']) }}
        in {{ Form::label('school', $periode->school->naam, ['class' => 'badge badge-light']) }}
      </span></h2>

      <br>
      <div class="form-row">
        @include('periode.startstop')
        @include('periode.opdrachtbreuk')
      </div>
      <div class="form-row">
        @include('periode.type_voor_nieuwe_periode')
      </div>
      <div class="form-row">
        <div class="col">
          <a class="btn btn-secondary btn-close" href="{{url('/')}}">Annuleer</a>
        </div>
        <div class="col">
          <button id="mysubmit" type="submit" class="btn btn-primary float-md-right">Bewaar</button>
        </div>
      </div>

      {{ Form::close() }}

      @yield('delete_button')
    </div>
  </div>
</div>
@endsection


@include('periode.startstopvalidation')
