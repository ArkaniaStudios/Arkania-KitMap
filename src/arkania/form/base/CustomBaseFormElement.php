<?php
declare(strict_types=1);

namespace arkania\form\base;

use JsonSerializable;

abstract class CustomBaseFormElement implements JsonSerializable {

    private string $name;
    private string $text;

    public function __construct(
        string $name,
        string $text
    ) {
        $this->name = $name;
        $this->text = $text;
    }

    abstract public function getType() : string;

    /**
     * @return string
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getText(): string {
        return $this->text;
    }

    abstract public function validateValue(mixed $value) : void;

    /**
     * @return mixed[]
     */
    final public function jsonSerialize(): array {
        $ret = $this->serializeElementData();
        $ret["type"] = $this->getType();
        $ret["text"] = $this->getText();
        return $ret;
    }

    /**
     * @return mixed[]
     */
    abstract protected function serializeElementData() : array;


}