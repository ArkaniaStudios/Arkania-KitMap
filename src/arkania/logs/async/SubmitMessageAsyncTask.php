<?php
declare(strict_types=1);

namespace arkania\logs\async;

use pocketmine\scheduler\AsyncTask;

class SubmitMessageAsyncTask extends AsyncTask {

    /** @var callable */
    private $onRun;

    /** @var callable|null */
    private $onCompletion;

    public function __construct(
        callable $onRun,
        callable $onCompletion = null
    ) {
        $this->onRun = $onRun;
        $this->onCompletion = $onCompletion;
    }

    public function onRun(): void {
        ($this->onRun)($this);
    }

    public function onCompletion(): void {
        if ($this->onCompletion !== null){
            ($this->onCompletion)($this->getResult());
        }
    }

}