<?php
declare(strict_types=1);

namespace arkania\form\base;

use InvalidArgumentException;

abstract class BaseSelector extends CustomBaseFormElement {

    /** @var int */
    protected int $defaultOptionIndex;
    /** @var string[] */
    protected array $options;

    /**
     * @param string[] $options
     *
     * @throws InvalidArgumentException
     */
    public function __construct(string $name, string $text, array $options, int $defaultOptionIndex = 0){
        parent::__construct($name, $text);
        $this->options = array_values($options);

        if(!isset($this->options[$defaultOptionIndex])){
            throw new InvalidArgumentException("No option at index $defaultOptionIndex, cannot set as default");
        }
        $this->defaultOptionIndex = $defaultOptionIndex;
    }

    public function validateValue($value) : void{
        if(!is_int($value)){
            throw new InvalidArgumentException("Expected int, got " . gettype($value));
        }
        if(!isset($this->options[$value])){
            throw new InvalidArgumentException("Option $value does not exist");
        }
    }

    /**
     * Returns the text of the option at the specified index, or null if it doesn't exist.
     */
    public function getOption(int $index) : ?string{
        return $this->options[$index] ?? null;
    }

    public function getDefaultOptionIndex() : int{
        return $this->defaultOptionIndex;
    }

    public function getDefaultOption() : string{
        return $this->options[$this->defaultOptionIndex];
    }

    /**
     * @return string[]
     */
    public function getOptions() : array{
        return $this->options;
    }

}