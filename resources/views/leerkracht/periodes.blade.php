@extends('layouts.master');

@section('stylesheets')

@endsection

@section('content')
<div class="row">
  <div class="col">
    <h2>Periodes voor {{$leerkracht->naam}}</h2>
  </div>
</div>
<table class="table table-striped w-auto">
  <thead>
    <th>Van</th>
    <th>Tot</th>
    <th>Bezetting</th>
  </thead>
  <tbody>
    @foreach ($periodes as $periode)
      <tr>
        <td>{{$periode->start->format('d-m-Y')}}</td>
        <td>{{$periode->stop->format('d-m-Y')}}</td>
        <td>{{$calculatedValues[$periode->id]}} %</td>
      </tr>
    @endforeach
  </tbody>
</table>


@endsection



@section('page-specific-scripts')

@endsection
