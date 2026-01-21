@props(['url'])
<tr>
<td class="header">
<a href="{{ $url }}" style="display: inline-block;">
@if (trim((string) $slot) === config('app.name'))
<img src="https://drive.google.com/file/d/1kdpxas7OP30mmjY4rZO_kxALMpo98hzw/view?usp=sharing" class="logo" alt="Sikarir Logo">
@else
{!! $slot !!}
@endif
</a>
</td>
</tr>
