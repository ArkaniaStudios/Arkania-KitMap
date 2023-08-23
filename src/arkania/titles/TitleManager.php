<?php
declare(strict_types=1);

namespace arkania\titles;

use pocketmine\utils\SingletonTrait;

class TitleManager {
    use SingletonTrait;

    private TitleData $titleData;

    public function getTitles() : TitleData {
        if (isset($this->titleData)){
            return $this->titleData;
        }
        return $this->titleData = TitleData::create()
            ->addTitle(
                Title::create()
                    ->setName('Noël')
                    ->setColor('§c')
            )
            ->addTitle(
                Title::create()
                    ->setName('Halloween')
                    ->setColor('§6')
            )
            ->addTitle(
                Title::create()
                    ->setName('Nouveau')
                    ->setColor('§a')
            );
    }

    public function getTitle(string $name) : ?Title {
        foreach ($this->getTitles()->getTitles() as $title) {
            if ($title->getName() === $name) {
                return $title;
            }
        }
        return null;
    }
}