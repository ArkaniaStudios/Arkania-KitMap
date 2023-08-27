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

use arkania\api\commands\arguments\StringArgument;
use arkania\api\commands\BaseSubCommand;
use arkania\factions\Faction;
use arkania\factions\FactionArgumentInvalidException;
use arkania\factions\FactionManager;
use arkania\language\CustomTranslationFactory;
use arkania\player\CustomPlayer;
use pocketmine\command\CommandSender;

class InfoCommand extends BaseSubCommand {

	public function __construct() {
		parent::__construct(
			'info'
		);
	}

	protected function registerArguments() : array {
		return [
			new StringArgument('factionTarget', true)
		];
	}

	public function onRun(CommandSender $player, array $args) : void {
		if (!$player instanceof CustomPlayer) return;

		if (isset($args['factionTarget'])) {
			if (FactionManager::getInstance()->exist($args['factionTarget'])){
				try {
					$faction = new Faction($args['factionTarget']);
				}catch (FactionArgumentInvalidException $e) {
					$player->sendMessage(CustomTranslationFactory::arkania_faction_not_exists($e->getMessage()));
					return;
				}
			}else{
				$player->sendMessage(CustomTranslationFactory::arkania_faction_not_exists($args['factionTarget']));
				return;
			}
		}else{
			$faction = $player->getFaction();
		}

		$officers = $faction->getOfficiers();
		if (count($officers) === 0) {
			$officers = [$player->getLanguage()->translate(CustomTranslationFactory::arkania_faction_no_officer())];
		}else{
			$officers = array_map(function (string $officer) : string {
				return $officer . '§f';
			}, $officers);
		}

		$description = $faction->getDescription();
		if ($description === '') {
			$description = $player->getLanguage()->translate(CustomTranslationFactory::arkania_faction_no_description());
		}

		$player->sendMessage(CustomTranslationFactory::arkania_faction_info(
			$faction->getName(),
			$faction->getName(),
			$faction->getOwner(),
			'§e' . implode('§f, §e', $officers),
			'§e' . implode('§f, §e', $faction->getMembers()),
			$faction->getCreationDate(),
			$description,
			(string) $faction->getMoney(),
			(string) $faction->getPower()
		), false);
	}
}
