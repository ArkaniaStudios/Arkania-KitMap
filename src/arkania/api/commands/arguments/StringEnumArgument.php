<?php
declare(strict_types=1);

namespace arkania\api\commands\arguments;

use pocketmine\command\CommandSender;
use pocketmine\network\mcpe\protocol\types\command\CommandEnum;

abstract class StringEnumArgument extends BaseArgument {

    protected const VALUES = [];

    public function __construct(string $name, bool $isOptional = false) {
        parent::__construct($name, $isOptional);
        $this->parameter->enum = new CommandEnum('', $this->getEnumValues());
    }

    public function getNetworkType(): int {
        return -1;
    }

    public function canParse(string $testString, CommandSender $sender): bool {
        return (bool)preg_match(
            "/^(" . implode("|", array_map('\\strtolower', $this->getEnumValues())) . ")$/iu",
            $testString
        );
    }

    /**
     * @param string $string
     * @return mixed
     */
    public function getValues(string $string): mixed {
        return static::VALUES[strtolower($string)];
    }

    /**
     * @return (string|mixed)[]
     */
    public function getEnumValues() : array {
        return array_keys(static::VALUES);
    }

}