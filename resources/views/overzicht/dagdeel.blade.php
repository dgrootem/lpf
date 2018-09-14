<td
  class="
  @switch ($dagDeel->status)
    @case (\App\DagDeel::BOOKED)
      bookedCell clickablecell clickablecell-{{$dagDeel->periode->id}}
    @break
    @case (\App\DagDeel::AVAILABLE)
      availableCell clickablecell clickablecell-new
    @break
    @default
      unavailableCell
    @endswitch

  "
  data-leerkracht="{{$leerkrachtid}}"
  data-datum="{{$datum}}"
  @if ($dagDeel->status==\App\DagDeel::BOOKED)
    title="{{$dagDeel->periode->opmerking}}"
  @endif
  >
  @if ($dagDeel->status!=\App\DagDeel::UNAVAILABLE)
    {{$dagDeel->school->naam}}
  @endif

</td>
