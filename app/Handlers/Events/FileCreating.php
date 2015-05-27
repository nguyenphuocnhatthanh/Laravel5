<?php namespace App\Handlers\Events;

use App\Events\CreateFileToDB;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldBeQueued;

class FileCreating {

	/**
	 * Create the event handler.
	 *
	 * @return void
	 */
	public function __construct()
	{
		//
	}

	/**
	 * Handle the event.
	 *
	 * @param  CreateFileToDB  $event
	 * @return void
	 */
	public function handle(CreateFileToDB $event)
	{
        $event->createFile($event->getFileName(), $event->getDataJSON());
	}

}
