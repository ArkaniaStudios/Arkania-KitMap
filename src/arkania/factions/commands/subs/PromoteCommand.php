<?php
declare(strict_types=1);

namespace arkania\factions\commands\subs;

use arkania\api\commands\arguments\StringArgument;
use arkania\api\commands\BaseSubCommand;
use arkania\factions\FactionArgumentInvalidException;
use arkania\language\CustomTranslationFactory;
use arkania\player\CustomPlayer;
use arkania\player\PlayerManager;
use pocketmine\command\CommandSender;

class PromoteCommand extends BaseSubCommand {

    public function __construct() {
        parent::__construct(
            'promote'
        );
    }

    protected function registerArguments(): array {
        return [
            new StringArgument('member', false)
        ];
    }

    /**
     * @throws FactionArgumentInvalidException
     */
    public function onRun(CommandSender $player, array $args): void {
        if (!$player instanceof CustomPlayer) return;

        $member = $args['member'];
        $faction = $player->getFaction();
        if (!$faction?->isMember($member)) {
            $player->sendMessage(CustomTranslationFactory::arkania_faction_not_in_faction($member));
            return;
        }

        if (!$faction?->isOwner($player->getName())) {
            $player->sendMessage(CustomTranslationFactory::arkania_faction_not_owner());
            return;
        }

        if ($faction?->isOfficier($member)) {
            $player->sendMessage(CustomTranslationFactory::arkania_faction_already_officer($member));
            return;
        }

        if ($faction?->getOwner() === $player->getName()){
            $player->sendMessage(CustomTranslationFactory::arkania_faction_cant_promote_self());
            return;
        }

        $faction?->addOfficier($member);
        $player->sendMessage(CustomTranslationFactory::arkania_faction_promoted($member));
        $member = PlayerManager::getInstance()->getPlayerInstance($member);
        $member?->sendMessage(CustomTranslationFactory::arkania_faction_promoted_by($player->getName()));
    }
}