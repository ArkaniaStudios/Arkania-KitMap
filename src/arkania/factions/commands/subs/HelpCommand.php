<?php
declare(strict_types=1);

namespace arkania\factions\commands\subs;

use arkania\api\commands\BaseSubCommand;
use arkania\language\CustomTranslationFactory;
use arkania\player\CustomPlayer;
use pocketmine\command\CommandSender;

class HelpCommand extends BaseSubCommand {

    public function __construct() {
        parent::__construct(
            'help'
        );
    }

    protected function registerArguments(): array {
        return [];
    }

    public function onRun(CommandSender $player, array $args) : void {
        if (!$player instanceof CustomPlayer) return;

        $player->sendMessage(CustomTranslationFactory::arkania_faction_help());

    }
}