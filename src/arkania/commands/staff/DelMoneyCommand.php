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

use arkania\api\commands\arguments\IntArgument;
use arkania\api\commands\arguments\StringArgument;
use arkania\api\commands\BaseCommand;
use arkania\economy\EconomyManager;
use arkania\economy\events\PlayerDelMoneyEvent;
use arkania\language\CustomTranslationFactory;
use arkania\permissions\Permissions;
use arkania\utils\Utils;
use pocketmine\command\CommandSender;

class DelMoneyCommand extends BaseCommand {

	public function __construct() {
		parent::__construct(
			'delmoney',
			CustomTranslationFactory::arkania_economy_delmoney_description(),
			'/delmoney <player> <amount>',
			permission: Permissions::ARKANIA_DELMONEY
		);
	}

	protected function registerArguments() : array {
		return [
			new StringArgument('target'),
			new IntArgument('amount')
		];
	}

	public function onRun(CommandSender $player, array $parameters) : void {
		$amount = $parameters['amount'];
		if (Utils::isValidNumber($amount)){
			$target = $parameters['target'];
			if (EconomyManager::getInstance()->getMoney($target) - $amount < 0) {
				$player->sendMessage(CustomTranslationFactory::arkania_economy_invalid_amount($amount));
				return;
			}
			EconomyManager::getInstance()->delMoney($target, (int) $amount);
			(new PlayerDelMoneyEvent($player, $target, $amount))->call();
			$player->sendMessage(CustomTranslationFactory::arkania_economy_delmoney_success($amount, $target));
		}else{
			$player->sendMessage(CustomTranslationFactory::arkania_economy_invalid_amount($amount));
		}
	}
}
