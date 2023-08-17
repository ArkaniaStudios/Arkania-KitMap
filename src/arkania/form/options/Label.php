<?php
declare(strict_types=1);

namespace arkania\form\options;

use arkania\form\base\CustomBaseFormElement;

class Label extends CustomBaseFormElement {

    public function getType() : string{
        return "label";
    }

    public function validateValue(mixed $value) : void{
        assert($value === null);
    }

    protected function serializeElementData() : array{
        return [];
    }

}