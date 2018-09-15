<div class="form-group col-md-6">
  <div class="card">
    <h5 class="card-header">Weekschema</h5>
  <!-- <div class="col"> -->
    <div class="card-body">
      <p class="card-text">
        <div class="container">
          <div class="row">
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
              <div class="d-flex flex-row bd-highlight mb-0">
                <div class="p-2 bd-highlight flex-fill"><input type="checkbox" value="1" name="MA_VM" @if($periode->MA_VM==1) checked @endif></div>
                <div class="p-2 bd-highlight flex-fill"><input type="checkbox" value="1" name="DI_VM" @if($periode->DI_VM==1) checked @endif></div>
                <div class="p-2 bd-highlight flex-fill"><input type="checkbox" value="1" name="WO_VM" @if($periode->WO_VM==1) checked @endif></div>
                <div class="p-2 bd-highlight flex-fill"><input type="checkbox" value="1" name="DO_VM" @if($periode->DO_VM==1) checked @endif></div>
                <div class="p-2 bd-highlight flex-fill"><input type="checkbox" value="1" name="VR_VM" @if($periode->VR_VM==1) checked @endif></div>
              </div>
              <div class="d-flex flex-row bd-highlight mb-0">
                <div class="p-2 bd-highlight flex-fill"><input type="checkbox" value="1" name="MA_NM" @if($periode->MA_NM==1) checked @endif></div>
                <div class="p-2 bd-highlight flex-fill"><input type="checkbox" value="1" name="DI_NM" @if($periode->DI_NM==1) checked @endif></div>
                <div class="p-2 bd-highlight flex-fill"><input type="checkbox" value="1" name="WO_NM" disabled></div>
                <div class="p-2 bd-highlight flex-fill"><input type="checkbox" value="1" name="DO_NM" @if($periode->DO_NM==1) checked @endif></div>
                <div class="p-2 bd-highlight flex-fill"><input type="checkbox" value="1" name="VR_NM" @if($periode->VR_NM==1) checked @endif></div>
              </div>
            </div>
          </div>
        </div>
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
        <textarea class="form-control" id="opmerking" value="opmerking" rows="3">{{$periode->opmerking}}</textarea>
      </p>
    </div>
  </div>
</div>
