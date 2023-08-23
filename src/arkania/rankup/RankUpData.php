<?php
declare(strict_types=1);

namespace arkania\rankup;

class RankUpData {

    private string $name;
    private string $color;

    /** @var int[] */
    private array $nextStep;

    public static function create() : self {
        return new self;
    }

    public function setName(string $name) : self {
        $this->name = $name;
        return $this;
    }

    public function setColor(string $color) : self {
        $this->color = $color;
        return $this;
    }

    public function setNextStep(...$nextStep) : self {
        foreach ($nextStep as $step) {
            $this->nextStep[] = $step;
        }
        return $this;
    }

    public function getName() : string {
        return $this->name;
    }


    public function getColor() : string {
        return $this->color;
    }

    public function getNextStep() : array {
        return $this->nextStep;
    }
    
}