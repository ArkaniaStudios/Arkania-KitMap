<?php
declare(strict_types=1);

namespace arkania\api\commands\arguments;

use pocketmine\command\CommandSender;
use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;
use pocketmine\network\mcpe\protocol\types\command\CommandParameter;

abstract class BaseArgument {

    protected string $name;
    protected bool $isOptional;
    protected CommandParameter $parameter;

    public function __construct(
        string $name,
        bool $isOptional = false
    ) {
        $this->name = $name;
        $this->isOptional = $isOptional;

        $this->parameter = new CommandParameter();
        $this->parameter->paramName = $this->name;
        $this->parameter->paramType = AvailableCommandsPacket::ARG_FLAG_VALID;
        $this->parameter->paramType |= $this->getNetworkType();
        $this->parameter->isOptional = $this->isOptional;
    }

    abstract public function getNetworkType() : int;

    abstract public function canParse(string $testString, CommandSender $sender) : bool;

    abstract public function parse(string $argument, CommandSender $sender);

    /**
     * @return string
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * @return bool
     */
    public function isOptional(): bool {
        return $this->isOptional;
    }

    public function getSpanLength() : int {
        return 1;
    }

    abstract public function getTypeName() : string;

    public final function getNetworkParameter() : CommandParameter {
        return $this->parameter;
    }

}