<?php
declare(strict_types=1);

namespace arkania\api\commands\arguments;

use arkania\api\commands\SoftEnumStore;
use pocketmine\command\CommandSender;
use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;
use pocketmine\network\mcpe\protocol\types\command\CommandEnum;
use pocketmine\network\mcpe\protocol\types\command\CommandOverload;
use pocketmine\network\mcpe\protocol\types\command\CommandParameter;

class SubArgument extends BaseArgument {

    public function __construct(string $name, bool $isOptional = false) {
        parent::__construct($name, $isOptional);

        $this->parameter->paramType = AvailableCommandsPacket::ARG_FLAG_VALID | AvailableCommandsPacket::ARG_FLAG_ENUM;
        SoftEnumStore::addEnum($this->parameter->enum = new CommandEnum(strtolower($name), [strtolower($name)]));
    }

    public function getNetworkType(): int {
        return CommandParameter::FLAG_FORCE_COLLAPSE_ENUM;
    }

    public function getTypeName(): string {
        return 'mixed';
    }

    public function canParse(string $testString, CommandSender $sender): bool {
        return true;
    }

    public function parse(mixed $argument, CommandSender $sender) : mixed{
        return $argument;
    }
}