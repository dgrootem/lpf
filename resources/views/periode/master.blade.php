@extends('layouts.master')

@section('content')
<div class="container">
  <div class="row">
    <div class="col">
      <div class="container">
        @yield('delete_button')
        <div class="row">


        <div class="col badge badge-primary">
          <h2 class="text-center">
          Plan een periode voor <span class="badge badge-light">{{$periode->leerkracht->naam}}</span> in <span class="badge badge-light">{{$periode->school->naam}}</span>
          </h2>
        </div>



      </div>
      @yield('form_open')
      <input type="hidden" name="school_id" value="{{$periode->school_id or old('school_id')}}">
      <input type="hidden" name="leerkracht_id" value="{{$periode->leerkracht_id or old('leerkracht_id')}}">
      <input type="hidden" name="heleDag" value="1">
      <input type="hidden" name="periode_id" value="{{$periode->id}}">

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


    </div>
  </div>
</div>
@endsection


@include('periode.startstopvalidation')
