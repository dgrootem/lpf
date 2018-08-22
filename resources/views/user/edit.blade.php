@extends('layouts.master');

@section('content')

  <h2>Welkom {{$user->name}}</h2>
  <h3>Duid aan voor welke school u verantwoordelijk bent voor het beheer van de LPF periodes.</h3>

  {{ Form::model($user, array('class' => 'col-md needs-validation',
                              'route' => array('user.update', $user->id),
                              'method' => 'PUT','id' => 'userform')) }}

  @foreach ($beschikbarescholen as $key => $school)
    <div class="form-check">
      <input class="form-check-input"
        type="checkbox"
        value="{{$school->id}}"
        name="checkBoxes[]"
        id="checkBox-{{$school->id}}"
        @if(in_array($school->id,$user->schools->pluck('id')->toArray()))
          checked
        @endif
        >
      <label class="form-check-label" for="checkBox-{{$school->id}}">
        {{$school->naam}} ({{$school->adres}})
      </label>
    </div>
  @endforeach
  <button type="submit" name="button" class="btn btn-primary">Opslaan</button>
</form>

@endsection
