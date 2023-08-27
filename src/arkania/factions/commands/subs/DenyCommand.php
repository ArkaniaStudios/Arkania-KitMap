<?php
declare(strict_types=1);

namespace arkania\factions\commands\subs;

use arkania\api\commands\BaseSubCommand;
use arkania\language\CustomTranslationFactory;
use arkania\player\CustomPlayer;
use pocketmine\command\CommandSender;

class DenyCommand extends BaseSubCommand {

    public function __construct() {
        parent::__construct(
            'deny'
        );
    }

    protected function registerArguments(): array {
        return [];
    }

    public function onRun(CommandSender $player, array $args): void {
        if (!$player instanceof CustomPlayer) return;

        if (!$player->isInvited()) {
            $player->sendMessage(CustomTranslationFactory::arkania_faction_no_invitation());
            return;
        }

        $keys = array_keys($player->getFactionInvite());
        $factionName = $keys[0];
        if ($player->getFactionInvite()[$factionName] - time() <= 0) {
            $player->sendMessage(CustomTranslationFactory::arkania_faction_invitation_expired());
        }
        $player->removeFactionInvite($factionName);
        $player->sendMessage(CustomTranslationFactory::arkania_faction_invitation_denied($factionName));
    }

}