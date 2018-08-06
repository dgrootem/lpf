@section('page-specific-scripts')
<script type="text/javascript">

  var form  = document.getElementsByTagName('form')[0];
  var start = $("#start");
  var stop = $("#stop");

  //var start = document.getElementById('start');
  //var stop =  document.getElementById('stop');
  var error = document.getElementById('dateRangeError');

$(document).ready(function(){


  function checkForConflict(date,leerkracht_id,periode_id){

    return $.ajax({
      url: "{{ url('/periodes/checkForConflict') }}",
      method: 'post',
      data: {
         _token : '{{ csrf_token() }}',
         date: date,
         leerkracht_id: leerkracht_id,
         periode_id: periode_id
      }/*,
      success: function(result){
         console.log(result);
      },
      failure: function(result){

      }*/
    });
/*
      $.ajax({
         type:'POST',
         url:'/periodes/checkForConflict',
         data:{,date: date,leerkracht_id: leerkracht_id},
         success:function(data){
            return data;
         }

      });
*/
  }

  function addValueCheck(element){
    element.on('change',function(){

      checkForConflict(element.val(),$("input[name=leerkracht_id]").val(),{{$periode->id}}  ).done(function(data){
        if (data.result==false){
          element.addClass("is-invalid");
          error.innerHTML = element.attr('name') + "datum ligt in bestaande periode";
          error.className = "error text-danger";
          $('#mysubmit').attr("disabled", "disabled");
        }
        else {
          element.removeClass("is-invalid")
          error.innerHTML = "";
          error.className = "error";
          $('#mysubmit').removeAttr("disabled");
        }
      });
    });
  }

  addValueCheck(start);
  addValueCheck(stop);

  form.addEventListener("submit", function (event) {
  // Each time the user tries to send the data, we check
  // if the email field is valid.
  if ((!start.validity.valid) ||
      (!stop.validity.valid)  ||
      (stop.value < start.value))
    {

      stop.className  ="form-control is-invalid";
      start.className ="form-control is-invalid";
    // If the field is not valid, we display a custom
    // error message.
    error.innerHTML = "Stopdatum mag niet voor startdatum vallen";
    error.className = "error text-danger";
    // And we prevent the form from being sent by canceling the event
    event.preventDefault();
  }
  }, false);
});
</script>
@endsection
