<?php
declare(strict_types=1);

namespace arkania\ranks\elements;

use arkania\Main;
use arkania\ranks\InvalidFormatException;

class RanksFormatInfo {

    private ?string $format = null;

    /**
     * @param string $format
     * @throws InvalidFormatException
     */
    public function __construct(
        string $format
    ) {
        try {
            if (preg_match('/^[a-zA-Z0-9_\-»§ {}\[\]]$/', $format) || strlen($format) > 0){
                $this->format = $format;
            }else{
                throw new InvalidFormatException('Le format du rank est invalid il doit être compris entre les caractères suivant: a-zA-Z0-9_-»§ {}[]');
            }
        }catch (InvalidFormatException $exception) {
            Main::getInstance()->getLogger()->error($exception->getMessage());
            $this->format = null;
        }
    }

    public function getFormat() : ?string {
        return $this->format;
    }

}