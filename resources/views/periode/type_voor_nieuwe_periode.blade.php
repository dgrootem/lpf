<div class="form-group">
  <label for="type_vervanging">Type periode</label>
  @foreach ($statuses as $key => $status)
    <div class="form-check {{ $status->visualisatie }}">
      <input class="form-check-input " type="radio" required name="status_id" id="{{ 'type_vervanging'.$status->omschrijving }}" value="{{$status->id}}">
      <label class="form-check-label " for="status_id">
        {{ $status->omschrijving }}
      </label>
    </div>
  @endforeach
</div>
