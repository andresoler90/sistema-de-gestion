<span class="d-inline-block">
    <span class="sort-selection pointer" data-orderby="{{ $column }}" data-order="ASC">
        <i class="fas fa-long-arrow-alt-up text-{{ orderColor($data,$column,'ASC') }}"></i>
    </span>
    <span class="sort-selection pointer" data-orderby="{{ $column }}" data-order="DESC">
        <i class="fas fa-long-arrow-alt-down text-{{ orderColor($data,$column,'DESC') }}"></i>
    </span>
</span>
