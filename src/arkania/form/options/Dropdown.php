<?php
declare(strict_types=1);

namespace arkania\form\options;

use arkania\form\base\BaseSelector;

class Dropdown extends BaseSelector {

    public function getType() : string{
        return "dropdown";
    }

    protected function serializeElementData() : array{
        return [
            "options" => $this->options,
            "default" => $this->defaultOptionIndex
        ];
    }

}