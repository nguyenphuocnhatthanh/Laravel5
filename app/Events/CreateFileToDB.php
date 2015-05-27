<?php namespace App\Events;

use App\Events\Event;

use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class CreateFileToDB extends Event {

	use SerializesModels;
    private $fileName;

    /**
     * @return mixed
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * @return mixed
     */
    public function getDataJSON()
    {
        return $this->dataJSON;
    }
    private $dataJSON;



    /**
	 * Create a new event instance.
	 *
	 * @return void
	 */
    function __construct($fileName, $dataJSON)
    {
        $this->fileName = $fileName;
        $this->dataJSON = $dataJSON;
    }

    public function createFile($fileName, $dataJSON){
        Storage::put($fileName, $dataJSON);
    }

}
