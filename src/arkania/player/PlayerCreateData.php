<?php
declare(strict_types=1);

namespace arkania\player;

use arkania\path\Path;
use arkania\path\PathTypeIds;
use JsonException;

class PlayerCreateData implements \JsonSerializable {

    private string $name;

    public function __construct(
        string $name,
    ) {
        $this->name = $name;
    }

    /**
     * @throws JsonException
     */
    public function create(): bool {
        $config = Path::config('players/' . $this->name, PathTypeIds::JSON());
        if (empty($config->getAll())) {
            $config->setAll($this->jsonSerialize());
            $config->save();
            return true;
        }
        return false;
    }

    /**
     * @return string[]
     */
    public function jsonSerialize(): array {
        return [
            'name' => $this->name,
            'rank' => 'Joueur',
            'permissions' => [],
            'ban' => 0,
            'mute' => 0,
            'kick' => 0,
            'warn' => 0
        ];
    }
}