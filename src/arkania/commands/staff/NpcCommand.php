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
use arkania\form\FormManager;
use arkania\language\CustomTranslationFactory;
use arkania\permissions\Permissions;
use arkania\player\CustomPlayer;
use pocketmine\command\CommandSender;

class NpcCommand extends BaseCommand {
	public static array $npc = [];

	public function __construct() {
		parent::__construct(
			'npc',
			CustomTranslationFactory::arkania_npc_description(),
			'/npc',
			[],
			[],
			Permissions::COMMAND_NPC
		);
	}

	protected function registerArguments() : array {
		return [
			new SubArgument('create', true),
			new SubArgument('disband', true),
			new SubArgument('rotate', true),
			new SubArgument('edit', true)
		];
	}

	public function onRun(CommandSender $player, array $parameters) : void {
		if(!$player instanceof CustomPlayer) return;

		if($parameters === []) {
			FormManager::getInstance()->sendNpcCreationForm($player);
			return;
		}
		$argument = strtolower($parameters['create']);
		if($argument === 'create') {
			FormManager::getInstance()->sendNpcCreationForm($player);
		} elseif($argument === 'disband') {
			self::$npc[$player->getName()] = 'disband';
			$player->sendMessage(CustomTranslationFactory::npc_tap_for_disband());
		} elseif($argument === 'rotate') {
			self::$npc[$player->getName()] = 'rotate';
			$player->sendMessage(CustomTranslationFactory::npc_tap_for_rotate());
		} elseif($argument === 'edit') {
			self::$npc[$player->getName()] = 'edit';
			$player->sendMessage(CustomTranslationFactory::npc_tap_for_edit());
		}
	}

}
