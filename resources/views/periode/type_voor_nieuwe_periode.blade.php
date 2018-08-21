<div class="form-group col-md-6">
  <div class="card">
    <h5 class="card-header">Type periode</h5>
  <!-- <div class="col"> -->
    <div class="card-body">
      <p class="card-text">
        @foreach ($statuses as $key => $status)
          <div class="form-check {{ $status->visualisatie }}">
            <input class="form-check-input "
                   type="radio" required name="status_id"
                   id="{{ 'type_vervanging'.$status->omschrijving }}"
                   value="{{$status->id}}"
                   @if ($periode->status_id == $status->id) checked @endif
                   >
            <label class="form-check-label" for="{{ 'type_vervanging'.$status->omschrijving }}">
              {{ $status->omschrijving }}
            </label>
          </input>
          </div>
        @endforeach
      </p>
    </div>
  </div>
</div>
<div class="form-group col-md-6">
  <div class="card">
    <h5 class="card-header">Opmerking</h5>
  <!-- <div class="col"> -->
    <div class="card-body">
      <p class="card-text">
        <textarea class="form-control" id="opmerking" name="opmerking" rows="3">{{$periode->opmerking}}</textarea>
      </p>
    </div>
  </div>
</div>
