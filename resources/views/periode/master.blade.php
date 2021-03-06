@extends('layouts.master')

@section('stylesheets')
  <link rel="stylesheet" href="{{asset('css/weekschema.css')}}">
@endsection

@section('content')
<div class="container">
  <div class="row">
    <div class="col">
      <div class="container">
        @yield('delete_button')
        <div class="row">
          <div class="col badge badge-primary">
            <h2 class="text-center">
            Plan een periode voor <span class="badge badge-light">{{$periode->leerkracht->naam}}</span>
             {{-- in <span class="badge badge-light">{{$periode->school->naam}}</span> --}}
            </h2>
          </div>
        </div>
        @yield('form_open')
        <input type="hidden" name="school_id" value="{{$periode->school_id or old('school_id')}}">
        <input type="hidden" name="leerkracht_id" value="{{$periode->leerkracht_id or old('leerkracht_id')}}">
        <input type="hidden" name="heleDag" value="1">
        <input type="hidden" name="id" value="{{$periode->id}}">
        <input type="hidden" name="status_id" value="2">
        <input type="hidden" name="originating_school_id" value="{{$periode->originating_school_id}}">

        <br>
        <div class="row">
          <div class="container">
            <div class="form-row">
              <div class="col-6">
                @include('periode.startstop')
                @include('periode.opdrachtbreuk')
              </div>
              <div class="col-6">
                @include('periode.type_voor_nieuwe_periode')
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col">
            <div class="card">
              <h5 class="card-header">Opmerking</h5>
            <!-- <div class="col"> -->
              <div class="card-body">
                <p class="card-text">
                  <textarea class="form-control" name="opmerking" id="opmerking" value="opmerking" rows="3">{{$periode->opmerking}}</textarea>
                </p>
              </div>
            </div>
          </div>
        </div>
      {{-- </div> --}}
      <div class="form-row">
        <div class="col">
          <a class="btn btn-secondary btn-close" href="{{url(URL::previous())}}">Annuleer</a>
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
