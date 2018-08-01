<div class="form-row">
  <div class="col">
  <label for="opdrachtbreuk">Opdrachtbreuk</label>
  </div>
  <div class="col">
  <input type="number" min="1" max="{{ $periode->school->school_type->noemer }}"
    value="{{ $periode->aantal_uren_van_titularis }}"
    class="form-control"
    name="aantal_uren_van_titularis"
    id="aantal_uren_van_titularis"
    aria-describedby="Opdrachtbreuk van te vervangen leerkrecht"
    placeholder="uren te vervangen" required>
    </div>
    <div class="col">
  <input type="text" class="form-control" readonly value="/ {{ $periode->school->school_type->noemer }}">
  </div>
</div>
