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

namespace arkania\factions\commands\subs;

use arkania\api\commands\arguments\IntArgument;
use arkania\api\commands\arguments\SubArgument;
use arkania\api\commands\BaseSubCommand;
use arkania\economy\EconomyManager;
use arkania\factions\FactionArgumentInvalidException;
use arkania\language\CustomTranslationFactory;
use arkania\player\CustomPlayer;
use arkania\utils\Utils;
use pocketmine\command\CommandSender;

class BankCommand extends BaseSubCommand {

	public function __construct() {
		parent::__construct(
			'bank'
		);
	}

	protected function registerArguments() : array {
		return [
			new SubArgument('add', true),
			new IntArgument('amount', true)
		];
	}

	/**
	 * @throws FactionArgumentInvalidException
	 * @throws \JsonException
	 */
	public function onRun(CommandSender $player, array $args) : void {
		if (!$player instanceof CustomPlayer) return;

		if (!$player->hasFaction()) {
			$player->sendMessage(CustomTranslationFactory::arkania_faction_no_have());
			return;
		}

		if (isset($args['add'], $amount) && $args['add'] === 'add' && Utils::isValidNumber($args['amount'])) {
			$amount = $args['amount'];
			if (EconomyManager::getInstance()->getMoney($player->getName()) < $amount) {
				$player->sendMessage(CustomTranslationFactory::arkania_faction_bank_no_money());
				return;
			}
			$player->getFaction()?->addMoney($amount);
			EconomyManager::getInstance()->delMoney($player->getName(), $amount);
			$player->sendMessage(CustomTranslationFactory::arkania_faction_bank_add((string) $amount));
			return;
		}
		$player->sendMessage(CustomTranslationFactory::arkania_faction_bank((string) $player->getFaction()?->getMoney() ?? '0'));
	}

}
