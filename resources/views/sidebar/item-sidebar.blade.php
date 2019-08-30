<li class="@if(isset($item['sub']) && count($item['sub'])>0)treeview @endif @if(isset($item['active']))active @endif">
    <a href="{{ $item['router'] }}">
        <i class="{{ $item['icon'] }}"></i> <span>{{ $item['text'] }}</span>
        @if(isset($item['sub']) && count($item['sub'])>0)
            <span class="pull-right-container">
                  <i class="fa fa-angle-left pull-right"></i>
            </span>
        @endif
    </a>
    @if(isset($item['sub']) && count($item['sub'])>0)
        <ul class="treeview-menu">
            {!! $submenu !!}
        </ul>
    @endif
</li>