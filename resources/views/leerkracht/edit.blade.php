@extends('layouts.master');

@section('content')
<form class="form" action="/leerkracht/{{$leerkracht->id}}" method="post">
  <input name="_method" type="hidden" value="PUT">

  @csrf
  <div class="container">
    <div class="row">

      <div class="col badge badge-primary"><h2>{{$leerkracht->naam}}</h2></div>

    </div>
    <div id="accordion">
    @foreach ($beschikbarescholen as $key => $school)

    <div class="card">
      <div class="card-header alert-warning">
        <div class="col">
          <button type="button" class="btn btn-link" data-toggle="collapse" data-target="#collapse{{$school->id}}" aria-expanded="true" aria-controls="collapse{{$school->id}}">
          {{$school->naam}}
        </button>

        </div>
      </div>

      <div id="collapse{{$school->id}}" class="collapse" aria-labelledby="heading{{$school->id}}" data-parent="#accordion">
      <div class="card-body">
        <table class="table table-small table-bordered">
          <tr>
            <th> </th>
            <th class="text-center">MA</th>
            <th class="text-center">DI</th>
            <th class="text-center">WO</th>
            <th class="text-center">DO</th>
            <th class="text-center">VR</th>
          </tr>
          <tr>
            <td>VM</td>
            <td class="text-center  btn-info"><input class="form-check-input" type="radio" name="MA_VM" value="{{$school->id}}"></td>
            <td class="text-center  btn-info"><input class="form-check-input" type="radio" name="DI_VM" value="{{$school->id}}"></td>
            <td class="text-center  btn-info"><input class="form-check-input" type="radio" name="WO_VM" value="{{$school->id}}"></td>
            <td class="text-center  btn-info"><input class="form-check-input" type="radio" name="DO_VM" value="{{$school->id}}"></td>
            <td class="text-center  btn-info"><input class="form-check-input" type="radio" name="VR_VM" value="{{$school->id}}"></td>
          </tr>
          <tr>
            <td>NM</td>
            <td class="text-center  btn-info"><input class="form-check-input" type="radio" name="MA_NM" value="{{$school->id}}"></td>
            <td class="text-center  btn-info"><input class="form-check-input" type="radio" name="DI_NM" value="{{$school->id}}"></td>
            <td class="text-center  btn-info"><input class="form-check-input" type="radio" name="WO_NM" value="{{$school->id}}" disabled></td></td>
            <td class="text-center  btn-info"><input class="form-check-input" type="radio" name="DO_NM" value="{{$school->id}}"></td>
            <td class="text-center  btn-info"><input class="form-check-input" type="radio" name="VR_NM" value="{{$school->id}}"></td>
          </tr>
        </table>
      </div>
    </div>
    </div>

    @endforeach
  </div>
  <div class="form-row">
    <div class="col">
      <a class="btn btn-secondary btn-close" href="{{url(URL::previous())}}">Annuleer</a>
    </div>
    <div class="col">
      <button id="mysubmit" type="submit" class="btn btn-primary float-md-right">Bewaar</button>
    </div>
  </div>
  </div>
</form>
@endsection
