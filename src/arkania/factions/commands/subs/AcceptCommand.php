<?php
declare(strict_types=1);

namespace arkania\factions\commands\subs;

use arkania\api\commands\BaseSubCommand;
use arkania\factions\Faction;
use arkania\factions\FactionArgumentInvalidException;
use arkania\factions\FactionManager;
use arkania\language\CustomTranslationFactory;
use arkania\player\CustomPlayer;
use arkania\ranks\RanksManager;
use pocketmine\command\CommandSender;

class AcceptCommand extends BaseSubCommand {

    public function __construct() {
        parent::__construct(
            'accept'
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

        if ($player->isInvited()) {
            if ($player->getFaction() !== null) {
                $player->sendMessage(CustomTranslationFactory::arkania_faction_already_in_faction());
                return;
            }
            $keys = array_keys($player->getFactionInvite());
            $factionName = $keys[0];
            if ($player->getFactionInvite()[$factionName] - time() <= 0){
                $player->sendMessage(CustomTranslationFactory::arkania_faction_invitation_expired());
            }else{
                if (FactionManager::getInstance()->exist($factionName)) {
                    $faction = new Faction($factionName);
                    $faction->addMember($player->getName());
                    $faction->broadCastFactionMessage(Faction::MESSAGE_TYPE_TOAST, CustomTranslationFactory::arkania_faction_broadcast_player_join($player->getName()));
                    $player->setFaction($faction);
                    $player->removeFactionInvite($factionName);
                    $player->sendMessage(CustomTranslationFactory::arkania_faction_invitation_accepted($factionName));
                    RanksManager::getInstance()->updateNametag($player->getRank(), $player);
                }else{
                    $player->sendMessage(CustomTranslationFactory::arkania_faction_not_exists($factionName));
                }
            }
        }else{
            $player->sendMessage(CustomTranslationFactory::arkania_faction_no_invitation());
        }
    }
}