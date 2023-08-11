<?php
declare(strict_types=1);

namespace arkania\form;

use arkania\form\base\BaseForm;
use arkania\form\base\BaseOption;
use arkania\Main;
use arkania\player\CustomPlayer;
use Closure;
use DaveRandom\CallbackValidator\InvalidCallbackException;
use pocketmine\player\Player;
use pocketmine\utils\Utils;
use TypeError;

class MenuForm extends BaseForm {

    private string $content;

    /** @var BaseOption[]  */
    private array $buttons;

    private Closure $onSubmit;

    private ?Closure $onClose = null;

    /**
     * @param string $title
     * @param string $content
     * @param BaseOption[] $buttons
     * @param Closure $onSubmit
     * @param Closure|null $onClose
     */
    public function __construct(
        string $title,
        string $content,
        array $buttons,
        Closure $onSubmit,
        ?Closure $onClose = null
    ) {
        parent::__construct($title);
        $this->content = $content;
        $this->buttons = array_values($buttons);
        try {
            Utils::validateCallableSignature(function (CustomPlayer $player, int $data): void {}, $onSubmit);
        }catch (TypeError|InvalidCallbackException $e) {
            Main::getInstance()->getLogger()->info('Invalid callback passed to MenuForm: ' . $e->getMessage());
        }
        $this->onSubmit = $onSubmit;
        if ($onClose !== null) {
            try {
                Utils::validateCallableSignature(function (CustomPlayer $player): void {}, $onClose);
            }catch (TypeError|InvalidCallbackException $e) {
                Main::getInstance()->getLogger()->info('Invalid callback passed to MenuForm: ' . $e->getMessage());
            }
            $this->onClose = $onClose;
        }
    }

    public function getOption(int $position): ?BaseOption {
        return $this->buttons[$position] ?? null;
    }

    public function getType(): string {
        return 'form';
    }

    /**
     * @return (string|mixed)[]
     */
    public function serializeFormData(): array {
        return [
            'content' => $this->content,
            'buttons' => $this->buttons
        ];
    }

    public function handleResponse(Player $player, $data): void {
        if ($data === null) {
            if ($this->onClose !== null) {
                ($this->onClose)($player);
            }
            return;
        }
        ($this->onSubmit)($player, $data);
    }
}