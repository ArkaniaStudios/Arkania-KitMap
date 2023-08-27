<?php
declare(strict_types=1);

namespace arkania\area\commands\subs;

use arkania\api\commands\BaseSubCommand;
use arkania\form\FormManager;
use arkania\player\CustomPlayer;
use pocketmine\command\CommandSender;

class ParamCommand extends BaseSubCommand {

    public function __construct() {
        parent::__construct(
            'param'
        );
    }

    protected function registerArguments(): array {
        return [];
    }

    public function onRun(CommandSender $player, array $args): void {
        if (!$player instanceof CustomPlayer) return;

        FormManager::getInstance()->sendAreaParamForm($player);
    }

}