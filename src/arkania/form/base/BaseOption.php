<?php
declare(strict_types=1);

namespace arkania\form\base;

use arkania\form\image\ButtonIcon;
use JsonSerializable;

class BaseOption implements JsonSerializable {

    private string $text;

    private ?ButtonIcon $image;

    public function __construct(
        string $text,
        ?ButtonIcon $image = null
    ) {
        $this->text = $text;
        $this->image = $image;
    }

    public function getText(): string {
        return $this->text;
    }

    public function getImage(): ?ButtonIcon {
        return $this->image;
    }

    /**
     * @return (string|mixed)[]
     */
    public function jsonSerialize(): array {
        $return = [
            'text' => $this->getText()
        ];
        if ($this->getImage() !== null) {
            $return['image'] = $this->getImage();
        }
        return $return;
    }
}