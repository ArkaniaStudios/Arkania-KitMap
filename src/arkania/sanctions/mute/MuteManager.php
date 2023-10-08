<?php
declare(strict_types=1);

namespace arkania\sanctions\mute;

use arkania\Main;
use pocketmine\utils\SingletonTrait;
use Symfony\Component\Filesystem\Path;

class MuteManager {
    use SingletonTrait;

    /** @var array<string, array> */
    private array $Mutes = [];

    public function __construct() {
        self::setInstance($this);

        if (!file_exists(Main::getInstance()->getDataFolder() . 'sanctions')) {
            mkdir(Main::getInstance()->getDataFolder() . 'sanctions');
        }
        if (!file_exists(Main::getInstance()->getDataFolder() . 'sanctions/mute')) {
            mkdir(Main::getInstance()->getDataFolder() . 'sanctions/mute');
        }
        $data = json_decode(Path::join(Main::getInstance()->getDataFolder(), 'sanctions', 'mute', 'mutes.json'), true);
        if ($data === null) {
            $data = [];
        }
        foreach ($data as $name => $Mute) {
            $this->Mutes[$name] = $Mute;
        }
    }

    public function insertMute(Mute $Mute) : bool {
        if (isset($this->Mutes[$Mute->getName()])) {
            return false;
        }
        $this->Mutes[$Mute->getName()] = $Mute->toArray();
        return true;
    }

    public function removeMute(string $name) : bool {
        if (!isset($this->Mutes[$name])) {
            return false;
        }
        unset($this->Mutes[$name]);
        return true;
    }

    public function getMute(string $name) : ?Mute {
        if (!isset($this->Mutes[$name])) {
            return null;
        }
        return Mute::fromArray($this->Mutes[$name]);
    }

    public function getMutes() : array {
        return $this->Mutes;
    }

    public static function respectTimeFormat(string $time) : bool {
        $time = strtolower($time);
        $time = str_replace([' ', 'd', 'h', 'm', 's'], '', $time);
        return ctype_digit($time);
    }

}