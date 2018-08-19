@section('page-specific-scripts')
<script type="text/javascript">

  var form  = document.getElementById('periodeform');
  var start = $("#start");
  var stop = $("#stop");

  //var start = document.getElementById('start');
  //var stop =  document.getElementById('stop');
  var error = document.getElementById('dateRangeError');

$(document).ready(function(){


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

  function disableSubmit(){
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



  function addValueCheck(element){
    element.on('change',function(){
      if ((!start[0].validity.valid) ||
          (!stop[0].validity.valid)  ||
          (stop.val() < start.val()))
      {

        stop.addClass("is-invalid");
        start.addClass("is-invalid");
        // If the field is not valid, we display a custom
        // error message.
        error.innerHTML = "Stopdatum mag niet voor startdatum vallen";
        error.className = "error text-danger";
        disableSubmit();
        return;
      }
      else {
        stop.removeClass("is-invalid");
        start.removeClass("is-invalid");
        clearError();
      }
      checkForConflict({{$periode->id}}).done(function(data){
        if (data.result!=null){
          element.addClass("is-invalid");
          error.innerHTML = data.result;
          error.className = "error text-danger";
          disableSubmit();
        }
        else {
          element.removeClass("is-invalid")
          clearError();

        }
      });
    });
  }

  addValueCheck(start);
  addValueCheck(stop);

  $(".delete").on("click", function(e){
      return confirm("U gaat deze periode verwjderen. Bent u zeker?");
    });

});

</script>
@endsection
