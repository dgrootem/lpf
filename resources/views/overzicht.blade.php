@extends('layouts.master')

@section('content')
<div class="container-fluid">
  <table class="table">
    <thead>
    <tr>
      <th>
        <a href="{{url('/overzichten/'.$startOfRange->copy()->addDays(-env('NBDAYS_IN_OVERZICHT'))->format('Y-m-d'))}}">
          <span class="fa fa-2x fa-angle-up"></span>
        </a>
        <a href="{{url('/overzichten/'.$startOfRange->copy()->addDays(env('NBDAYS_IN_OVERZICHT'))->format('Y-m-d'))}}">
          <span class="fa fa-2x fa-angle-down"></span>
        </a>
      </th>
      @foreach ($leerkrachten as $key => $leerkracht)
      <th>
        <a href="{{url('/periodes/create')}}?leerkracht={{$leerkracht->id }}">{{ $leerkracht->naam}}</a>
        <a href="{{url('/leerkracht/'.$leerkracht->id.'/edit')}}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
      </th>
      @endforeach
    </tr>
  </thead>

    @foreach ($dateRange as $datum => $lijn)
    <tbody>
      <tr height="50%" @if (\Carbon\Carbon::parse($datum)->isWeekend())  class="collapsedrow" @endif>
        <th rowspan="2" scope="rowgroup">{{ \Carbon\Carbon::parse($datum)->formatLocalized("%d-%m") }}</th>
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
                          +"&datum="+$(this).data("datum");

  });
});
</script>



@endsection
