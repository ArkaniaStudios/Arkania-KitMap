<?php
declare(strict_types=1);

namespace arkania\api\commands\arguments;

use pocketmine\command\CommandSender;
use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;

class TargetArgument extends BaseArgument {

    public function getNetworkType(): int {
        return AvailableCommandsPacket::ARG_TYPE_TARGET;
    }

    public function getTypeName(): string {
        return "target";
    }

    public function canParse(string $testString, CommandSender $sender): bool {
        return true;
    }

    public function parse(string $argument, CommandSender $sender): string {
        return $argument;
    }

}