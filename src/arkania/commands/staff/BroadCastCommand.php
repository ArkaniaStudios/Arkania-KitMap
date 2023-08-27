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

namespace arkania\commands\staff;

use arkania\api\commands\arguments\StringArgument;
use arkania\api\commands\arguments\TextArgument;
use arkania\api\commands\BaseCommand;
use arkania\form\FormManager;
use arkania\language\CustomTranslationFactory;
use arkania\permissions\Permissions;
use arkania\player\CustomPlayer;
use pocketmine\command\CommandSender;
use pocketmine\command\utils\InvalidCommandSyntaxException;
use pocketmine\console\ConsoleCommandSender;

class BroadCastCommand extends BaseCommand {

	public function __construct() {
		parent::__construct(
			'broadcast',
			CustomTranslationFactory::arkania_broadcast_description(),
			'/bc',
			[],
			['bc'],
			Permissions::ARKANIA_BROADCAST
		);
	}

	protected function registerArguments() : array {
		return [
			new StringArgument('type', true),
			new TextArgument('message', true)
		];
	}

	public function onRun(CommandSender $player, array $parameters) : void {
		if ($player instanceof ConsoleCommandSender) {
			if (count($parameters) === 0) {
				throw new InvalidCommandSyntaxException();
			}
			if ($parameters['type'] === 'important'){
				$message = $parameters['message'];
				foreach ($player->getServer()->getOnlinePlayers() as $players) {
					$players->sendMessage('§e----------------------- (§cANNONCE§e) -----------------------');
					$players->sendMessage(' ');
					$players->sendMessage('§c' . $message);
					$players->sendMessage(' ');
					$players->sendMessage('§e---------------------------------------------------------');
				}
			}else{
				$message = $parameters;
				foreach ($player->getServer()->getOnlinePlayers() as $players) {
					$players->sendMessage('§c' . implode(' ', $message));
				}
			}
		}elseif($player instanceof CustomPlayer){
			if (count($parameters) !== 0) {
				throw new InvalidCommandSyntaxException();
			}
			FormManager::getInstance()->sendBroadCastForm($player);
		}
	}
}
