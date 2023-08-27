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

namespace arkania\commands\player;

use arkania\api\commands\arguments\TargetArgument;
use arkania\api\commands\arguments\TextArgument;
use arkania\api\commands\BaseCommand;
use arkania\form\FormManager;
use arkania\language\CustomTranslationFactory;
use arkania\Main;
use arkania\permissions\Permissions;
use arkania\player\CustomPlayer;
use arkania\report\ReportManager;
use arkania\utils\trait\Date;
use arkania\webhook\Embed;
use arkania\webhook\Message;
use arkania\webhook\Webhook;
use JsonException;
use pocketmine\command\CommandSender;

class ReportCommand extends BaseCommand {

	public function __construct() {
		parent::__construct(
			'report',
			CustomTranslationFactory::arkania_report_description(),
			'/report <player> <raison>'
		);
	}

	protected function registerArguments() : array {
		return [
			new TargetArgument('target', true),
			new TextArgument('raison', true)
		];
	}

	/**
	 * @throws JsonException
	 */
	public function onRun(CommandSender $player, array $parameters) : void {

		if (!$player instanceof CustomPlayer) return;

		if ($parameters === []) {
			FormManager::getInstance()->sendReportForm($player);
			return;
		}

		$target = $parameters['target'];
		$raison = $parameters['raison'];

		if ($target === null || $raison === null) {
			FormManager::getInstance()->sendReportForm($player);
			return;
		}
		$count = count(ReportManager::getInstance()->getReports($player->getName())) + 1;
		ReportManager::getInstance()->addReport(
			$player->getName(),
			$target,
			$raison,
			$player->hasPermission(Permissions::ARKANIA_STAFF),
			(string) $count
		);
		$webhook = new Webhook(Main::ADMIN_URL);
		$message = new Message();
		$embed = new Embed();
		$embed->setTitle('**NEW - REPORT**')
			->setContent('- Un nouveau report vient d\'avoir lieu.' . PHP_EOL . PHP_EOL . '*Informations:*' . PHP_EOL . '- Joueur: **' . $player->getName() . '**' . PHP_EOL . '- Cible: **' . $target . '**' . PHP_EOL . '- Raison: **' . $raison . '**' . PHP_EOL . '- Nombre de reports: **' . $count . '**' . PHP_EOL . '- Date: **' . Date::create()->toString() . '**')
			->setFooter('Arkania - Report')
			->setcolor(0x6F4392)
			->setImage();
		$message->addEmbed($embed);
		$webhook->send($message);
		$player->sendMessage(CustomTranslationFactory::arkania_report_success($target, $raison));
	}
}
