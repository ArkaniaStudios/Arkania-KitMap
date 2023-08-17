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

namespace arkania\player;

use arkania\language\CustomTranslationFactory;
use arkania\language\Language;
use arkania\language\LanguageManager;
use arkania\utils\Utils;
use pocketmine\lang\LanguageNotFoundException;
use pocketmine\lang\Translatable;
use pocketmine\player\Player;

class CustomPlayer extends Player {
	/** @var string[] */
	private array $inventorie = [];

	/** @var string[] */
	private array $logs = [];

    private array $moneyZone = [];

    private ?string $lastMessage = null;

	public function getLanguage() : Language {
		return LanguageManager::getInstance()->getPlayerLanguage($this);
	}

	public function sendMessage(Translatable|string $message, bool $usePrefix = true) : void {
		if ($message instanceof Translatable) {
			if (Utils::getPrefix() === null) {
				$message = $this->getLanguage()->translate($message);
			} else {
				if ($usePrefix) {
                    $message = Utils::getPrefix() . $this->getLanguage()->translate($message);
                } else {
                    $message = $this->getLanguage()->translate($message);
                }
			}
		}
		parent::sendMessage($message);
	}

    public function disconnect(Translatable|string $reason, Translatable|string|null $quitMessage = null, Translatable|string|null $disconnectScreenMessage = null): void {
        if ($reason instanceof Translatable){
            $reason = $this->getLanguage()->translate($reason);
        }
        parent::disconnect($reason, $quitMessage, $disconnectScreenMessage);
    }

    public function setLanguage(string $language) : void {
		try {
			LanguageManager::getInstance()->setPlayerLanguage($this, $language);
			$this->sendMessage(CustomTranslationFactory::arkania_language_changed($language));
		} catch (LanguageNotFoundException $e) {
			$this->sendMessage(CustomTranslationFactory::arkania_language_not_found($e->getMessage()));
		}
	}

	public function removeInventory() : void {
		if (isset($this->inventorie[$this->getName()])) {
			unset($this->inventorie[$this->getName()]);
		}
	}

	public function setInventory(string $inventory) : void {
		$this->inventorie[$this->getName()] = $inventory;
	}

	public function isInInventory() : bool {
		return isset($this->inventorie[$this->getName()]);
	}

	public function isInLogs() : bool {
		return isset($this->logs[$this->getName()]);
	}

	public function setLogs(bool $logs) : void {
		$this->logs[$this->getName()] = $logs;
	}

	public function removeLogs() : void {
		if (isset($this->logs[$this->getName()])) {
			unset($this->logs[$this->getName()]);
		}
	}

    public function getRank() : string {
        $config = PlayerManager::getInstance()->getPlayerData($this->getName());
        return $config->get('rank', 'Joueur');
    }

    public function setInMoneyZone(bool $moneyZone) : void {
        $this->moneyZone[$this->getName()] = $moneyZone;
    }

    public function isInMoneyZone() : bool {
        return isset($this->moneyZone[$this->getName()]) && $this->moneyZone[$this->getName()] === true;
    }

    public function setLastMessage(string $playerName) : void {
        $this->lastMessage = $playerName;
    }

    public function getLastMessage() : ?string {
        return $this->lastMessage;
    }

}
