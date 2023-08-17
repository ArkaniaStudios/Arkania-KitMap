<?php
declare(strict_types=1);

namespace arkania\api\commands\interface;

use arkania\api\commands\arguments\BaseArgument;
use pocketmine\command\CommandSender;

interface ArgumentableInterface {

    public function generateUsageMessage(): string;
    public function hasArguments(): bool;

    /**
     * @return BaseArgument[][]
     */
    public function getArgumentList(): array;

    /**
     * @param (string|mixed)[] $rawArgs
     * @param CommandSender $sender
     * @return (string|mixed)[]
     */
    public function parseArguments(array $rawArgs, CommandSender $sender): array;
    public function registerArgument(int $position, BaseArgument $argument): void;

}