@extends('layouts.master')

@section('content')



<h2>Plan een periode </h2>


  <form method="POST" action="/periodes" class="col-md-6">
    {{ csrf_field() }}
    <input type="hidden" name="school_id" value="{{$school->id}}">
    <input type="hidden" name="leerkracht_id" value="{{$leerkracht->id}}">

    <div class="form-row alert-primary ">
      <div class="col strong"><label >Leerkracht</label></div>
      <div class="col"><label >{{ $leerkracht->naam }}</label></div>
    </div>
    <div class="form-row alert-secondary">
      <div class="col strong"><label>School</label></div>
      <div class="col"><label>{{$school->naam}}</label>  </div>
    </div>
<hr>
  <div class="form-row">
    <div class="form-group col-md-6">
      <label for"start">Start</label>
      <input type="date" name="start" max="2019-06-30"
          min="2018-08-01" value="{{ date('Y-m-d') }}"
          id="start"
          data-date-format="dddd DD MM YYYY"
          class="form-control">
    </div>
    <div class="form-group col-md-6">
      <label for"stop">Einde</label>
      <input type="date" name="stop" min="2018-08-01"
          id="einde"
          max="2019-06-30" value="{{ date('Y-m-d') }}"
          data-date-format="dddd DD MM YYYY"
          class="form-control">
    </div>
  </div>


    <div class="form-row">
      <div class="col">
      <label for="opdrachtbreuk">Opdrachtbreuk</label>
      </div>
      <div class="col">
      <input type="number" min="1" max="{{ $school->school_type->noemer }}"
        value="{{ $school->school_type->noemer }}"
        class="form-control"
        name="aantal_uren_van_titularis"
        id="aantal_uren_van_titularis" aria-describedby="Opdrachtbreuk van te vervangen leerkrecht" placeholder="uren te vervangen">
        </div>
        <div class="col">
      <input type="text" class="form-control" readonly value="/ {{ $school->school_type->noemer }}">
      </div>
    </div>

<hr>

    <div class="form-group">
      <label for="type_vervanging">Type periode</label>
      @foreach ($statuses as $key => $status)
        <div class="form-check {{ $status->visualisatie }}">
          <input class="form-check-input " type="radio" name="type_vervanging" id="{{ 'type_vervanging'.$status->omschrijving }}" value="{{$status->id}}">
          <label class="form-check-label " for="type_vervanging_RV">
            {{ $status->omschrijving }}
          </label>
        </div>
      @endforeach
    </div>

    <hr>
    <div class="form-group">
      <label for="opmerking">Opmerking</label>
      <textarea class="form-control" id="opmerking" rows="3"></textarea>
    </div>

    <div class="form-row">
      <div class="col"><button type="cancel" class="btn btn-secondary">Annuleer</button></div>
      <div class="col"><button type="submit" class="btn btn-primary float-md-right">Bewaar</button></div>
    </div>

</form>

@endsection
