<?php
declare(strict_types=1);

namespace arkania\factions;

use arkania\Main;
use pocketmine\world\Position;
use Symfony\Component\Filesystem\Path;

class HomeManager {

    private Faction $faction;

    public function __construct(
        Faction $faction
    ) {
        $this->faction = $faction;
    }

    public function setFactionHome(Position $position) : void {
        $this->removeFactionHome();
        $this->faction->addValueInData('homes', [
            $position->getX(),
            $position->getY(),
            $position->getZ(),
            $position->getWorld()->getFolderName()
        ]);
    }

    public function removeFactionHome() : void {
        $this->faction->addValueInData('homes', []);
    }

    public function getFactionHome() : ?Position {
        $config = Path::join(Main::getInstance()->getDataFolder(), 'factions', $this->faction->getName() . '.json');
        $data = json_decode(file_get_contents($config), true);
        if (isset($data['homes'])) {
            $home = $data['homes'];
            if (count($home) > 0) {
                return new Position($home[0], $home[1], $home[2], Main::getInstance()->getServer()->getWorldManager()->getWorldByName($home[3]));
            }
        }
        return null;
    }

}