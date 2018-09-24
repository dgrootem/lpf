@isset($scholen)
<script>

    Chart.defaults.global.defaultFontColor = 'white';

    const chart = new Chart(document.getElementById("my-chart-1"), {
      type: "horizontalBar",
      data: {
        labels:
        [


          @foreach ($scholen as $key => $school)
            ["[{{$school['afkorting']}}] {{$school['naam']}}"],
          @endforeach

        ],
        datasets: [
          {
            label: "RV",
            data: [

              @foreach ($scholen as $key => $school)
                {{$school['RV']}},
              @endforeach
            ],
            backgroundColor: "#28a745" },
          {
            label: "ongebruikt",
            data: [

              @foreach ($scholen as $key => $school)
                {{$school['unused']}},
              @endforeach
            ],
            backgroundColor: "#f8f9fa"},
        ]
      },
      options: {
        plugins: {
          stacked100: { enable: true },
        },
        tooltips: {
              enabled: false,
        },
        onClick : function(evt) {
           //var activePoint = chart.getElementAtEvent(evt)[0];
           //var data = activePoint._chart.data;
           //alert(data.labels);
         }
      }
    });
</script>
@endisset
