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
use arkania\language\CustomTranslationFactory;
use arkania\Main;
use arkania\permissions\Permissions;
use arkania\player\CustomPlayer;
use arkania\utils\trait\Date;
use arkania\webhook\Embed;
use arkania\webhook\Message;
use arkania\webhook\Webhook;
use pocketmine\command\CommandSender;
use pocketmine\command\utils\InvalidCommandSyntaxException;
use pocketmine\scheduler\ClosureTask;

class RedemCommand extends BaseCommand {
	public function __construct() {
		parent::__construct(
			'redem',
			CustomTranslationFactory::arkania_redem_description(),
			'/redem <force>',
			[],
			[],
			Permissions::ARKANIA_REDEM
		);
	}

	protected function registerArguments() : array {
		return [
			new SubArgument('force', true)
		];
	}

	public function onRun(CommandSender $player, array $parameters) : void {
		if (\count($parameters) === 0) {
			$time = 30;
			$task = Main::getInstance()->getScheduler()->scheduleRepeatingTask(
				new ClosureTask(
					function () use (&$task, $player, &$time) {
						if ($time % 10 === 0 || $time === 5 || $time === 4 || $time === 3 || $time === 2 || $time === 1) {
							foreach (Main::getInstance()->getServer()->getOnlinePlayers() as $players) {
								if ($players instanceof CustomPlayer) {
									$players->sendMessage(CustomTranslationFactory::arkania_redem_timing("$time"));
								}
							}
						} elseif ($time === 0) {
							foreach (Main::getInstance()->getServer()->getOnlinePlayers() as $players) {
								if ($players instanceof CustomPlayer) {
									$players->sendMessage(CustomTranslationFactory::arkania_redem_success());
								}
							}
							Main::getInstance()->getServer()->forceShutdown();
							RedemCommand::sendWebhook($player);
							$task->cancel();
						}
						$time--;
					}
				),
				20
			);
		} elseif ($parameters['force'] === 'force') {
			$this->sendWebhook($player);
			$player->getServer()->forceShutdown();
			$player->sendMessage(CustomTranslationFactory::arkania_redem_success());
		} else {
			throw new InvalidCommandSyntaxException();
		}
	}

	public static function sendWebhook(CommandSender $player) : void {
		$webhook = new Webhook(Main::ADMIN_URL);
		$message = new Message();
		$embed = new Embed();
		$embed->setTitle('**REDÉMARRAGE**')
			->setContent('- Le serveur vient de redémarrer !' . PHP_EOL . PHP_EOL . '*Informations:*' . PHP_EOL . '- Redémarrage forcé par **' . $player->getName() . '**' . PHP_EOL . '- Date: **' . Date::create()->toString() . '**')
			->setFooter('Arkania - Redémarrage');
		$message->addEmbed($embed);
		$webhook->send($message);
	}
}
