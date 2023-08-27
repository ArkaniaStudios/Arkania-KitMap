<?php
declare(strict_types=1);

namespace arkania\sanctions\ban;

use arkania\Main;
use pocketmine\utils\SingletonTrait;
use Symfony\Component\Filesystem\Path;

class BanManager {
    use SingletonTrait;

    /** @var array<string, array> */
    private array $bans = [];

    public function __construct() {
        self::setInstance($this);

        if (!file_exists(Main::getInstance()->getDataFolder() . 'sanctions/ban')) {
            mkdir(Main::getInstance()->getDataFolder() . 'sanctions/ban');
        }
        $data = json_decode(Path::join(Main::getInstance()->getDataFolder(), 'sanctions', 'ban', 'bans.json'), true);
        if ($data === null) {
            $data = [];
        }
        foreach ($data as $name => $ban) {
            $this->bans[$name] = $ban;
        }
    }

    public function insertBan(Ban $ban) : bool {
        if (isset($this->bans[$ban->getName()])) {
            return false;
        }
        $this->bans[$ban->getName()] = $ban->toArray();
        return true;
    }

    public function removeBan(string $name) : bool {
        if (!isset($this->bans[$name])) {
            return false;
        }
        unset($this->bans[$name]);
        return true;
    }

    public function getBan(string $name) : ?Ban {
        if (!isset($this->bans[$name])) {
            return null;
        }
        return Ban::fromArray($this->bans[$name]);
    }

    public function getBans() : array {
        return $this->bans;
    }

    public static function respectTimeFormat(string $time) : bool {
        $time = strtolower($time);
        $time = str_replace([' ', 'd', 'h', 'm', 's'], '', $time);
        return ctype_digit($time);
    }

}