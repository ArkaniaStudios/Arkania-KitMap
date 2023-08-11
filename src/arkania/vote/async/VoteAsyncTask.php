<?php
declare(strict_types=1);

namespace arkania\vote\async;

use pocketmine\scheduler\AsyncTask;

class VoteAsyncTask extends AsyncTask {

    /** @var callable */
    private $onRun;

    /** @var callable */
    private $onCompletion;

    public function __construct(
        callable $onRun,
        callable $onCompletion
    ) {
        $this->onRun = $onRun;
        $this->onCompletion = $onCompletion;
    }

    public function onRun(): void {
        call_user_func($this->onRun, $this);
    }

    public function onCompletion(): void {
        call_user_func($this->onCompletion, $this);
    }

}