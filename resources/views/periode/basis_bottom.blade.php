<div class="form-group">
  <label for="opmerking">Opmerking</label>
  <textarea class="form-control" id="opmerking" name="opmerking" rows="3">{{$periode->opmerking}}</textarea>
</div>

<div class="form-row">
  {{-- <div class="col"><button type="reset" class="btn btn-secondary">Annuleer</button></div> --}}
  <a class="btn btn-secondary btn-close" href="{{url('/')}}">Annuleer</a>
  <div class="col"><button id="mysubmit" type="submit" class="btn btn-primary float-md-right">Bewaar</button></div>
</div>
