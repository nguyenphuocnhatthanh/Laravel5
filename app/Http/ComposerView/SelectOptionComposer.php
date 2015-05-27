<?php  namespace App\Http\ComposerView;
use Illuminate\Contracts\View\View;

/**
 * Created by PhpStorm.
 * User: ron
 * Date: 08/04/2015
 * Time: 10:56
 */
class SelectOptionComposer {

    private $list = [
        ''              => 'Options',
        'displayall'    => 'Display All',
        'displaydelete' => 'Display deleted data',
        'delete'        => 'Delete',
    ];

    private $listResote = [
        ''              => 'Options',
        'displayall'    => 'Display All',
        'displaydelete' => 'Display deleted data',
        'restore'       => 'Restore data',
    ];

    public function compose(View $view){
        $view->with('select', $this->list);
        $view->with('listRestore', $this->listResote);
    }
}