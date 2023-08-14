<?php

/*
 *
 *     _      ____    _  __     _      _   _   ___      _                 _   _   _____   _____  __        __   ___    ____    _  __
 *    / \    |  _ \  | |/ /    / \    | \ | | |_ _|    / \               | \ | | | ____| |_   _| \ \      / /  / _ \  |  _ \  | |/ /
 *   / _ \   | |_) | | ' /    / _ \   |  \| |  | |    / _ \     _____    |  \| | |  _|     | |    \ \ /\ / /  | | | | | |_) | | ' /
 *  / ___ \  |  _ <  | . \   / ___ \  | |\  |  | |   / ___ \   |_____|   | |\  | | |___    | |     \ V  V /   | |_| | |  _ <  | . \
 * /_/   \_\ |_| \_\ |_|\_\ /_/   \_\ |_| \_| |___| /_/   \_\            |_| \_| |_____|   |_|      \_/\_/     \___/  |_| \_\ |_|\_\
 *
 * Arkania is a Minecraft Bedrock server created in 2019,
 * we mainly use PocketMine-MP to create content for our server
 * but we use something else like WaterDog PE
 *
 * @author Arkania-Team
 * @link https://arkaniastudios.com
 *
 */

declare(strict_types=1);

namespace arkania\api\commands;

use arkania\api\commands\interface\ArgumentableInterface;
use arkania\language\CustomTranslationFactory;
use arkania\Main;
use arkania\player\CustomPlayer;
use arkania\utils\trait\ArgumentableTrait;
use arkania\utils\trait\ArgumentOrderException;
use arkania\utils\Utils;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\utils\InvalidCommandSyntaxException;
use pocketmine\lang\Translatable;
use pocketmine\permission\DefaultPermissions;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\BroadcastLoggerForwarder;
use pocketmine\utils\TextFormat;

abstract class BaseCommand extends Command implements ArgumentableInterface {
    use ArgumentableTrait;

    private ?CommandSender $currentSender = null;

    public const ERR_INVALID_ARG_VALUE = 0x01;
    public const ERR_TOO_MANY_ARGUMENTS = 0x02;
    public const ERR_INSUFFICIENT_ARGUMENTS = 0x03;
    public const ERR_NO_ARGUMENTS = 0x04;

    /** @var string[] */
    protected array $errorMessages = [
        self::ERR_INVALID_ARG_VALUE => TextFormat::RED . "Invalid value '{value}' for argument #{position}",
        self::ERR_TOO_MANY_ARGUMENTS => TextFormat::RED . "Too many arguments given",
        self::ERR_INSUFFICIENT_ARGUMENTS => TextFormat::RED . "Insufficient number of arguments given",
        self::ERR_NO_ARGUMENTS => TextFormat::RED . "No arguments are required for this command",
    ];

    public const MAX_COORD = 30000000;
    public const MIN_COORD = -30000000;

    /**
     * @param Translatable|string|null $usageMessage
     * @param string[] $aliases
     * @param string|string[] $permission
     * @throws ArgumentOrderException
     */
	public function __construct(
		string $name,
		Translatable|string $description = "",
		Translatable|string|null $usageMessage = null,
		array $aliases = [],
		string|array|null $permission = null,
	) {
		parent::__construct($name, $description, $usageMessage, $aliases);
		if ($permission !== null) {
			if (\is_array($permission)) {
				$this->setPermission(\implode(";", $permission));
			} else {
				$this->setPermission($permission);
			}
		} else {
			$this->setPermission(DefaultPermissions::ROOT_USER);
		}

        foreach ($this->registerArguments() as $pos => $argument){
            $this->registerArgument($pos, $argument);
        }

	}

	final public function execute(CommandSender $sender, string $commandLabel, array $args) : void {
        $this->currentSender = $sender;
        $cmd = $this;
        $passArgs = [];
        if (count($args) > 0){
            $passArgs = $this->attemptArgumentParsing($cmd, $args);
        }elseif($this->hasRequiredArguments()){
            $this->sendError(self::ERR_INSUFFICIENT_ARGUMENTS);
            return;
        }
        if ($passArgs !== null){
            $cmd->onRun($sender, $commandLabel, $passArgs);
        }
	}

