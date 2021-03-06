@extends('layouts.master');

@section('stylesheets')
  <link rel="stylesheet" href="{{asset('css/weekschema.css')}}">
@endsection

@section('content')
<form class="form" action="{{url('/leerkracht/'.$leerkracht->id)}}" method="post">
  <input name="_method" type="hidden" value="PUT">


  @csrf
  <div class="container">
    <div class="row">
      @if ($allowChanges)
      <button type="button" class="btn btn-success" id="addWeekschema">Nieuw weekschema</button>
      @endif
      <div class="col badge badge-primary btn-block"><h2>{{$leerkracht->naam}}</h2></div>
    </div>
    <div class="row">
      <div class="col-6">
        <div class="container">
          <div class="row">
            <div class="col">
              <label class="label label-primary" for="opmerking">Ambt</label>
            </div>
            <div class="col">
              <input class="form-control" type="text" name="ambt" value="{{$leerkracht->ambt->naam}}" readonly>
            </div>
          </div>
          <div class="row">
            <div class="col">
              <div class="container">
                <div class="row">
                  <label class="label label-default" for="wilook">Wil ook</label>
                </div>
                <div class="row">
                  <textarea name="wilook" rows="3" class="form-control" style="min-width: 100%">{{$leerkracht->wilook}}</textarea>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-6">
        <div class="container">
          <div class="row">
            <label class="label label-default" for="opmerking">Opmerking</label>
          </div>
          <div class="row">
            <textarea name="opmerking" rows="4" class="form-control" style="min-width: 100%">{{$leerkracht->opmerking}}</textarea>
          </div>
        </div>
      </div>
    </div>

    @if (!$allowChanges)
      <div class="row bg-warning">
        <strong>OPGELET: Er zijn periodes gekoppeld aan deze leerkracht.<br>U kan dit schema niet wijzigen.</strong>
      </div>
     @endif
    <div class="row">
      <div id="accordion">
        @foreach($leerkracht->aanstellingen as $aanstelling)
          @foreach($aanstelling->weekschemas as $weekschema)
            @include('leerkracht.weekschema')
          @endforeach
        @endforeach
      </div>
    </div>


  <div class="form-row">
    {{-- <div class="col"> --}}
      <a class="btn btn-secondary btn-close" href="{{url(URL::previous())}}">Annuleer</a>
    {{-- </div>
    <div class="col"> --}}
      <button id="mysubmit" type="submit" class="btn btn-primary loat-md-right">Bewaar</button>
    {{-- </div> --}}
  </div>
  </div>
</form>
@endsection



@section('page-specific-scripts')
<script type="text/javascript">
$(document).ready(function () {
  $('#addWeekschema').on('click',function(){
    var count = $("#accordion").children().length;
    var original = $('#weekschema1');
    var myclone =  original.clone();

    count++;

    original.attr('id','weekschema'+count);
    myclone.prependTo("#accordion");

    $("#weekschema" + count + " > .card-header").attr('id','heading'+count);
    $("#weekschema" + count + " > .card-header >h5 > button").attr('id','toggleButton'+count);

    $("#weekschema" + count + " > #collapse1").attr('aria-labelledby','heading'+count);
    $("#weekschema" + count + " > #collapse1").attr('id','collapse'+count);


    $("#toggleButton"+count).html('Weekschema week '+count);
    $("#toggleButton"+count).attr('data-target','#collapse'+count);
    $("#toggleButton"+count).attr('aria-controls','collapse'+count);

    //$("#collapse "+count+" > div > div:nth-child(1) > div > div.col-9 > div > div > div.col > div:nth-child(3) > div:nth-child(3) ")

    var items = $("#collapse"+count).find(".dagdeel");
    //var items = document.getElementsByClassName('dagdeel');
    items.each(function (k,i){ $(this).attr('name',$( this ).attr('name').replace('Week1','Week'+count));});

    var a =$("#accordion").children().sort(function(a,b){if (a.id > b.id) return 1; else return -1;});
    $("#accordion").append(a);

  });
});
</script>
@endsection
