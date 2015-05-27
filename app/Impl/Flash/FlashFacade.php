<?php  namespace App\Impl\Flash;
use Illuminate\Support\Facades\Facade;

/**
 * Created by PhpStorm.
 * User: ron
 * Date: 30/03/2015
 * Time: 13:58
 */
class FlashFacade extends Facade{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() {
        return 'flash';
    }
}