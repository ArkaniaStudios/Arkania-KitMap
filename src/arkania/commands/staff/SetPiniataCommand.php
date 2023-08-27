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

use arkania\api\commands\arguments\SubArgument;
use arkania\api\commands\BaseCommand;
use arkania\game\PiniataManager;
use arkania\language\CustomTranslationFactory;
use arkania\Main;
use arkania\npc\type\customs\Piniata;
use arkania\permissions\Permissions;
use arkania\player\CustomPlayer;
use pocketmine\command\CommandSender;
use pocketmine\entity\Location;
use pocketmine\Server;

class SetPiniataCommand extends BaseCommand {

	public function __construct() {
		parent::__construct(
			'piniata',
			CustomTranslationFactory::arkania_piniata_description(),
			'/piniata',
			[],
			[],
			Permissions::ARKANIA_PINIATA
		);
	}

	protected function registerArguments() : array {
		return [
			new SubArgument('create', true),
			new SubArgument('spawn', true)
		];
	}

	public function onRun(CommandSender $player, array $parameters) : void {
		if (!$player instanceof CustomPlayer) return;
		if (isset($parameters['create']) && $parameters['create'] === 'spawn'){
			Server::getInstance()->broadcastMessage(CustomTranslationFactory::arkania_piniata_start());
			$position = PiniataManager::getInstance()->getPositions();
			$entity = new Piniata(new Location($position['x'], $position['y'], $position['z'], Main::getInstance()->getServer()->getWorldManager()->getDefaultWorld(), 0, 0));
			$entity->setNameTag('§l§cPiniata' . "\n\n" . str_repeat('§a|', 10));
			$entity->setHealth(100);
			$entity->setNameTagAlwaysVisible();
			$entity->spawnToAll();
			return;
		}

		if (isset($parameters['create']) && $parameters['create'] === 'create') {
			PiniataManager::getInstance()->createSpawnLama();
			$player->sendMessage(CustomTranslationFactory::arkania_piniata_create());
			return;
		}

		$position = $player->getPosition();
		PiniataManager::getInstance()->setPositions([
			'x' => $position->getX(),
			'y' => $position->getY(),
			'z' => $position->getZ()
		]);
		$player->sendMessage(CustomTranslationFactory::arkania_piniata_set_position());
	}
}
