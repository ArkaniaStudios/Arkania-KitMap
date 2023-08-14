<?php
declare(strict_types=1);

namespace arkania\api\commands\arguments;

use pocketmine\command\CommandSender;
use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;

class IntArgument extends BaseArgument {

    public function getNetworkType(): int {
        return AvailableCommandsPacket::ARG_TYPE_INT;
    }

    public function getTypeName(): string {
        return 'int';
    }

    public function canParse(string $testString, CommandSender $sender): bool {
        return (bool)preg_match('/^[-+]?\d+$/', $testString);
    }

    public function parse(string $argument, CommandSender $sender): int {
        return (int)$argument;
    }

}