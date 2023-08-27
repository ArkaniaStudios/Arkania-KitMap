<?php
declare(strict_types=1);

namespace arkania\factions\commands\subs;

use arkania\api\commands\BaseSubCommand;
use arkania\language\CustomTranslationFactory;
use arkania\player\CustomPlayer;
use pocketmine\command\CommandSender;

class ClaimCommand extends BaseSubCommand {

    public function __construct() {
        parent::__construct(
            'claim'
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

        if ($faction->getPower() < 1){
            $player->sendMessage(CustomTranslationFactory::arkania_faction_no_power());
            return;
        }

        if ($faction->getClaimManager()->countClaim() > 2) {
            $player->sendMessage(CustomTranslationFactory::arkania_faction_claim_max());
            return;
        }

        $faction->getClaimManager()->addClaim($player);
        $player->sendMessage(CustomTranslationFactory::arkania_faction_claim_success());
    }
}