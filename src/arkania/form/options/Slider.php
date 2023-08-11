<?php
declare(strict_types=1);

namespace arkania\form\options;

use arkania\form\base\CustomBaseFormElement;
use InvalidArgumentException;

class Slider extends CustomBaseFormElement {

    /** @var float */
    private float $min;
    /** @var float */
    private float $max;
    /** @var float */
    private float $step;
    /** @var float */
    private float $default;

    public function __construct(string $name, string $text, float $min, float $max, float $step = 1.0, ?float $default = null){
        parent::__construct($name, $text);

        if($this->min > $this->max){
            throw new InvalidArgumentException("Slider min value should be less than max value");
        }
        $this->min = $min;
        $this->max = $max;

        if($default !== null){
            if($default > $this->max or $default < $this->min){
                throw new InvalidArgumentException("Default must be in range $this->min ... $this->max");
            }
            $this->default = $default;
        }else{
            $this->default = $this->min;
        }

        if($step <= 0){
            throw new InvalidArgumentException("Step must be greater than zero");
        }
        $this->step = $step;
    }

    public function getType() : string{
        return "slider";
    }

    public function validateValue($value) : void{
        if(!is_float($value) and !is_int($value)){
            throw new InvalidArgumentException("Expected float, got " . gettype($value));
        }
        if($value < $this->min or $value > $this->max){
            throw new InvalidArgumentException("Value $value is out of bounds (min $this->min, max $this->max)");
        }
    }

    public function getMin() : float{
        return $this->min;
    }

    public function getMax() : float{
        return $this->max;
    }

    public function getStep() : float{
        return $this->step;
    }

    public function getDefault() : float{
        return $this->default;
    }

    protected function serializeElementData() : array{
        return [
            "min" => $this->min,
            "max" => $this->max,
            "default" => $this->default,
            "step" => $this->step
        ];
    }

}