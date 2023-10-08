<?php
declare(strict_types=1);

namespace arkania\commands\player;

use arkania\api\commands\BaseCommand;
use arkania\form\FormManager;
use arkania\player\CustomPlayer;
use pocketmine\command\CommandSender;

class KitsCommand extends BaseCommand {

    public function __construct() {
        parent::__construct(
            'kits'
        );
    }

    protected function registerArguments(): array {
        return [];
    }

    public function onRun(CommandSender $player, array $parameters): void {
        if (!$player instanceof CustomPlayer) return;
        FormManager::getInstance()->sendKitForm($player);
    }

}