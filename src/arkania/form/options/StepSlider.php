<?php
declare(strict_types=1);

namespace arkania\form\options;

use arkania\form\base\BaseSelector;

class StepSlider extends BaseSelector {

    public function getType() : string{
        return "step_slider";
    }

    protected function serializeElementData() : array{
        return [
            "steps" => $this->options,
            "default" => $this->defaultOptionIndex
        ];
    }

}