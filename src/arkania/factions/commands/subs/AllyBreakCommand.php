<?php
declare(strict_types=1);

namespace arkania\factions\commands\subs;

use arkania\api\commands\arguments\StringArgument;
use arkania\api\commands\BaseSubCommand;
use arkania\factions\Faction;
use arkania\factions\FactionArgumentInvalidException;
use arkania\language\CustomTranslationFactory;
use arkania\player\CustomPlayer;
use pocketmine\command\CommandSender;

class AllyBreakCommand extends BaseSubCommand {

    public function __construct() {
        parent::__construct(
            'allybreak'
        );
    }

    protected function registerArguments(): array {
        return [
            new StringArgument('factionName')
        ];
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

        $factionAlly = new Faction($args['factionName']);
        if (!$faction->getAllyManager()->isAlly($factionAlly)) {
            $player->sendMessage(CustomTranslationFactory::arkania_faction_no_ally($factionAlly->getName()));
            return;
        }

        $faction->getAllyManager()->removeAlly($factionAlly);
        $factionAlly->getAllyManager()->removeAlly($faction);
        $player->sendMessage(CustomTranslationFactory::arkania_faction_ally_break($factionAlly->getName()));
    }

}