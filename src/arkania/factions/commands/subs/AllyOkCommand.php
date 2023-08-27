<?php
declare(strict_types=1);

namespace arkania\factions\commands\subs;

use arkania\api\commands\BaseSubCommand;
use arkania\factions\Faction;
use arkania\factions\FactionArgumentInvalidException;
use arkania\language\CustomTranslationFactory;
use arkania\player\CustomPlayer;
use arkania\player\PlayerManager;
use pocketmine\command\CommandSender;

class AllyOkCommand extends BaseSubCommand {

    public function __construct() {
        parent::__construct(
            'allyok'
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

        if (!$player->hasFaction()) {
            $player->sendMessage(CustomTranslationFactory::arkania_faction_no_have());
            return;
        }

        $faction = $player->getFaction();
        if (!$faction->isOwner($player->getName())) {
            $player->sendMessage(CustomTranslationFactory::arkania_faction_not_owner());
            return;
        }
        $factionAlly = $player->getAllyRequest();
        if ($factionAlly === null){
            $player->sendMessage(CustomTranslationFactory::arkania_faction_no_ally_request());
            return;
        }

        $factionAlly = new Faction($factionAlly);
        $factionAlly->getAllyManager()->addAlly($faction);
        $faction->getAllyManager()->addAlly($factionAlly);
        $player->removeAllyRequest();
        $player->sendMessage(CustomTranslationFactory::arkania_faction_ally_request_accepted($factionAlly->getName()));
        $owner = PlayerManager::getInstance()->getPlayerInstance($factionAlly->getOwner());
        $owner?->sendMessage(CustomTranslationFactory::arkania_faction_ally_request_accepted_2($faction->getName()));

    }

}