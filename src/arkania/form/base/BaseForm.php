<?php
declare(strict_types=1);

namespace arkania\form\base;

use pocketmine\form\Form;

abstract class BaseForm implements Form {

    public function __construct(
        private readonly string $title
    ) {}

    /**
     * @return string
     */
    public function getTitle(): string {
        return $this->title;
    }

    /**
     * @return (mixed|string)[]
     */
    final public function jsonSerialize(): array {
        $return = $this->serializeFormData();
        $return["type"] = $this->getType();
        $return["title"] = $this->getTitle();
        return $return;
    }

    abstract public function getType(): string;

    /**
     * @return (mixed|string)[]
     */
    abstract public function serializeFormData(): array;
}