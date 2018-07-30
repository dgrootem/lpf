<div class="form-row">
  <div class="form-group col-md-6">
    <label for"start">Start</label>
    <input type="date" name="start" max="2019-06-30"
        min="2018-08-01" value="{{ date('Y-m-d') }}"
        id="start"
        data-date-format="dddd DD MM YYYY"
        class="form-control" required>
  </div>
  <div class="form-group col-md-6">
    <label for"stop">Einde</label>
    <input type="date" name="stop" min="2018-08-01"
        id="einde"
        max="2019-06-30" value="{{ date('Y-m-d') }}"
        data-date-format="dddd DD MM YYYY"
        class="form-control" required>
  </div>
</div>
<small id="dateRangeError" class="error" aria-live="polite"></small>
<script type="text/javascript">

  var form  = document.getElementsByTagName('form')[0];
  var start = document.getElementById('start');
  var stop =  document.getElementById('einde');
  var error = document.getElementById('dateRangeError');


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

</script>
