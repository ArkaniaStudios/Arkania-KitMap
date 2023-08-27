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

namespace arkania\broadcast;

use arkania\Main;
use arkania\player\CustomPlayer;
use pocketmine\lang\Translatable;
use pocketmine\scheduler\ClosureTask;

class BroadCastManager {

	/** @var (string|callable|Translatable)[] */
	private array $message = [];

	/** @var (string|callable|Translatable)[] */
	private array $exceptionMessage = [];

	private int $messageIndex = 0;

	public function __construct(
		private readonly Main $main
	) {}

	public function registerMessage(string|callable|Translatable $message) : void {
		$this->message[] = $message;
	}

	public function registerExceptionMessage(string|callable|Translatable $message, int $time) : void {
		$this->exceptionMessage[] = [$message, $time];
	}

	public function setUp() : void {
		$scheduler = $this->main->getScheduler();
		$scheduler->scheduleRepeatingTask(new ClosureTask(function () {
			if($this->messageIndex >= count($this->message)) {
				$this->messageIndex = 0;
			}
			$message = $this->message[$this->messageIndex];
			if(is_callable($message)) {
				$message = $message();
			}
			if (!$message instanceof Translatable) {
				$this->main->getServer()->broadcastMessage('[§c!§f] ' . $message);
			}else{
				foreach ($this->main->getServer()->getOnlinePlayers() as $player) {
					if ($player instanceof CustomPlayer){
						$player->sendMessage('[§c!§f] ' . $player->getLanguage()->translate($message));
					}
				}
			}
			$this->messageIndex++;
		}), 20 * 60 * 5);
		foreach ($this->exceptionMessage as $exception) {
			/** @var string $message */
			$message = $exception[0];
			/** @var int $time */
			$time = $exception[1];
			$scheduler->scheduleRepeatingTask(new ClosureTask(function () use ($message) {
				if(is_callable($message)) {
					$message = $message();
				}
				if (!$message instanceof Translatable) {
					$this->main->getServer()->broadcastMessage($message);
				}else{
					foreach ($this->main->getServer()->getOnlinePlayers() as $player) {
						if ($player instanceof CustomPlayer){
							$player->sendMessage($message);
						}
					}
				}
			}), $time);
		}
	}
}
