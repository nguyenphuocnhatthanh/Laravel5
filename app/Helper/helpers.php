<?php
function sortBy($routeName,$column, $display, $search = null){
    $orderBy = Request::get('orderBy') == 'asc' ? 'desc' : 'asc';
    return link_to_route($routeName, $display, ['sort' => $column, 'orderBy' => $orderBy, 'search' => $search]);
}
