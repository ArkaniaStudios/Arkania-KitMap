<?php
declare(strict_types=1);

namespace arkania\rankup;

class RankUpInfo {

    /** @var RankUpData[] */
    private array $ranks = [];

    public static function create() : self {
        return new self;
    }

    public function addRank(RankUpData $data) : self {
        $this->ranks[] = $data;
        return $this;
    }

    public function getRanks() : array {
        return $this->ranks;
    }

}