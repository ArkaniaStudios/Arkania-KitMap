<?php
declare(strict_types=1);

namespace arkania\titles;

class TitleData {

    /** @var Title[] */
    private array $titles = [];

    public static function create() : self {
        return new self;
    }

    public function addTitle(Title $title) : self {
        $this->titles[$title->getName()] = $title;
        return $this;
    }

    public function getTitle(string $name) : ?Title {
        return $this->titles[$name] ?? null;
    }

    public function getTitles() : array {
        return $this->titles;
    }

}