    /**
     * @param BaseCommand $ctx
     * @param array             $args
     *
     * @return array|null
     */
    private function attemptArgumentParsing(BaseCommand $ctx, array $args): ?array {
        $dat = $ctx->parseArguments($args, $this->currentSender);
        if(!empty(($errors = $dat["errors"]))) {
            foreach($errors as $error) {
                $this->sendError($error["code"], $error["data"]);
            }

            return null;
        }

        return $dat["arguments"];
    }

    public function sendError(int $errorCode, array $args = []): void {
        $str = $this->errorMessages[$errorCode];
        foreach($args as $item => $value) {
            $str = str_replace('{' . $item . '}', (string) $value, $str);
        }
        $this->currentSender->sendMessage($str);
    }

    abstract public function onRun(CommandSender $player, string $commandLabel, array $parameters) : void;

	protected function fetchPermittedPlayerTarget(CommandSender $sender, ?string $target, string $selfPermission, string $otherPermission) : ?Player {
		if ($target !== null) {
			$player = $sender->getServer()->getPlayerByPrefix($target);
		} elseif ($sender instanceof Player) {
			$player = $sender;
		} else {
			throw new InvalidCommandSyntaxException();
		}

		if ($player === null) {
			$sender->sendMessage(CustomTranslationFactory::arkania_player_not_found($target)->prefix(Utils::getPrefix()));

			return null;
		}
		if (
			($player === $sender && $this->testPermission($sender, $selfPermission)) ||
			($player !== $sender && $this->testPermission($sender, $otherPermission))
		) {
			return $player;
		}

		return null;
	}

	public static function broadcastCommandMessage(CommandSender $source, Translatable|string $message, bool $sendToSource = true) : void {
		$users = $source->getServer()->getBroadcastChannelSubscribers('arkania.chat.type.admin');
		$result = CustomTranslationFactory::arkania_chat_type_admin($source->getName(), $message);
		$colored = $result->prefix(TextFormat::GRAY . TextFormat::ITALIC);

		if ($sendToSource) {
			if (!\is_string($message)) {
				$source->sendMessage(Main::getInstance()->getDefaultLanguage()->translate($message));
			} else {
				$source->sendMessage($message);
			}
		}

		foreach ($users as $user) {
			if ($user instanceof BroadcastLoggerForwarder) {
				$user->sendMessage(Main::getInstance()?->getDefaultLanguage()->translate($result));
			} elseif ($user !== $source) {
				$user->sendMessage(Main::getInstance()?->getDefaultLanguage()->translate($colored));
			}
		}
	}

	public static function sendLogs(CommandSender $sender, string $message, bool $onlyOp = false) : void {
		/** @var CustomPlayer $player */
		foreach (Server::getInstance()->getOnlinePlayers() as $player) {
			if ($onlyOp) {
				if ($player->getServer()->isOp($player->getName())) {
					$player->sendMessage("§o[Logs] {$sender->getName()} : " . $message);
				}
			} elseif ($player->isInLogs()) {
				$player->sendMessage("§o§7[Logs] {$sender->getName()} : " . $message);
			}
		}
		Main::getInstance()->getLogger()->log(2, "[Logs] {$sender->getName()} : " . $message);
	}

	public function getMain() : Main {
		return Main::getInstance();
	}

	public function testPermission(CommandSender $target, ?string $permission = null) : bool {
		if ($this->testPermissionSilent($target, $permission)) {
			return true;
		}

		$target->sendMessage(CustomTranslationFactory::arkania_command_not_permission($this->getName()));

		return false;
	}

    protected function getRelativeDouble(float $original, string $input, float $min = self::MIN_COORD, float $max = self::MAX_COORD) : float{
        if($input[0] === "~"){
            $value = $this->getDouble(substr($input, 1));

            return $original + $value;
        }

        return $this->getDouble($input, $min, $max);
    }

    protected function getDouble(string $value, float $min = self::MIN_COORD, float $max = self::MAX_COORD) : float{
        $i = (double) $value;

        if($i < $min){
            $i = $min;
        }elseif($i > $max){
            $i = $max;
        }

        return $i;
    }

}
