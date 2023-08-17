<?php
declare(strict_types=1);

namespace arkania\api\commands;

use arkania\api\commands\interface\ArgumentableInterface;
use arkania\utils\trait\ArgumentableTrait;
use arkania\utils\trait\ArgumentOrderException;
use pocketmine\command\CommandSender;
use pocketmine\lang\Translatable;

abstract class BaseSubCommand implements ArgumentableInterface {
    use ArgumentableTrait;

    /** @var string */
    private string $name;
    /** @var string[] */
    private array $aliases;
    /** @var string */
    private string $description;
    /** @var string */
    protected string $usageMessage;
    /** @var string|null */
    private ?string $permission = null;
    /** @var CommandSender */
    protected CommandSender $currentSender;
    /** @var BaseCommand */
    protected BaseCommand $parent;

    /**
     * @param string $name
     * @param string $description
     * @param string[] $aliases
     * @throws ArgumentOrderException
     */
    public function __construct(string $name, string $description = "", array $aliases = []) {
        $this->name = $name;
        $this->description = $description;
        $this->aliases = $aliases;
        foreach ($this->registerArguments() as $pos => $argument) {
            $this->registerArgument($pos, $argument);
        }
        $this->usageMessage = $this->generateUsageMessage();
    }

    /**
     * @param CommandSender $player
     * @param string $aliasUsed
     * @param (string|mixed)[] $args
     * @return void
     */
    abstract public function onRun(CommandSender $player, string $aliasUsed, array $args): void;

    /**
     * @return string
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * @return string[]
     */
    public function getAliases(): array {
        return $this->aliases;
    }

    /**
     * @return string
     */
    public function getDescription(): string {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getUsageMessage(): string {
        return $this->usageMessage;
    }

    /**
     * @return string|null
     */
    public function getPermission(): ?string {
        return $this->permission;
    }

    /**
     * @param string $permission
     */
    public function setPermission(string $permission): void {
        $this->permission = $permission;
    }

    public function testPermissionSilent(CommandSender $sender): bool {
        if(empty($this->permission)) {
            return true;
        }
        foreach(explode(";", $this->permission) as $permission) {
            if($sender->hasPermission($permission)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param CommandSender $currentSender
     *
     * @internal Used to pass the current sender from the parent command
     */
    public function setCurrentSender(CommandSender $currentSender): void {
        $this->currentSender = $currentSender;
    }

    /**
     * @param BaseCommand $parent
     *
     * @internal Used to pass the parent context from the parent command
     */
    public function setParent(BaseCommand $parent): void {
        $this->parent = $parent;
    }

    /**
     * @param int $errorCode
     * @param (string|mixed)[] $args
     * @return void
     */
    public function sendError(int $errorCode, array $args = []): void {
        $this->parent->sendError($errorCode, $args);
    }

    public function sendUsage():void {
        $this->currentSender->sendMessage("/{$this->parent->getName()} $this->usageMessage");
    }

}