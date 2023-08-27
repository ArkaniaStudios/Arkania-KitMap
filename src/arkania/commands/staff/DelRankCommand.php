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
use arkania\player\CustomPlayer;
use arkania\ranks\RankFailureException;
use arkania\ranks\RanksManager;
use pocketmine\command\CommandSender;

class DelRankCommand extends BaseCommand {

	public function __construct() {
		parent::__construct(
			'delrank',
			CustomTranslationFactory::arkania_ranks_delete_description(),
			'/delrank <rank>',
			permission: Permissions::ARKANIA_DELRANk
		);
	}

	protected function registerArguments() : array {
		return [
			new StringArgument('rank')
		];
	}

	public function onRun(CommandSender $player, array $parameters) : void {
		if (!$player instanceof CustomPlayer) return;
		$rank = $parameters['rank'];
		if (!RanksManager::getInstance()->exists($rank)) {
			$player->sendMessage(CustomTranslationFactory::arkania_ranks_no_exist($rank));
			return;
		}

		try {
			$message = RanksManager::getInstance()->delRank($rank);
			$player->sendMessage($message);
		}catch (RankFailureException $exception) {
			$player->sendMessage($exception->getMessage());
		} catch (\JsonException $e) {
			return;
		}
	}
}
