<?php
declare(strict_types=1);

namespace arkania\form\image;

class ButtonIcon implements \JsonSerializable {

    public const IMAGE_TYPE_URL = 'url';
    public const IMAGE_TYPE_PATH = 'path';

    private string $type;

    private string $image;

    public function __construct(
        string $type,
        string $image
    ) {
        $this->type = $type;
        $this->image = $image;
    }


    /**
     * @return (string|mixed)[]
     */
    public function jsonSerialize(): array {
        return ['type' => $this->type, 'data' => $this->image];
    }
}