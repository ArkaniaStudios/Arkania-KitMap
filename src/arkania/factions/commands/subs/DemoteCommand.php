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

class DemoteCommand extends BaseSubCommand {

    public function __construct() {
        parent::__construct(
            'demote'
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

        if (!$faction?->isOfficier($member)) {
            $player->sendMessage(CustomTranslationFactory::arkania_faction_is_not_officer($member));
            return;
        }

        if ($faction?->getOwner() === $player->getName()){
            $player->sendMessage(CustomTranslationFactory::arkania_faction_cant_demote_self());
            return;
        }

        $faction?->removeOfficier($member);
        $player->sendMessage(CustomTranslationFactory::arkania_faction_demoted($member));
        $member = PlayerManager::getInstance()->getPlayerInstance($member);
        $member?->sendMessage(CustomTranslationFactory::arkania_faction_demoted_by($player->getName()));
    }

}