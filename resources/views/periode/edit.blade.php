@extends('periode.master')

@section('form_open')

  {{ Form::model($periode, array('class' => 'col-md needs-validation',
                              'route' => array('periodes.update', $periode->id),
                              'method' => 'PUT','id' => 'periodeform')) }}

@endsection

@section('delete_button')
  {{ Form::model($periode, array('class' => 'col needs-validation',
                                'route' => array('periodes.destroy', $periode->id),
                                'method' => 'DELETE','id' => 'deleteform')) }}
  <div class="form-row">
    <div class="col text-center">
      <button type="submit" class="btn btn-danger text-center delete" id="deleteButton">VERWIJDER</button>
    </div>
  </div>
  {{ Form::close() }}
@endsection
