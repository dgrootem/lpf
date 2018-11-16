<td
  class="
  @switch ($dagDeel->status)
    @case (\App\DagDeel::BOOKED)
      @if(in_array($dagDeel->school->id,Auth::user()->schools()->pluck('id')->toArray()))
        ownschoolBooked
      @endif
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
  @if ($dagDeel->status == \App\DagDeel::AVAILABLE )
    data-originating-school="{{$dagDeel->school->id}}"
  @endif
  data-datum="{{$datum}}"
  @if ($dagDeel->status==\App\DagDeel::BOOKED)
    title="{{$dagDeel->periode->opmerking}}"
  @endif
  >
  @if ($dagDeel->status!=\App\DagDeel::UNAVAILABLE)
    {{$dagDeel->school->afkorting}}
  @endif

</td>
