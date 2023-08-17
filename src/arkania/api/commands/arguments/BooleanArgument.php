<?php
declare(strict_types=1);

namespace arkania\api\commands\arguments;

use pocketmine\command\CommandSender;

class BooleanArgument extends StringEnumArgument {

    protected array $value = [
        'true' => true,
        'false' => false,
    ];

    public function getTypeName(): string {
        return 'bool';
    }

    public function parse(string $argument, CommandSender $sender) : bool {
        return $this->getValues($argument);
    }

}