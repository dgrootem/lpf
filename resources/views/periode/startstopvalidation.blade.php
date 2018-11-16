@section('page-specific-scripts')
<script type="text/javascript">

  var form  = document.getElementById('periodeform');
  var start = $("#start");
  var startDagDeel = $("#startDagDeel");
  var stop = $("#stop");
  var stopDagDeel = $("#stopDagDeel");
  var aantal_uren_van_titularis = $("#aantal_uren_van_titularis");

  //var start = document.getElementById('start');
  //var stop =  document.getElementById('stop');
  var error = document.getElementById('dateRangeError');
  var urenMessage = document.getElementById('berekenUren');

$(document).ready(function(){

  function calculateUren(){
    return $.ajax({
      url: "{{ url('/periodes/calculateAantalDagdelen') }}",
      method: 'post',
      data: {
         _token : '{{ csrf_token() }}',
         start: start.val(),
         startDagDeel: startDagDeel.val(),
         stop: stop.val(),
         stopDagDeel: stopDagDeel.val(),
         leerkracht_id: {{$periode->leerkracht->id}},
         aantal_uren_van_titularis: aantal_uren_van_titularis.val(),
         school_id : {{$periode->school_id}},
         status_id : getStatus(),
         periode_id : {{$periode->id or -1}}
      },
      success: function(result){
         console.log(result);
      },
      failure: function(result){
        console.log(result);
      }
    });
  }

  function getStartWeekschemaNr(){

    return $.ajax({
      url: "{{ url('/periodes/startWeekschemaNr') }}",
      method: 'post',
      data: {
         _token : '{{ csrf_token() }}',
         leerkracht_id: {{$periode->leerkracht->id}},
         datestart: start.val(),
      },
      success: function(result){
         console.log(result);
      },
      failure: function(result){
        console.log(result);
      }
    });
  }

  function getOpdrachtBreukData(school_id){

    return $.ajax({
      url: "{{ url('/periodes/getOpdrachtBreuk') }}",
      method: 'post',
      data: {
         _token : '{{ csrf_token() }}',
         leerkracht_id: {{$periode->leerkracht->id}},
         school_id: school_id,
         datestart: start.val(),
      },
      success: function(result){
         console.log(result);
      },
      failure: function(result){
        console.log(result);
      }
    });
  }

  function checkConflictingDays(periode_id){

    return $.ajax({
      url: "{{ url('/periodes/getConflictingDays') }}",
      method: 'post',
      data: {
         _token : '{{ csrf_token() }}',
         leerkracht_id: {{$periode->leerkracht->id}},
         periode_id: 3,
         datestart: start.val(),
         datestop: stop.val()
      },
      success: function(result){
        console.log(result.conflictdagen);
         console.log(result);
      },
      failure: function(result){
        console.log('kaka');
        console.log(result);
      }
    });
  }


  function checkForConflict(periode_id){

    return $.ajax({
      url: "{{ url('/periodes/checkForConflict') }}",
      method: 'post',
      data: {
         _token : '{{ csrf_token() }}',
         datestart: start.val(),
         datestop: stop.val(),
         leerkracht_id: {{$periode->leerkracht->id}},
         periode_id: periode_id
      },
      success: function(result){
         console.log(result);
      },
      failure: function(result){
        console.log(result);
      }
    });
  }

  function setError(text){
    error.innerHTML = text;
    error.className = "error text-danger";
    $('#mysubmit').attr("disabled", "disabled");
  }

  function clearError(){
    error.innerHTML = "";
    error.className = "error";
    enableSubmit();
  }

  function enableSubmit(){
    $('#mysubmit').removeAttr("disabled");
  }

  function setUren(aantal){
    urenMessage.className = "error text-info";
    urenMessage.innerHTML ="Aantal uren voor deze vervanging: "+aantal;
  }

  function clearUren(){
    urenMessage.className = "error";
    urenMessage.innerHTML ="";
  }

  function getStatus(){
    var a = parseInt($("input[name='status_id']:checked").val());
    return a;
  }

  function setConflictingDays(data){
    $(".dagdeel").prop('disabled', false);
    $(".cannotbeused").prop('disabled', true);

    data.conflictdagen.forEach(dag => {
      $("input[name=Week"+dag.volgorde+"_"+dag.naam.toUpperCase()+"_"+dag.deel+"]").prop('disabled', true);
    });
  }

  $("#schoolselector").on('change',function(){
    //alert(this.value);
    setOpdrachtBreuk(this.value);
  })

  $("#mysubmit").on('submit',function(e){

    var aantal = $(".dagdeel:checked").length;
    if (aantal==0){
      alert('Kies minstens 1 dagdeel !');
      e.preventDefault();
      return false;
    }
    else {
      return true;
    }

  });


  function setOpdrachtBreuk(value){
    getOpdrachtBreukData(value).done(function(data){
      $("#aantal_uren_van_titularis").val(data.teller);
      $("#aantal_uren_van_titularis").attr('max',data.noemer);
      $("#opdrachtbreuk-noemer").val("/"+data.noemer);
    });
  }

  function addValueCheck(element){
    element.on('change',function(){
      if ((!start[0].validity.valid) ||
          (!stop[0].validity.valid)  ||
          (stop.val() < start.val()))
      {

        stop.addClass("is-invalid");
        start.addClass("is-invalid");
        setError("Stopdatum mag niet voor startdatum vallen");
        return;
      }
      else {
        stop.removeClass("is-invalid");
        start.removeClass("is-invalid");
        clearError();
      }/*
      checkForConflict({{$periode->id}}).done(function(data){
        if (data.result!=null){
          element.addClass("is-invalid");
          setError(data.result);
          return;
        }
        else {
          element.removeClass("is-invalid")
          clearError();

            calculateUren().done(function(data){
              if (data.result!=null) {
                 clearUren();
                 setError(data.result);
                 enableSubmit();
              }
              else
                setUren(data.uren);

            });

        }
      });*/
      getStartWeekschemaNr({{$periode->id}}).done(function(data){
        $("#weekschemaVoorStart").html(data.volgorde);
      });
      checkConflictingDays({{$periode->id}}).done(function(data){
        setConflictingDays(data);
      })
    });
  }

  addValueCheck(start);
  addValueCheck(stop);

  checkConflictingDays({{$periode->id}}).done(function(data){
    setConflictingDays(data);
  });

  setOpdrachtBreuk($("#schoolselector").val());

  $(".delete").on("click", function(e){
      return confirm("U gaat deze periode verwjderen. Bent u zeker?");
    });

});

</script>
@endsection
