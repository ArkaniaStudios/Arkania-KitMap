<?php
declare(strict_types=1);

namespace arkania\factions\commands\subs;

use arkania\api\commands\BaseSubCommand;
use arkania\factions\FactionArgumentInvalidException;
use arkania\language\CustomTranslationFactory;
use arkania\player\CustomPlayer;
use arkania\ranks\RanksManager;
use pocketmine\command\CommandSender;

class LeaveCommand extends BaseSubCommand {

    public function __construct() {
        parent::__construct(
            'leave'
        );
    }

    protected function registerArguments(): array {
        return [];
    }

    /**
     * @throws FactionArgumentInvalidException
     */
    public function onRun(CommandSender $player, array $args): void {
        if (!$player instanceof CustomPlayer) return;

        if (!$player->hasFaction()){
            $player->sendMessage(CustomTranslationFactory::arkania_faction_no_have());
            return;
        }

        $faction = $player->getFaction();
        if ($faction->isOwner($player->getName())){
            $player->sendMessage(CustomTranslationFactory::arkania_faction_owner_cant_leave());
            return;
        }

        $faction->removeMember($player->getName());
        $player->removeFaction();
        $player->sendMessage(CustomTranslationFactory::arkania_faction_leave($faction->getName()));
        RanksManager::getInstance()->updateNametag($player->getRank(), $player);
    }
}