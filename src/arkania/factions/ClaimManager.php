<?php
declare(strict_types=1);

namespace arkania\factions;

use arkania\Main;
use arkania\player\CustomPlayer;
use pocketmine\world\format\Chunk;
use pocketmine\world\Position;
use Symfony\Component\Filesystem\Path;

class ClaimManager {

    /** @var (string|mixed)[] */
    private static array $claims = [];

    private Faction $faction;

    private static string $factionName = '';

    public function __construct(
        Faction $faction
    ) {
        $this->faction = $faction;
    }

    public function addClaim(CustomPlayer $player) : void {
        $position = $player->getPosition();
        $chunkX = $position->getFloorX() >> Chunk::COORD_BIT_SIZE;
        $chunkZ = $position->getFloorZ() >> Chunk::COORD_BIT_SIZE;
        $world = $position->getWorld()->getFolderName();

        $config = Path::join(Main::getInstance()->getDataFolder(), 'factions', $this->faction->getName() . '.json');
        $data = json_decode(file_get_contents($config), true);
        $data['claims'][] = [
            'x' => $chunkX,
            'z' => $chunkZ,
            'world' => $world
        ];
        file_put_contents($config, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        self::$claims[$chunkX . ':' . $chunkZ . ':' . $world] = [
            'x' => $chunkX,
            'z' => $chunkZ,
            'world' => $world,
            'faction' => $this->faction->getName()
            ];
    }

    public function removeAllFactionClaim() : void {
        $config = Path::join(Main::getInstance()->getDataFolder(), 'factions', $this->faction->getName() . '.json');
        $data = json_decode(file_get_contents($config), true);
        $data['claims'] = [];
        file_put_contents($config, json_encode($data));
        self::$claims = [];
    }

    public function removeClaim(CustomPlayer $player) : void {
        $position = $player->getPosition();
        $chunkX = $position->getFloorX() >> Chunk::COORD_BIT_SIZE;
        $chunkZ = $position->getFloorZ() >> Chunk::COORD_BIT_SIZE;
        $world = $position->getWorld()->getFolderName();

        $config = Path::join(Main::getInstance()->getDataFolder(), 'factions', $this->faction->getName() . '.json');
        $data = json_decode(file_get_contents($config), true);
        $data['claims'] = array_filter($data['claims'], function($claim) use ($chunkX, $chunkZ, $world) {
            return $claim['x'] !== $chunkX && $claim['z'] !== $chunkZ && $claim['world'] !== $world;
        });
        unset(self::$claims[$chunkX . ':' . $chunkZ . ':' . $world]);
    }

    public function countClaim() : int {
        $config = Path::join(Main::getInstance()->getDataFolder(), 'factions', $this->faction->getName() . '.json');
        $data = json_decode(file_get_contents($config), true);
        return count($data['claims']);
    }

    public static function isClaimed(Position $position) : bool {
        $chunkX = $position->getFloorX() >> Chunk::COORD_BIT_SIZE;
        $chunkZ = $position->getFloorZ() >> Chunk::COORD_BIT_SIZE;
        $world = $position->getWorld()->getFolderName();
        if (isset(self::$claims[$chunkX . ':' . $chunkZ . ':' . $world])){
            self::setFactionName(self::$claims[$chunkX . ':' . $chunkZ . ':' . $world]['faction']);
            return true;
        }
        return false;
    }

    public static function registerFactionClaim(array $claims, string $factionName) : void {
        foreach ($claims as $claim) {
            $claim['faction'] = $factionName;
            self::$claims[$claim['x'] . ':' . $claim['z'] . ':' . $claim['world']] = $claim;
        }
    }

    private static function setFactionName(string $faction) : void {
        self::$factionName = $faction;
    }

    public static function getFactionName() : string {
        return self::$factionName;
    }

}