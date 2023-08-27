<?php
declare(strict_types=1);

namespace arkania\sanctions\ban;

use arkania\Main;
use Symfony\Component\Filesystem\Path;

class Ban {

    private string $name;

    /** @var (string|int|bool|float)[]|null */
    private ?array $infos;

    public function __construct(
        string $name,
        array $infos = null
    ) {
        $this->name = $name;
        $this->infos = $infos;
    }

    public function getBanInfos(): ?array {
        if ($this->infos === null) {
            return BanManager::getInstance()->getBans()[$this->name] ?? null;
        }else{
            return $this->infos;
        }
    }

    public function getName(): string {
        return $this->name;
    }

    public function isBanned(): bool {
        return $this->getBanInfos() !== null;
    }

    public function getReason(): ?string {
        return $this->getBanInfos()['reason'] ?? null;
    }

    public function getSanctioner(): ?string {
        return $this->getBanInfos()['sanctioner'] ?? null;
    }

    public function getSanctionDate(): ?int {
        return $this->getBanInfos()['sanction_date'] ?? null;
    }

    public function getExpirationDate(): ?int {
        return $this->getBanInfos()['expiration_date'] ?? null;
    }

    public function ban() : void {
        $config = Path::join(Main::getInstance()->getDataFolder(), 'sanctions', 'ban', 'bans.json');
        $data = json_decode($config, true);
        if ($data === null) {
            $data = [];
        }
        $data[$this->name] = [
            'reason' => $this->getReason(),
            'sanctioner' => $this->getSanctioner(),
            'sanction_date' => $this->getSanctionDate(),
            'expiration_date' => $this->getExpirationDate()
        ];
        file_put_contents($config, json_encode($data));
    }

    public function unban() : void {
        $config = Path::join(Main::getInstance()->getDataFolder(), 'sanctions', 'ban', 'bans.json');
        $data = json_decode($config, true);
        if ($data === null) {
            $data = [];
        }
        unset($data[$this->name]);
        file_put_contents($config, json_encode($data));
    }

    public function exists() : bool {
        $config = Path::join(Main::getInstance()->getDataFolder(), 'sanctions', 'ban', 'bans.json');
        $data = json_decode($config, true);
        if ($data === null) {
            $data = [];
        }
        return isset($data[$this->name]);
    }

    /**
     * @return array<string, string|int|bool|float>
     */
    public function toArray() : array {
        return [
            'reason' => $this->getReason(),
            'sanctioner' => $this->getSanctioner(),
            'sanction_date' => $this->getSanctionDate(),
            'expiration_date' => $this->getExpirationDate()
        ];
    }

    public static function fromArray(array $array) : Ban {
        return new Ban(
            $array['name'],
            $array
        );
    }

}