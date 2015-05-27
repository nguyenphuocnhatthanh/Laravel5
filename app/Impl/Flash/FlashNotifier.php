<?php namespace App\Impl\Flash;
/**
 * Created by PhpStorm.
 * User: ron
 * Date: 30/03/2015
 * Time: 14:01
 */

use Illuminate\Session\Store as Session;

class FlashNotifier {

    /**
     * Session
     * @var $session
     */
    protected $session;

    public function __construct(Session $session){
        $this->session = $session;
    }

    public function success($key, $message){
        $this->message($key, $message, 'success');
    }

    public function error($key, $message){
        $this->message($key, $message, 'danger');
    }

    public function message($key, $message, $level = 'info'){
        $this->session->flash($key, $message);
        $this->session->flash('flash.level', $level);


    }
}