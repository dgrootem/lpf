@extends('layouts.master')

@section('content')
<div class="container-fluid">
  <table class="table">
    <thead>
    <tr class="header">
      <th>
        <a href="{{url('/overzichten/'.$startOfRange->copy()->addDays(-env('NBDAYS_IN_OVERZICHT'))->format('Y-m-d'))}}">
          <span class="fa fa-2x fa-angle-up"></span>
        </a>
        <a href="{{url('/overzichten/'.$startOfRange->copy()->addDays(env('NBDAYS_IN_OVERZICHT'))->format('Y-m-d'))}}">
          <span class="fa fa-2x fa-angle-down"></span>
        </a>
      </th>
      @foreach ($leerkrachten as $key => $leerkracht)
      <th id="lkrheader_{{$leerkracht->id}}" title='test'>
        {{-- @if($leerkracht->aanstelling()->weekschemas->count()>0)
        <a href="{{url('/periodes/create')}}?leerkracht={{$leerkracht->id }}">{{ $leerkracht->naam}}</a>
        @else --}}
        {{ $leerkracht->naam}}
        {{-- @endif --}}
        <a href="{{url('/leerkracht/'.$leerkracht->id.'/edit')}}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
        <a href="{{url('/leerkracht/'.$leerkracht->id.'/periodes')}}"><i class="fa fa-bar-chart" aria-hidden="true"></i></a>
      </th>
      @endforeach
    </tr>
  </thead>

    @foreach ($dateRange as $datum => $lijn)
    <tbody>
      <tr height="50%" @if (\Carbon\Carbon::parse($datum)->isWeekend())  class="collapsedrow" @endif>
        <th rowspan="2" scope="rowgroup" @if(\Carbon\Carbon::parse($datum) == \Carbon\Carbon::today()) class="vandaag" @endif>{{ \Carbon\Carbon::parse($datum)->formatLocalized("%d-%m") }}</th>
        @foreach ($lijn['VM'] as $leerkrachtid => $dagDeel)
          @include('overzicht.dagdeel')
        @endforeach
      </tr>
      <tr height="50%" @if (\Carbon\Carbon::parse($datum)->isWeekend()) class="collapsedrow" @endif>
        @foreach ($lijn['NM'] as $leerkrachtid => $dagDeel)
          @include('overzicht.dagdeel')
        @endforeach
      </tr>
    </tbody>
    @endforeach
  </table>
</div>

@foreach ($leerkrachten as $key => $leerkracht)
<div id="popover-content_{{$leerkracht->id}}" class="d-none">
  <div><strong>Ambt:</strong> {{$leerkracht->ambt->naam}}</div>
  @if(( $leerkracht->wilook != null ) && ($leerkracht->wilook !== ''))
    <div><strong>Wil ook:</strong></div>
    <div>{{$leerkracht->wilook}}</div>
  @endif
  @if(( $leerkracht->opmerking != null ) && ($leerkracht->opmerking !== ''))
    <div><strong>Opmerking:</strong></div>
    <div>{{$leerkracht->opmerking}}</div>
  @endif
</div>
@endforeach

@endsection

@section('page-specific-scripts')
<script type="text/javascript">
$(document).ready(function () {
  @foreach ($periodesInRange as $key => $periode)
    $(".clickablecell-{{$periode->id}}").tooltip({show: {delay: 800}, track: true});
    $(".clickablecell-{{$periode->id}}").click(function(){
      window.location.href = "{{url('/periodes')}}/{{$periode->id}}/edit";
      //alert( "Handler for .click() on periode {{$periode->id}} called." );
    });
  @endforeach
  $(".clickablecell-new").click(function(){
    window.location.href = "{{url('/periodes')}}/create?leerkracht="
                          +$(this).data("leerkracht")
                          +"&datum="+$(this).data("datum")
                          +"&originating_school_id="+$(this).data("originating-school");

  });


  @foreach ($leerkrachten as $key => $leerkracht)
  $('#lkrheader_{{$leerkracht->id}}').tooltip({
    html: true,
	content: function() {
          return $("#popover-content_{{$leerkracht->id}}").html();
        }
});

  @endforeach

});
</script>



@endsection
