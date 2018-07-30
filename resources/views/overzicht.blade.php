@extends('layouts.master')

@section('content')

<table class="table">
  <tr>
    <th></th>
    @foreach ($leerkrachten as $key => $leerkracht)
    <th><a href="/periodes/create/{{$leerkracht->id }}">{{ $leerkracht->naam}}</a></th>
    @endforeach
  </tr>


  @foreach ($range as $datum => $lijn)
    <tr>
      <th>{{ $datum }}</th>
      @foreach ($lijn as $leerkrachtid => $status)
        <td class="{{$status->visualisatie}}">{{ $status->omschrijving}} </td>
      @endforeach
    </tr>
  @endforeach
</table>


@endsection
