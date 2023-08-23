<?php
declare(strict_types=1);

namespace arkania\rankup;

use arkania\player\PlayerManager;
use JsonException;
use pocketmine\utils\SingletonTrait;

class RankUpManager {
    use SingletonTrait;

    private RankUpInfo $config;

    public function getConfig() : RankUpInfo {
        if (isset($this->config)){
            return $this->config;
        }
        return $this->config = RankUpInfo::create()
            ->addRank(RankUpData::create()
                ->setName('Cooper')
                ->setColor('§6')
                ->setNextStep(0, 10, 30))
            ->addRank(RankUpData::create()
                ->setName('Iron')
                ->setColor('§7')
                ->setNextStep(50, 75, 100))
            ->addRank(RankUpData::create()
                ->setName('Gold')
                ->setColor('§e')
                ->setNextStep(150, 200, 275))
            ->addRank(RankUpData::create()
                ->setName('Diamond')
                ->setColor('§b')
                ->setNextStep(350, 450, 550))
            ->addRank(RankUpData::create()
                ->setName('Emerald')
                ->setColor('§a')
                ->setNextStep(650, 750, 850))
            ->addRank(RankUpData::create()
                ->setName('Netherite')
                ->setColor('§5')
                ->setNextStep(1000));
    }

    /**
     * @throws JsonException
     */
    public function setPlayerRankUp(string $player, string $rank, string $color, int $level) : void {
        $path = PlayerManager::getInstance()->getPlayerData($player);
        $path->set('rankup', [
            'rank' => $rank,
            'level' => $level,
            'color' => $color
        ]);
        $path->save();
    }

}