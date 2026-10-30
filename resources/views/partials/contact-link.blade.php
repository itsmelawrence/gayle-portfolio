<li><span>{{ $label }}</span>@if (! $value){{ ph("[$placeholder]") }}@elseif (isset($href))<a href="{{ $href }}">{{ $value }}</a>@else<span>{{ $value }}</span>@endif</li>
