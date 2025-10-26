@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim((string) $slot) === config('app.name'))
<img src="{{ asset('img/logosikarir.png') }}" class="logo" alt="Sikarir Logo">
@else
{!! $slot !!}
@endif
</a>
</td>
</tr>
