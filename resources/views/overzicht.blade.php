@extends('layouts.master')

@section('content')

<table class="table">
  <tr>
    <th></th>
    @foreach ($leerkrachten as $key => $leerkracht)
    <th><a href="/periodes/create?leerkracht={{$leerkracht->id }}">{{ $leerkracht->naam}}</a></th>
    @endforeach
  </tr>


  @foreach ($range as $datum => $lijn)
    <tr @if (\Carbon\Carbon::parse($datum)->isWeekend()) class="collapsedrow" @endif>
      <th>{{ $datum }}</th>
      @foreach ($lijn as $leerkrachtid => $periode)
        <td
          class="{{$periode->status->visualisatie}}
          @if (strcmp($periode->status->visualisatie,'')<>0)
            clickablecell-{{$periode->id}}
          @endif
          ">
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
    $(".clickablecell-{{$periode->id}}").click(function(){
      window.location.href = "/periodes/{{$periode->id}}/edit";
      //alert( "Handler for .click() on periode {{$periode->id}} called." );
    });
  @endforeach
});
</script>


@endsection
