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

use arkania\factions\Faction;
use arkania\factions\FactionArgumentInvalidException;
use arkania\language\CustomTranslationFactory;
use arkania\language\Language;
use arkania\language\LanguageManager;
use arkania\path\Path;
use arkania\path\PathTypeIds;
use arkania\ranks\RanksManager;
use arkania\titles\Title;
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

    private bool $factionAdmin = false;

    private bool $chunkView = false;


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

    public function getKills() : int {
        $config = PlayerManager::getInstance()->getPlayerData($this->getName());
        return $config->get('kills', 0);
    }

    public function addKill() : void {
        $config = PlayerManager::getInstance()->getPlayerData($this->getName());
        $config->set('kills', $config->get('kills', 0) + 1);
        $config->save();
    }

    public function getKillsByPlayer(string $player) : int {
        $config = PlayerManager::getInstance()->getPlayerData($player);
        return $config->get('kills', 0);
    }

    public function getDeaths() : int {
        $config = PlayerManager::getInstance()->getPlayerData($this->getName());
        return $config->get('deaths', 0);
    }

    public function getRank() : string {
        $config = PlayerManager::getInstance()->getPlayerData($this->getName());
        return $config->get('rank', 'Joueur');
    }

    public function getRankFullFormat() : string {
        $config = Path::config('ranks/' . $this->getRank(), PathTypeIds::JSON());
        return $config->get('color', '§r').$config->get('rankName', 'Joueur').' §f- ' . $config->get('color', '§r').$this->getName();
    }

    public function getRankUp() : string {
        $config = PlayerManager::getInstance()->getPlayerData($this->getName());
        return $config->get('rankup', [
            'rank' => 'Cooper',
            'level' => 1,
            'color' => '§6'
        ])['rank'];
    }

    public function getFullRankUp() : string {
        $infos = PlayerManager::getInstance()->getPlayerData($this->getName());
        $config = $infos->get('rankup', [
            'rank' => 'Cooper',
            'level' => 1,
            'color' => '§6'
        ]);
        $rank = $config['rank'];
        $level = $config['level'];
        $color = $config['color'];
        return $color.$rank.str_replace(['1', '2', '3'], ['I', 'II', 'III'], (string)$level);
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

    public function addTitle(Title $title) : void {
        $config = PlayerManager::getInstance()->getPlayerData($this->getName());

        $titles = $config->get('titles', []);
        $titles[] = [
            'title' => $title->getName(),
            'color' => $title->getColor()
        ];
        $config->set('titles', $titles);
        $config->save();
    }

    public function setTitle(Title $title) : void {
        $config = PlayerManager::getInstance()->getPlayerData($this->getName());

        $config->set('title', [
            'title' => $title->getName(),
            'color' => $title->getColor()
        ]);
        $config->save();
        RanksManager::getInstance()->updateNametag($this->getRank(), $this);
    }

    public function getTitle() : array {
        $config = PlayerManager::getInstance()->getPlayerData($this->getName());
        return $config->get('title', [
            'title' => 'Aucun',
            'color' => '§e'
        ]);
    }

    public function getTitles() : array {
        $config = PlayerManager::getInstance()->getPlayerData($this->getName());
        return $config->get('titles', []);
    }

    public function hasTitle(string $title) : bool {
        $config = PlayerManager::getInstance()->getPlayerData($this->getName());
        return in_array($title, $config->get('titles', []));
    }

    public function setFaction(Faction $faction) : void {
        $config = PlayerManager::getInstance()->getPlayerData($this->getName());
        $config->set('faction', $faction->getName());
        $config->save();
    }

    public function getFaction() : ?Faction {
        $config = PlayerManager::getInstance()->getPlayerData($this->getName());
        $factionName = $config->get('faction', null);
        if ($factionName === null) {
            return null;
        }
        try{
            return Faction::getFaction($factionName);
        }catch (FactionArgumentInvalidException $e) {
            return null;
        }
    }

    public function hasFaction() : bool {
        $config = PlayerManager::getInstance()->getPlayerData($this->getName());
        return $config->get('faction', null) !== null;
    }

    public function setFactionAdmin(bool $value = true) : void {
        if ($value) {
            $this->factionAdmin = true;
        }else{
            $this->factionAdmin = false;
        }
    }

    public function isFactionAdmin() : bool {
        return $this->factionAdmin;
    }

    public function setChunkView(bool $value = true) : void {
        if ($value) {
            $this->chunkView = true;
        }else{
            $this->chunkView = false;
        }
    }

    public function isChunkView() : bool {
        return $this->chunkView;
    }

}