<?php
declare(strict_types=1);

namespace arkania\factions\commands\subs;

use arkania\api\commands\arguments\StringArgument;
use arkania\api\commands\BaseSubCommand;
use arkania\factions\Faction;
use arkania\factions\FactionArgumentInvalidException;
use arkania\factions\FactionManager;
use arkania\language\CustomTranslationFactory;
use arkania\permissions\Permissions;
use arkania\player\CustomPlayer;
use arkania\player\PlayerManager;
use pocketmine\command\CommandSender;

class ForceDisbandCommand extends BaseSubCommand {

    public function __construct() {
        parent::__construct(
            'forcedisband'
        );
        $this->setPermission(Permissions::ARKANIA_FACTION_FORCE_DISBAND);
    }

    protected function registerArguments(): array {
        return [
            new StringArgument('factionName', false)
        ];
    }

    /**
     * @throws FactionArgumentInvalidException
     * @throws \JsonException
     */
    public function onRun(CommandSender $player, array $args): void {
        if (!$player instanceof CustomPlayer) return;

        $factionName = $args['factionName'];
        if (!FactionManager::getInstance()->exist($factionName)){
            $player->sendMessage(CustomTranslationFactory::arkania_faction_not_exists($factionName));
            return;
        }
        $faction = new Faction($factionName);
        $faction->disband();
        $player->sendMessage(CustomTranslationFactory::arkania_faction_disbanded($factionName));
        $owner = $faction->getOwner();
        $owner = PlayerManager::getInstance()->getPlayerInstance($owner);
        $owner?->sendMessage(CustomTranslationFactory::arkania_faction_disbanded_by($factionName, $player->getName()));
    }

}