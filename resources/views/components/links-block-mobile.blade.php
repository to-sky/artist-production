@foreach($links as $link)
    <li>
        <a href="{{ route($link['route']) }}">
            {{ $trans[$link['name']] ?? __($link['label']) }}
        </a>
    </li>
@endforeach