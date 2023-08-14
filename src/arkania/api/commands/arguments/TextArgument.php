<?php
declare(strict_types=1);

namespace arkania\api\commands\arguments;

use pocketmine\command\CommandSender;
use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;

class TextArgument extends StringArgument {

    public function getNetworkType(): int {
        return AvailableCommandsPacket::ARG_TYPE_RAWTEXT;
    }

    public function getTypeName(): string {
        return 'text';
    }

    public function getSpanLength() : int {
        return PHP_INT_MAX;
    }

    public function canParse(string $testString, CommandSender $sender): bool {
        return $testString !== '';
    }

}