@foreach($items as $item)
    <?php
    if ($item->hasChildren()){
        if ($item->children()->where('isActive',true)->first() !== null){
            $active = 'active';
        }else{
            $active = '';
        }
    }else{
        $active = '';
    }
    ?>
    <li @lm_attrs($item) @if($item->hasChildren()) class="nav-item" @endif @lm_endattrs>
        @if($item->link) <a @lm_attrs($item->link)
            @if($item->hasChildren()) class="nav-link" data-toggle="collapse" role="button" aria-expanded="{{ $active != '' ? 'true' : 'false' }}" aria-controls="collapseExample" @else class="nav-link" @endif @lm_endattrs href="{!! $item->url() !!}">
            {!! $item->title !!}
            @if($item->hasChildren())
            <i class="ri-arrow-right-s-line iq-arrow-right"></i>
            @endif
        </a>
        @else
            <span class="navbar-text">{!! $item->title !!}</span>
        @endif
        @if($item->hasChildren())
            <ul class="iq-submenu collapse  {{ $active != '' ? 'show' : '' }}" id="{!! str_replace('#','',$item->url()) !!}">
                @include(config('laravel-menu.views.bootstrap-items'),
        array('items' => $item->children()))
            </ul>
        @endif
    </li>
    @if($item->divider)
        <li>{!! Lavary\Menu\Builder::attributes($item->divider) !!}></li>
    @endif
@endforeach
