<?php
declare(strict_types=1);

namespace arkania\report;

use arkania\Main;
use arkania\path\Path;
use arkania\path\PathTypeIds;
use arkania\utils\trait\Date;
use pocketmine\utils\SingletonTrait;

class ReportManager {
    use SingletonTrait;

    public function __construct() {
        if (!file_exists(Main::getInstance()->getDataFolder() . 'reports/')) {
            mkdir(Main::getInstance()->getDataFolder() . 'reports/');
        }
    }

    /**
     * @throws \JsonException
     */
    public function addReport(
        string $player,
        string $reported,
        string $raison,
        bool $isStaff,
        string $count
    ) : void {
        $path = Path::config('reports/' . $player, PathTypeIds::JSON());
        $path->set($count, [
                'reported' => $reported,
                'raison' => $raison,
                'isStaff' => $isStaff,
                'date' => Date::create()->toString()
        ]);
        $path->save();
    }

    public function removeReport(string $player, string $count) : void {
        $path = Path::config('reports/' . $player, PathTypeIds::JSON());
        $path->remove($count);
        $path->save();
    }

    public function getReports(string $player) : array {
        $path = Path::config('reports/' . $player, PathTypeIds::JSON());
        return $path->getAll();
    }

    public function getAllReportFile() : array {
        $file = [];
        foreach (glob(Main::getInstance()->getDataFolder() . 'reports/*.json') as $filename) {
            $file[] = basename($filename);
        }
        return $file;
    }

}