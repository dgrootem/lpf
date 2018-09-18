{{-- <div class="form-group col-md-6"> --}}
  <div class="card">
    @foreach ($periode->weekschemas as $weekschema)
    <h5 class="card-header">Weekschema {{$weekschema->volgorde}}</h5>
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
              <div class="dlk-radio btn-group d-flex flex-row bd-highlight mb-0">
                @foreach(PCont::voormiddagen($weekschema) as $periodedagdeel)
                <div class="p-2 bd-highlight flex-fill">
                  <label class="btn mycontainer">
                    <input type="checkbox" value="1" name="Week{{$weekschema->volgorde}}_{{strtoupper($periodedagdeel->dagdeel->naam)}}_VM"
                    @if($periodedagdeel->status===\App\DagDeel::UNAVAILABLE) disabled @endif
                    @if ($periodedagdeel->status===\App\DagDeel::BOOKED) checked @endif
                    class="form-control voormiddag dagdeel" >
                    <span class="mycheckmark"></span>
                  </label>
                </div>
                @endforeach
              </div>
              <div class="dlk-radio btn-group d-flex flex-row bd-highlight mb-0">
                @foreach(PCont::namiddagen($weekschema) as $periodedagdeel)
                <div class="p-2 bd-highlight flex-fill @if($periodedagdeel->dagdeel->naam === 'wo') invisible @endif">
                  <label class="btn mycontainer">
                    <input type="checkbox" value="1" name="Week{{$weekschema->volgorde}}_{{strtoupper($periodedagdeel->dagdeel->naam)}}_NM"
                    @if($periodedagdeel->status===\App\DagDeel::UNAVAILABLE) disabled @endif
                    @if ($periodedagdeel->status===\App\DagDeel::BOOKED) checked @endif
                    class="form-control voormiddag dagdeel" >
                    <span class="mycheckmark"></span>
                  </label>
                </div>
                @endforeach
              </div>
              
              {{-- <div class="d-flex flex-row bd-highlight mb-0">
                <div class="p-2 bd-highlight flex-fill"><input type="checkbox" value="1" name="MA_VM" @if($periode->MA_VM==1) checked @endif @if(PCont::canBeChosen($periode,'MA_VM')) disabled @endif></div>
                <div class="p-2 bd-highlight flex-fill"><input type="checkbox" value="1" name="DI_VM" @if($periode->DI_VM==1) checked @endif @if(PCont::canBeChosen($periode,'DI_VM')) disabled @endif></div>
                <div class="p-2 bd-highlight flex-fill"><input type="checkbox" value="1" name="WO_VM" @if($periode->WO_VM==1) checked @endif @if(PCont::canBeChosen($periode,'WO_VM')) disabled @endif></div>
                <div class="p-2 bd-highlight flex-fill"><input type="checkbox" value="1" name="DO_VM" @if($periode->DO_VM==1) checked @endif @if(PCont::canBeChosen($periode,'DO_VM')) disabled @endif></div>
                <div class="p-2 bd-highlight flex-fill"><input type="checkbox" value="1" name="VR_VM" @if($periode->VR_VM==1) checked @endif @if(PCont::canBeChosen($periode,'VR_VM')) disabled @endif></div>
              </div>
              <div class="d-flex flex-row bd-highlight mb-0">
                <div class="p-2 bd-highlight flex-fill"><input type="checkbox" value="1" name="MA_NM" @if($periode->MA_NM==1) checked @endif @if(PCont::canBeChosen($periode,'MA_NM')) disabled @endif></div>
                <div class="p-2 bd-highlight flex-fill"><input type="checkbox" value="1" name="DI_NM" @if($periode->DI_NM==1) checked @endif @if(PCont::canBeChosen($periode,'DI_NM')) disabled @endif></div>
                <div class="p-2 bd-highlight flex-fill"><input type="checkbox" value="1" name="WO_NM" disabled class="invisible"></div>
                <div class="p-2 bd-highlight flex-fill"><input type="checkbox" value="1" name="DO_NM" @if($periode->DO_NM==1) checked @endif @if(PCont::canBeChosen($periode,'DO_NM')) disabled @endif></div>
                <div class="p-2 bd-highlight flex-fill"><input type="checkbox" value="1" name="VR_NM" @if($periode->VR_NM==1) checked @endif @if(PCont::canBeChosen($periode,'VR_NM')) disabled @endif></div>
              </div> --}}
            </div>
          </div>
        </div>
      </p>
    </div>
    @endforeach
  </div>
{{-- </div> --}}
{{-- <div class="form-group col-md-6"> --}}

{{-- </div> --}}
