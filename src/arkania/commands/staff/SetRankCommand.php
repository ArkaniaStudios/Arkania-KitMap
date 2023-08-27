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
use arkania\Main;
use arkania\permissions\Permissions;
use arkania\player\PlayerNotFoundException;
use arkania\ranks\RanksManager;
use arkania\utils\trait\Date;
use arkania\webhook\Embed;
use arkania\webhook\Message;
use arkania\webhook\Webhook;
use pocketmine\command\CommandSender;

class SetRankCommand extends BaseCommand {

	public function __construct() {
		parent::__construct(
			'setrank',
			CustomTranslationFactory::arkania_ranks_setrank_description(),
			'/setrank <player> <rank>',
			permission: Permissions::ARKANIA_SETRANK
		);
	}

	protected function registerArguments() : array {
		return [
			new StringArgument('target', false),
			new StringArgument('rank', false)
		];
	}

	public function onRun(CommandSender $player, array $parameters) : void {
		$rank = $parameters['rank'];
		if (!RanksManager::getInstance()->exists($rank)){
			$player->sendMessage(CustomTranslationFactory::arkania_ranks_no_exist($rank));
			return;
		}

		try {
			$target = $parameters['target'];
			RanksManager::getInstance()->setPlayerRank($target, $rank);
			$webhook = new Webhook(Main::ADMIN_URL);
			$message = new Message();
			$embed = new Embed();
			$embed->setTitle('**SETRANK - PLAYER**')
				->setContent('- Le grade de **' . $target . '** a été modifié par **' . $player->getName() . '**' . PHP_EOL . PHP_EOL . '*Informations: *' . PHP_EOL . '- Staff: **' . $player->getName() . '**' . PHP_EOL . '- Joueur: **' . $target . '**' . PHP_EOL . '- Grade: **' . $rank . '**' . PHP_EOL . '- Date: **' . Date::create()->toString() . '**')
				->setFooter('Arkania - Ranks')
				->setColor(0xD49F44)
				->setImage();
			$message->addEmbed($embed);
			$webhook->send($message);
		}catch (PlayerNotFoundException $e) {
			$player->sendMessage(CustomTranslationFactory::arkania_ranks_no_exist($e->getMessage()));
			return;
		}
		$player->sendMessage(CustomTranslationFactory::arkania_ranks_setrank_success($target, $rank));
	}

}
