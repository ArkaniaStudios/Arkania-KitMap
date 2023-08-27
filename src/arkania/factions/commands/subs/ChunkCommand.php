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

use arkania\api\commands\BaseSubCommand;
use arkania\factions\task\SeeChunkTask;
use arkania\language\CustomTranslationFactory;
use arkania\Main;
use arkania\player\CustomPlayer;
use pocketmine\command\CommandSender;

class ChunkCommand extends BaseSubCommand {

	public function __construct() {
		parent::__construct(
			'chunk'
		);
	}

	protected function registerArguments() : array {
		return [];
	}

	public function onRun(CommandSender $player, array $args) : void {
		if (!$player instanceof CustomPlayer) return;

		if ($player->isChunkView()) {
			$player->setChunkView(false);
			$player->sendMessage(CustomTranslationFactory::arkania_faction_view_chunk_disable());
		} else {
			$player->setChunkView();
			$player->sendMessage(CustomTranslationFactory::arkania_faction_view_chunk_enable());
			Main::getInstance()->getScheduler()->scheduleRepeatingTask(new SeeChunkTask(), 20);
		}
	}
}
