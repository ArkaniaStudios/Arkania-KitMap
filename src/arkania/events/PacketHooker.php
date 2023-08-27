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

namespace arkania\events;

use arkania\api\commands\arguments\SubArgument;
use arkania\api\commands\BaseCommand;
use arkania\api\commands\interface\ArgumentableInterface;
use arkania\api\commands\SoftEnumStore;
use arkania\libs\muqsit\simplepackethandler\SimplePacketHandler;
use pocketmine\command\CommandSender;
use pocketmine\event\EventPriority;
use pocketmine\event\Listener;
use pocketmine\network\mcpe\NetworkSession;
use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;
use pocketmine\network\mcpe\protocol\types\command\CommandEnum;
use pocketmine\network\mcpe\protocol\types\command\CommandOverload;
use pocketmine\network\mcpe\protocol\types\command\CommandParameter;
use pocketmine\plugin\Plugin;
use pocketmine\Server;

class PacketHooker implements Listener {

	private static bool $isRegistered = false;

	private static bool $isIntercepting = false;

	public static function isRegistered() : bool {
		return self::$isRegistered;
	}

	public static function register(Plugin $registrant) : void {
		if(self::$isRegistered) {
			throw new \InvalidArgumentException("Event listener is already registered by another plugin.");
		}

		$interceptor = SimplePacketHandler::createInterceptor($registrant, EventPriority::NORMAL, false);
		$interceptor->interceptOutgoing(function (AvailableCommandsPacket $pk, NetworkSession $target) : bool {
			if(self::$isIntercepting)return true;
			$p = $target->getPlayer();
			foreach($pk->commandData as $commandName => $commandData) {
				$cmd = Server::getInstance()->getCommandMap()->getCommand($commandName);
				if($cmd instanceof BaseCommand) {
					/** @phpstan-ignore-next-line */
					$pk->commandData[$commandName]->overloads = self::generateOverloads($p, $cmd);
				}
			}
			$pk->softEnums = SoftEnumStore::getEnums();
			self::$isIntercepting = true;
			$target->sendDataPacket($pk);
			self::$isIntercepting = false;
			return false;
		});

		self::$isRegistered = true;
	}

	/**
	 * @return CommandOverload[][]
	 */
	private static function generateOverloads(CommandSender $cs, BaseCommand $command) : array {
		$overloads = [];

		$subArgument = false;
		foreach ($command->getArgumentList() as $arguments) {
			foreach ($arguments as $argument){
				if ($argument instanceof SubArgument) {
					$overloads[] = new CommandOverload(false, [$argument->getNetworkParameter()]);
					$subArgument = true;
				}
			}
		}

		if(!$subArgument){
			foreach($command->getSubCommands() as $label => $subCommand) {
				if(!$subCommand->testPermissionSilent($cs) || $subCommand->getName() !== $label){ // hide aliases
					continue;
				}
				$scParam = new CommandParameter();
				$scParam->paramName = $label;
				$scParam->paramType = AvailableCommandsPacket::ARG_FLAG_VALID | AvailableCommandsPacket::ARG_FLAG_ENUM;
				$scParam->isOptional = false;
				$scParam->enum = new CommandEnum($label, [$label]);

				/** @var CommandOverload[] $overloadList */
				$overloadList = self::generateOverloadList($subCommand);
				if(!empty($overloadList)){
					foreach($overloadList as $overload) {
						$overloads[] = new CommandOverload(false, [$scParam, ...$overload->getParameters()]);
					}
				} else {
					$overloads[] = new CommandOverload(false, [$scParam]);
				}
			}

			foreach(self::generateOverloadList($command) as $overload) {
				$overloads[] = $overload;
			}
		}
		/** @phpstan-ignore-next-line */
		return $overloads;
	}

	/**
	 * @return CommandOverload[][]
	 */
	private static function generateOverloadList(ArgumentableInterface $argumentable) : array {
		$input = $argumentable->getArgumentList();
		$combinations = [];
		$outputLength = array_product(array_map("count", $input));
		$indexes = [];
		foreach($input as $k => $charList){
			$indexes[$k] = 0;
		}
		do {
			/** @var CommandParameter[] $set */
			$set = [];
			foreach($indexes as $k => $index){
				$set[$k] = clone $input[$k][$index]->getNetworkParameter();

			}
			$combinations[] = new CommandOverload(false, $set);

			foreach($indexes as $k => $v){
				$indexes[$k]++;
				$lim = count($input[$k]);
				if($indexes[$k] >= $lim){
					$indexes[$k] = 0;
					continue;
				}
				break;
			}
		} while(count($combinations) !== $outputLength);

		/** @phpstan-ignore-next-line */
		return $combinations;
	}

}
