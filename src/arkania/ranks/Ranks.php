<?php
declare(strict_types=1);

namespace arkania\ranks;

use arkania\path\Path;
use arkania\path\PathTypeIds;
use arkania\ranks\elements\RanksFormatInfo;
use arkania\ranks\elements\RanksPermissions;
use JsonException;
use JsonSerializable;

class Ranks implements JsonSerializable {

    /** @var string */
    private string $rankName;

    /** @var RanksFormatInfo */
    private RanksFormatInfo $format;

    /** @var RanksFormatInfo */
    private RanksFormatInfo $nametag;

    /** @var RanksPermissions|null */
    private RanksPermissions|null $permissions;

    /** @var string */
    private string $color;

    /** @var bool */
    private bool $default;

    public function __construct(
        string $rankName,
        RanksFormatInfo $format,
        RanksFormatInfo $nametag,
        ?RanksPermissions $permissions = null,
        string $color,
        bool $default = false
    ) {
        $this->rankName = $rankName;
        $this->format = $format;
        $this->nametag = $nametag;
        $this->permissions = $permissions;
        $this->color = $color;
        $this->default = $default;
    }

    public function getName(): string {
        return $this->rankName;
    }

    public function getRankFormatInfo(): RanksFormatInfo {
        return $this->format;
    }

    public function getRankNametagFormatInfo(): RanksFormatInfo {
        return $this->nametag;
    }

    public function getRanksPermissions(): ?RanksPermissions {
        return $this->permissions;
    }

    public function getColor(): string {
        return $this->color;
    }

    public function isDefault(): bool {
        return $this->default;
    }

    /**
     * @return bool
     * @throws JsonException
     */
    public function create() : bool {
        $config = Path::config('ranks/' . $this->getName(), PathTypeIds::JSON());
        if (empty($config->getAll())) {
            $config->setAll($this->jsonSerialize());
            $config->save();
            return true;
        }
        return false;
    }

    public function jsonSerialize(): array {
        return [
            "rankName" => $this->rankName,
            "format" => $this->format->getFormat(),
            "nametag" => $this->nametag->getFormat(),
            "permissions" => $this->permissions,
            "color" => $this->color,
            "default" => $this->default
        ];
    }
}