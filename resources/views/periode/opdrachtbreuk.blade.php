<!-- <div class="form-row"> -->
<div class="form-group col-md-6">
  <div class="card">
    <h5 class="card-header">Opdracht</h5>
  <!-- <div class="col"> -->
    <div class="card-body">
      <p class="card-text">
        <div class="form-row">
          <div class="col">
            <label for="ambt">School</label>
          </div>
          <div class="col">
            {{ Form::select('school_id',$scholenlijst,$periode->school_id, ['class' => 'form-control'])}}
          </div>
        </div>
      </p>
      <p class="card-text">
        <div class="form-row">
          <div class="col-6">
            <label for="opdrachtbreuk">Opdrachtbreuk</label>
          </div>
          <div class="col-3">
            <input type="number" min="1" max="{{ $periode->school->school_type->noemer }}"
              value="{{ $periode->aantal_uren_van_titularis }}"
              class="form-control"
              name="aantal_uren_van_titularis"
              id="aantal_uren_van_titularis"
              aria-describedby="Opdrachtbreuk van te vervangen leerkrecht"
              placeholder="uren te vervangen" required>
            </div>
            <div class="col-3">
            <input type="text" class="form-control" readonly value="/ {{ $periode->school->school_type->noemer }}">
          </div>
        </div>
      </p>
      <p class="card-text">
        <div class="form-row">
          <div class="col">
            <label for="ambt">Ambt</label>
          </div>
          <div class="col">
            {{ Form::select('ambt',$ambts,$periode->ambt, ['class' => 'form-control'])}}
          </div>
        </div>
      </p>
  </div>
  </div>
</div>
