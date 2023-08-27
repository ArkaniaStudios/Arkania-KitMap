<?php
declare(strict_types=1);

namespace arkania\factions\commands\subs;

use arkania\api\commands\BaseSubCommand;
use arkania\language\CustomTranslationFactory;
use arkania\player\CustomPlayer;
use pocketmine\command\CommandSender;

class HomeCommand extends BaseSubCommand {

    public function __construct() {
        parent::__construct(
            'home'
        );
    }

    protected function registerArguments(): array {
        return [];
    }

    public function onRun(CommandSender $player, array $args): void {
        if (!$player instanceof CustomPlayer) return;

        if (!$player->hasFaction()) {
            $player->sendMessage(CustomTranslationFactory::arkania_faction_no_have());
            return;
        }
        $faction = $player->getFaction();
        $homeManager = $faction->getHomeManager();

        if ($homeManager->getFactionHome() === null) {
            $player->sendMessage(CustomTranslationFactory::arkania_faction_home_not_set());
            return;
        }

        $player->teleport($homeManager->getFactionHome());
        $player->sendMessage(CustomTranslationFactory::arkania_faction_home_teleport());
    }
}