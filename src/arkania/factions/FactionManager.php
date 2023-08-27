<?php
declare(strict_types=1);

namespace arkania\factions;

use arkania\language\CustomTranslationFactory;
use arkania\Main;
use arkania\player\CustomPlayer;
use pocketmine\utils\SingletonTrait;

class FactionManager {
    use SingletonTrait;

    public function __construct() {
        if (!file_exists(Main::getInstance()->getDataFolder() . 'factions/')) {
            mkdir(Main::getInstance()->getDataFolder() . 'factions/');
        }
    }

    public function createFaction(string $factionName, CustomPlayer $owner) : bool {
        try {
            $faction = new Faction($factionName, $owner->getName());
            $owner->setFaction($faction);
            return true;
        }catch (FactionArgumentInvalidException $e) {
            $owner->sendMessage(CustomTranslationFactory::arkania_faction_already_exist($e->getMessage()));
            return false;
        }
    }

    public function exist(string $factionName) : bool {
        return file_exists(Main::getInstance()->getDataFolder() . 'factions/' . $factionName . '.json');
    }

    public static function loadAllClaim() : void {
        $files = scandir(Main::getInstance()->getDataFolder() . 'factions/');
        foreach ($files as $file) {
            if ($file === '.' || $file === '..') continue;
            $data = json_decode(file_get_contents(Main::getInstance()->getDataFolder() . 'factions/' . $file), true);
            ClaimManager::registerFactionClaim($data['claims'], $file);
        }
    }

}