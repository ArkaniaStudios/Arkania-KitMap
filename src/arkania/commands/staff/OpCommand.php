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
use arkania\utils\trait\Date;
use arkania\webhook\Embed;
use arkania\webhook\Message;
use arkania\webhook\Webhook;
use pocketmine\command\CommandSender;

class OpCommand extends BaseCommand {

	public function __construct() {
		parent::__construct(
			'op',
			CustomTranslationFactory::arkania_op_description(),
			'/op <player>',
			permission: Permissions::ARKANIA_OP
		);
	}

	protected function registerArguments() : array {
		return [
			new StringArgument('target')
		];
	}

	public function onRun(CommandSender $player, array $parameters) : void {
		$target = $parameters['target'];
		if(!$this->getMain()->getServer()->isOp($target)) {
			$this->getMain()->getServer()->addOp($target);
			$player->sendMessage(CustomTranslationFactory::arkania_op_success($target));
			$webhook = new Webhook(Main::ADMIN_URL);
			$message = new Message();
			$embed = new Embed();
			$embed->setTitle('**OP - PLAYER**')
				->setContent('- Le joueur **' . $target . '** vient d\'être promus opérateur.' . PHP_EOL . PHP_EOL . '*Informations:*' . PHP_EOL . '- Staff: **' . $player->getName() . '**' . PHP_EOL . '- Joueur: **' . $target . '**' . PHP_EOL . '- Date: **' . Date::create()->toString() . '**')
				->setFooter('Arkania - Opérateur')
				->setColor(0xEF054F)
				->setImage();
			$message->addEmbed($embed);
			$webhook->send($message);
			self::sendLogs($player, 'vient d\'op ' . $target);
		} else {
			$player->sendMessage(CustomTranslationFactory::arkania_op_already($target));
		}
	}
}
