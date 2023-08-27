<?php
declare(strict_types=1);

namespace arkania\factions\commands\subs;

use arkania\api\commands\BaseSubCommand;
use arkania\language\CustomTranslationFactory;
use arkania\player\CustomPlayer;
use pocketmine\command\CommandSender;
use pocketmine\world\Position;

class SetHomeCommand extends BaseSubCommand {

    public function __construct() {
        parent::__construct(
            'sethome'
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
        if (!$faction->isOwner($player->getName())) {
            $player->sendMessage(CustomTranslationFactory::arkania_faction_not_owner());
            return;
        }
        $faction->getHomeManager()->setFactionHome($player->getPosition());
        $player->sendMessage(CustomTranslationFactory::arkania_faction_home_set());

    }

}