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

namespace arkania\teleportation;

use arkania\language\CustomTranslationFactory;
use arkania\Main;
use arkania\player\CustomPlayer;
use pocketmine\utils\SingletonTrait;

class TeleportationManager {
	use SingletonTrait;

	/** @var string[] */
	private array $teleportation = [];

	public function sendTeleportationToTarget(CustomPlayer $player, CustomPlayer $target) : void {
		$this->teleportation[$player->getName()] = [
			$target->getName(),
			time()
		];
		$player->sendMessage(CustomTranslationFactory::arkania_teleportation_send($target->getName()));
		$target->sendMessage(CustomTranslationFactory::arkania_teleportation_receive($player->getName()));
	}

	public function acceptTeleportationToTarget(CustomPlayer $player) : void {
		if (!isset($this->teleportation[$player->getName()])) {
			$player->sendMessage(CustomTranslationFactory::arkania_teleportation_no_request());
			return;
		}
		$target = $this->teleportation[$player->getName()][0];
		$time = $this->teleportation[$player->getName()][1];
		if (time() - $time > 30) {
			$player->sendMessage(CustomTranslationFactory::arkania_teleportation_expired());
			unset($this->teleportation[$player->getName()]);
			return;
		}
		$target = Main::getInstance()->getServer()->getPlayerExact($target);
		if (!$target instanceof CustomPlayer) {
			$player->sendMessage(CustomTranslationFactory::arkania_teleportation_not_found($target->getName()));
			return;
		}
		$player->teleport($target->getPosition());
		$player->sendMessage(CustomTranslationFactory::arkania_teleportation_accepted_self($target->getName()));
		$target->sendMessage(CustomTranslationFactory::arkania_teleportation_accepted_target($player->getName()));
		unset($this->teleportation[$player->getName()]);
	}

	public function denyTeleportation(CustomPlayer $player) : void {
		if (!isset($this->teleportation[$player->getName()])) {
			$player->sendMessage(CustomTranslationFactory::arkania_teleportation_no_request());
			return;
		}
		$target = $this->teleportation[$player->getName()][0];
		$time = $this->teleportation[$player->getName()][1];
		if (time() - $time > 30) {
			$player->sendMessage(CustomTranslationFactory::arkania_teleportation_expired());
			unset($this->teleportation[$player->getName()]);
			return;
		}
		$target = Main::getInstance()->getServer()->getPlayerExact($target);
		if (!$target instanceof CustomPlayer) {
			$player->sendMessage(CustomTranslationFactory::arkania_teleportation_not_found($target->getName()));
			return;
		}
		$player->sendMessage(CustomTranslationFactory::arkania_teleportation_denied_self($target->getName()));
		$target->sendMessage(CustomTranslationFactory::arkania_teleportation_denied_target($player->getName()));
		unset($this->teleportation[$player->getName()]);
	}

	public function sendTeleportationToPlayer(CustomPlayer $player, CustomPlayer $target) : void {
		$this->teleportation[$target->getName()] = [
			$player->getName(),
			time()
		];
		$player->sendMessage(CustomTranslationFactory::arkania_teleportation_send($target->getName()));
		$target->sendMessage(CustomTranslationFactory::arkania_teleportation_receive($player->getName()));
	}

	public function acceptTeleportationToPlayer(CustomPlayer $player) : void {
		if (!isset($this->teleportation[$player->getName()])) {
			$player->sendMessage(CustomTranslationFactory::arkania_teleportation_no_request());
			return;
		}
		$target = $this->teleportation[$player->getName()][0];
		$time = $this->teleportation[$player->getName()][1];
		if (time() - $time > 30) {
			$player->sendMessage(CustomTranslationFactory::arkania_teleportation_expired());
			unset($this->teleportation[$player->getName()]);
			return;
		}
		$target = Main::getInstance()->getServer()->getPlayerExact($target);
		if (!$target instanceof CustomPlayer) {
			$player->sendMessage(CustomTranslationFactory::arkania_teleportation_not_found($target->getName()));
			return;
		}
		$target->teleport($player->getPosition());
		$player->sendMessage(CustomTranslationFactory::arkania_teleportation_accepted_self($target->getName()));
		$target->sendMessage(CustomTranslationFactory::arkania_teleportation_accepted_target($player->getName()));
		unset($this->teleportation[$player->getName()]);
	}

}
