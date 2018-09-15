@extends('layouts.master');

@section('stylesheets')
  <link rel="stylesheet" href="{{asset('css/weekschema.css')}}">
@endsection

@section('content')
<form class="form" action="/leerkracht/{{$leerkracht->id}}" method="post">
  <input name="_method" type="hidden" value="PUT">


  @csrf
  <div class="container">
    <div class="row">
      <div class="col badge badge-primary btn-block"><h2>{{$leerkracht->naam}}</h2></div>
    </div>
    <div class="row">
      <table class="table">
        <tbody>
          @foreach ($beschikbarescholen as $key => $school)
          <tr>
            <div class="container">
              <div class="row border align-items-center">
                <div class="col-3 ">
                  {{$school->naam}}
                </div>
                <div class="col-9">
                  <div class="container">
                    <div class="row ">
                      <div class="col-2">
                        <div class="p-2 bd-highlight flex-fill invisible">BB</div>
                        <div class="p-2 bd-highlight flex-fill">VM</div>
                        <div class="p-2 bd-highlight flex-fill">NM</div>
                      </div>
                      <div class="col">
                        <div class="d-flex flex-row bd-highlight mb-0 ">
                          <div class="p-2 bd-highlight flex-fill">MA</div>
                          <div class="p-2 bd-highlight flex-fill">DI</div>
                          <div class="p-2 bd-highlight flex-fill">WO</div>
                          <div class="p-2 bd-highlight flex-fill">DO</div>
                          <div class="p-2 bd-highlight flex-fill">VR</div>
                        </div>
                        <div class="dlk-radio btn-group d-flex flex-row bd-highlight mb-0">
                          <div class="p-2 bd-highlight flex-fill border">
                            <label class="btn btn-primary btn-block">
                              <input type="radio" value="{{$school->id}}" name="MA_VM" @if($leerkracht->MA_VM==$school->id) checked @endif class="form-control" >
                              <i class="fa fa-check fa-large glyphicon glyphicon-ok"></i>
                            </label>
                          </div>
                          <div class="p-2 bd-highlight flex-fill">
                            <label class="btn btn-primary btn-block">
                              <input type="radio" value="{{$school->id}}" name="DI_VM" @if($leerkracht->DI_VM==$school->id) checked @endif >
                              <i class="fa fa-check glyphicon glyphicon-ok"></i>
                            </label>
                          </div>
                          <div class="p-2 bd-highlight flex-fill">
                            <label class="btn btn-primary btn-block">
                              <input type="radio" value="{{$school->id}}" name="WO_VM" @if($leerkracht->WO_VM==$school->id) checked @endif >
                              <i class="fa fa-check glyphicon glyphicon-ok"></i>
                            </label>
                          </div>
                          <div class="p-2 bd-highlight flex-fill">
                            <label class="btn btn-primary btn-block">
                              <input type="radio" value="{{$school->id}}" name="DO_VM" @if($leerkracht->DO_VM==$school->id) checked @endif >
                              <i class="fa fa-check glyphicon glyphicon-ok"></i>
                            </label>
                          </div>
                          <div class="p-2 bd-highlight flex-fill">
                            <label class="btn btn-primary btn-block">
                              <input type="radio" value="{{$school->id}}" name="VR_VM" @if($leerkracht->VR_VM==$school->id) checked @endif >
                              <i class="fa fa-check glyphicon glyphicon-ok"></i>
                            </label>
                          </div>
                        </div>
                        <div class="dlk-radio btn-group d-flex flex-row bd-highlight mb-0">
                          <div class="p-2 bd-highlight flex-fill">
                            <label class="btn btn-primary btn-block">
                              <input type="radio" value="{{$school->id}}" name="MA_NM" @if($leerkracht->MA_NM==$school->id) checked @endif >
                              <i class="fa fa-check glyphicon glyphicon-ok"></i>
                            </label>
                          </div>
                          <div class="p-2 bd-highlight flex-fill">
                            <label class="btn btn-primary btn-block">
                              <input type="radio" value="{{$school->id}}" name="DI_NM" @if($leerkracht->DI_NM==$school->id) checked @endif >
                              <i class="fa fa-check glyphicon glyphicon-ok"></i>
                            </label>
                          </div>
                          <div class="p-2 bd-highlight flex-fill">
                            <label class="btn btn-primary btn-block invisible">
                              <input type="radio" value="0" name="WO_NM" disabled class="invisible">
                              <i class="fa fa-check glyphicon glyphicon-ok"></i>
                            </label>
                          </div>
                          <div class="p-2 bd-highlight flex-fill">
                            <label class="btn btn-primary btn-block">
                              <input type="radio" value="{{$school->id}}" name="DO_NM" @if($leerkracht->DO_NM==$school->id) checked @endif >
                              <i class="fa fa-check glyphicon glyphicon-ok"></i>
                            </label>
                          </div>
                          <div class="p-2 bd-highlight flex-fill">
                            <label class="btn btn-primary btn-block">
                              <input type="radio" value="{{$school->id}}" name="VR_NM" @if($leerkracht->VR_NM==$school->id) checked @endif >
                              <i class="fa fa-check glyphicon glyphicon-ok"></i>
                            </label>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </tr>
        @endforeach
      </tbody>
      </div>
    </div>





  </div>
  <div class="form-row">
    <div class="col">
      <a class="btn btn-secondary btn-close" href="{{url(URL::previous())}}">Annuleer</a>
    </div>
    <div class="col">
      <button id="mysubmit" type="submit" class="btn btn-primary btn-block float-md-right">Bewaar</button>
    </div>
  </div>
  </div>
</form>
@endsection
