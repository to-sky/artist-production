<section class="ap_section ap_section--link-menu">
    <div class="ap_section__content">
        <div class="ap_form">
            <ul class="ap_links-list">
                @foreach($links as $link)
                    <li>
                        <a href="{{ route($link['route']) }}" class="ap_links-list_link @if($active == $link['name']) active @endif">
                            {{ $trans[$link['name']] ?? __($link['label']) }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</section>