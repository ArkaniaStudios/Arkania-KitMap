<?php
declare(strict_types=1);

namespace arkania\factions\commands\subs;

use arkania\api\commands\BaseSubCommand;
use arkania\factions\FactionArgumentInvalidException;
use arkania\language\CustomTranslationFactory;
use arkania\player\CustomPlayer;
use pocketmine\command\CommandSender;

class DisbandCommand extends BaseSubCommand {

    public function __construct() {
        parent::__construct(
            'disband'
        );
    }

    protected function registerArguments(): array {
        return [];
    }

    /**
     * @throws FactionArgumentInvalidException
     * @throws \JsonException
     */
    public function onRun(CommandSender $player, array $args): void {
        if (!$player instanceof CustomPlayer) return;

        $faction = $player->getFaction();

        if ($faction === null) {
            $player->sendMessage(CustomTranslationFactory::arkania_faction_no_have());
            return;
        }

        if (!$faction->isOwner($player->getName())){
            $player->sendMessage(CustomTranslationFactory::arkania_faction_not_owner());
            return;
        }

        $faction->disband();
        $player->sendMessage(CustomTranslationFactory::arkania_faction_disbanded($faction->getName()));
    }
}