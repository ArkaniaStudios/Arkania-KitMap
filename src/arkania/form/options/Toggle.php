<?php
declare(strict_types=1);

namespace arkania\form\options;

use arkania\form\base\CustomBaseFormElement;

class Toggle extends CustomBaseFormElement {

    /** @var bool */
    private bool $default;

    public function __construct(string $name, string $text, bool $defaultValue = false){
        parent::__construct($name, $text);
        $this->default = $defaultValue;
    }

    public function getType() : string{
        return "toggle";
    }

    public function getDefaultValue() : bool{
        return $this->default;
    }

    public function validateValue($value) : void{
        if(!is_bool($value)){
            throw new \InvalidArgumentException("Expected bool, got " . gettype($value));
        }
    }

    protected function serializeElementData() : array{
        return [
            "default" => $this->default
        ];
    }

}