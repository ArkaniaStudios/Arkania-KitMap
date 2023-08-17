<?php
declare(strict_types=1);

namespace arkania\form;

use arkania\form\base\BaseForm;
use arkania\form\base\CustomBaseFormElement;
use arkania\form\options\CustomFormResponse;
use arkania\player\CustomPlayer;
use Closure;
use InvalidArgumentException;
use pocketmine\player\Player;
use pocketmine\utils\Utils;

class CustomMenuForm extends BaseForm {

    /** @var CustomBaseFormElement[] */
    private array $elements;
    /**
     * @var CustomBaseFormElement[]
     */
    private array $elementMap = [];

    private Closure $onSubmit;

    private ?Closure $onClose;

    /**
     * @param string $title
     * @param CustomBaseFormElement[] $elements
     * @param Closure $onSubmit
     * @param Closure|null $onClose
     */
    public function __construct(
        string $title,
        array $elements,
        Closure $onSubmit,
        Closure $onClose = null
    ) {
        parent::__construct($title);
        $this->elements = array_values($elements);
        foreach($this->elements as $element){
            if(isset($this->elementMap[$element->getName()])){
                throw new \InvalidArgumentException("Multiple elements cannot have the same name, found \"" . $element->getName() . "\" more than once");
            }
            $this->elementMap[$element->getName()] = $element;
        }
        Utils::validateCallableSignature(function(CustomPlayer $player, CustomFormResponse $response) : void{}, $onSubmit);
        $this->onSubmit = $onSubmit;
        if($onClose !== null){
            Utils::validateCallableSignature(function(CustomPlayer $player) : void{}, $onClose);
            $this->onClose = $onClose;
        }
    }

    public function getElement(int $index) : ?CustomBaseFormElement{
        return $this->elements[$index] ?? null;
    }

    public function getElementByName(string $name) : ?CustomBaseFormElement{
        return $this->elementMap[$name] ?? null;
    }

    /**
     * @return CustomBaseFormElement[]
     */
    public function getAllElements() : array{
        return $this->elements;
    }

    public function getType(): string {
        return 'custom_form';
    }

    public function serializeFormData(): array {
        return [
            'content' => $this->elements,
        ];
    }

    public function handleResponse(Player $player, $data): void {
        if($data === null){
            if($this->onClose !== null){
                ($this->onClose)($player);
            }
        }elseif(is_array($data)){
            if(($actual = count($data)) !== ($expected = count($this->elements))){
                throw new InvalidArgumentException("Expected $expected result data, got $actual");
            }

            $values = [];

            foreach($data as $index => $value){
                if(!isset($this->elements[$index])){
                    throw new InvalidArgumentException("Element at offset $index does not exist");
                }
                $element = $this->elements[$index];
                try{
                    $element->validateValue($value);
                }catch(InvalidArgumentException $e){
                    throw new InvalidArgumentException("Validation failed for element \"" . $element->getName() . "\": " . $e->getMessage(), 0, $e);
                }
                $values[$element->getName()] = $value;
            }

            ($this->onSubmit)($player, new CustomFormResponse($values));
        }else{
            throw new InvalidArgumentException("Expected array or null, got " . gettype($data));
        }
    }
}