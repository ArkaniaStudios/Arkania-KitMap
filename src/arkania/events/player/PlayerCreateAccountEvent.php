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

namespace arkania\events\player;

use arkania\economy\EconomyManager;
use arkania\Main;
use arkania\utils\trait\Date;
use arkania\webhook\Embed;
use arkania\webhook\Message;
use arkania\webhook\Webhook;
use JsonException;
use pocketmine\event\Listener;

class PlayerCreateAccountEvent implements Listener {
	/**
	 * @throws JsonException
	 */
	public function onPlayerCreateAccount(\arkania\economy\events\PlayerCreateAccountEvent $event) : void {
		$player = $event->getPlayer();
		EconomyManager::getInstance()->createAccount($player->getName());
		$webhook = new Webhook(Main::ADMIN_URL);
		$message = new Message();
		$embed = new Embed();
		$embed->setTitle('**ACCOUNT-MONEY - CREATION**')
			->setContent('- **' . $player->getName() . '** vient de se voir crée son compte en banque.' . PHP_EOL . PHP_EOL . '*Informations :*' . PHP_EOL . '- Solde: **1000**' . PHP_EOL . '- Date de création: **' . Date::create()->toString() . '**' . PHP_EOL . '- Server: **KitMap**')
			->setFooter('Arkania - Economy')
			->setColor(0x05E4EF);
		$message->addEmbed($embed);
		$webhook->send($message);
	}
}
