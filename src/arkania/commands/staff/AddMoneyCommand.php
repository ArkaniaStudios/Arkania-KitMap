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
use arkania\economy\events\PlayerAddMoneyEvent;
use arkania\language\CustomTranslationFactory;
use arkania\permissions\Permissions;
use arkania\utils\Utils;
use JsonException;
use pocketmine\command\CommandSender;

class AddMoneyCommand extends BaseCommand {

	public function __construct() {
		parent::__construct(
			'addmoney',
			CustomTranslationFactory::arkania_economy_addmoney_description(),
			'/addmoney <player> <amount>',
			permission: Permissions::ARKANIA_ADDMONEY
		);
	}

	protected function registerArguments() : array {
		return [
			new StringArgument('target'),
			new IntArgument('amount')
		];
	}

	/**
	 * @throws JsonException
	 */
	public function onRun(CommandSender $player, array $parameters) : void {
		$amount = $parameters['amount'];
		if (Utils::isValidNumber($amount)){
			$target = $parameters['target'];
			EconomyManager::getInstance()->addMoney($target, (int) $amount);
			(new PlayerAddMoneyEvent($player, $target, $amount))->call();
			$player->sendMessage(CustomTranslationFactory::arkania_economy_addmoney_success($amount, $target));
		}else{
			$player->sendMessage(CustomTranslationFactory::arkania_economy_invalid_amount($amount));
		}
	}
}
