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
use arkania\api\commands\BaseCommand;
use arkania\language\CustomTranslationFactory;
use arkania\permissions\Permissions;
use arkania\ranks\elements\RanksFormatInfo;
use arkania\ranks\InvalidFormatException;
use arkania\ranks\RankFailureException;
use arkania\ranks\Ranks;
use arkania\ranks\RanksManager;
use pocketmine\command\CommandSender;

class AddRankCommand extends BaseCommand {

	public function __construct() {
		parent::__construct(
			'addrank',
			CustomTranslationFactory::arkania_ranks_addrank_description(),
			'/addrank <rank: string',
			permission: Permissions::ARKANIA_ADDRANK
		);
	}

	protected function registerArguments() : array {
		return [
			new StringArgument('rank'),
			new StringArgument('color')
		];
	}

	public function onRun(CommandSender $player, array $parameters) : void {
		$rank = $parameters['rank'];
		if (RanksManager::getInstance()->exists($rank)){
			$player->sendMessage(CustomTranslationFactory::arkania_ranks_addrank_exist($rank));
			return;
		}

		try {
			$color = $parameters['color'] ?? '§e';
			$message = RanksManager::getInstance()->addRank(new Ranks(
				$rank,
				new RanksFormatInfo('§7[{PLAYER_STATUS}§7] [' . $color . '{FACTION_RANK}{FACTION}§7] [' . $color . $rank . '§7] {PLAYER_NAME} » {MESSAGE}'),
				new RanksFormatInfo('[' . $color . '{FACTION}§f] {LINE} ' . $color . ' {PLAYER_NAME}'),
				null,
				$parameters['color'] ?? '§f',
				false,
			));
			$player->sendMessage($message);
		}catch (RankFailureException $exception){
			$player->sendMessage($exception->getMessage());
			return;
		} catch (InvalidFormatException $e) {
			$player->sendMessage($e->getMessage());
			return;
		}
	}
}
