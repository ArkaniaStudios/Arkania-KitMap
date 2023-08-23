<?php
declare(strict_types=1);

namespace arkania\titles;

class Title {

    private string $titleName;
    private string $titleColor;

    public static function create() : self {
        return new self;
    }

    public function setName(string $titleName) : self {
        $this->titleName = $titleName;
        return $this;
    }

    public function setColor(string $titleColor) : self {
        $this->titleColor = $titleColor;
        return $this;
    }

    public function getName(): string {
        return $this->titleName;
    }

    public function getColor(): string {
        return $this->titleColor;
    }

    public function getTitleInfos() : array {
        return [
            'name' => $this->titleName,
            'color' => $this->titleColor
        ];
    }

}