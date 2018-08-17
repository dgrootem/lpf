@extends('layouts.master')

@section('content')

<table class="table">
  <tr>
    <th></th>
    @foreach ($leerkrachten as $key => $leerkracht)
    <th><a href="{{url('/periodes/create')}}?leerkracht={{$leerkracht->id }}">{{ $leerkracht->naam}}</a></th>
    @endforeach
  </tr>


  @foreach ($range as $datum => $lijn)
    <tr @if (\Carbon\Carbon::parse($datum)->isWeekend()) class="collapsedrow" @endif>
      <th>{{ \Carbon\Carbon::parse($datum)->formatLocalized("%d-%m") }}</th>
      @foreach ($lijn as $leerkrachtid => $periode)
        <td
          class="{{$periode->status->visualisatie}}
          @if (strcmp($periode->status->visualisatie,'')<>0)
            clickablecell-{{$periode->id}}
          @else
            clickablecell-new
          @endif
          "
          data-leerkracht="{{$leerkrachtid}}"
          data-datum="{{$datum}}"
          title="{{$periode->opmerking}}">
            {{ $periode->status->omschrijving}}
        </td>
      @endforeach
    </tr>
  @endforeach
</table>

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
    //alert( "Handler for .click() on periode {{$periode->id}} called." );
  });
});
</script>


@endsection
