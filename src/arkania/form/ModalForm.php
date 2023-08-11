<?php
declare(strict_types=1);

namespace arkania\form;

use arkania\form\base\BaseForm;
use arkania\player\CustomPlayer;
use Closure;
use InvalidArgumentException;
use pocketmine\player\Player;
use pocketmine\utils\Utils;

class ModalForm extends BaseForm {

    /** @var string */
    private string $content;
    /**
     * @var Closure
     */
    private Closure $onSubmit;
    /** @var string */
    private string $button1;
    /** @var string */
    private string $button2;

    /**
     * @param string   $title Text to put on the title of the dialog.
     * @param string   $text Text to put in the body.
     * @param Closure $onSubmit signature `function(Player $player, bool $choice)`
     * @param string   $yesButtonText Text to show on the "Yes" button. Defaults to client-translated "Yes" string.
     * @param string   $noButtonText Text to show on the "No" button. Defaults to client-translated "No" string.
     *
     */
    public function __construct(string $title, string $text, Closure $onSubmit, string $yesButtonText = "gui.yes", string $noButtonText = "gui.no"){
        parent::__construct($title);
        $this->content = $text;
        Utils::validateCallableSignature(function(CustomPlayer $player, bool $choice) : void{}, $onSubmit);
        $this->onSubmit = $onSubmit;
        $this->button1 = $yesButtonText;
        $this->button2 = $noButtonText;
    }

    public function getYesButtonText() : string{
        return $this->button1;
    }

    public function getNoButtonText() : string{
        return $this->button2;
    }

    final public function handleResponse(Player $player, $data) : void{
        if(!is_bool($data)){
            throw new InvalidArgumentException("Expected bool, got " . gettype($data));
        }

        ($this->onSubmit)($player, $data);
    }

    public function getType() : string{
        return "modal";
    }

    public function serializeFormData() : array{
        return [
            "content" => $this->content,
            "button1" => $this->button1,
            "button2" => $this->button2
        ];
    }

}