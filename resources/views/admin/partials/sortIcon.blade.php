@if(!Request::has('sort') || Request::get('sort') != $column)
    <i class="fa fa-fw fa-sort"></i>
@elseif(Request::get('orderBy') == 'asc')
    <i class="fa fa-fw fa-sort fa-sort-asc"></i>
@else
    <i class="fa fa-fw fa-sort fa-sort-desc"></i>
@endif