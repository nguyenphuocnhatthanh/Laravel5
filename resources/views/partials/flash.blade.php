<div class="alert alert-{{Session::get('flash.level')}}">
    <button type="button" data-dismiss="alert" aria-hidden="true" class="close">x</button>
    {{Session::get('flash_notification.message')}}
</div>