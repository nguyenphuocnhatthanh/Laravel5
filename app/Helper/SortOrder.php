<?php
/**
 * Created by PhpStorm.
 * User: ron
 * Date: 20/05/2015
 * Time: 08:13
 */

namespace App\Helper;


trait SortOrder {

    public function sort($request){
        $sort = $request->has('sort') ? $request->get('sort') : null;
        $orderBy = $request->get('orderBy');
        return ['sort' => $sort, 'orderBy' => $orderBy];
    }
}