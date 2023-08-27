<?php
declare(strict_types=1);

namespace arkania\factions\commands\subs;

use arkania\api\commands\arguments\TargetArgument;
use arkania\api\commands\BaseSubCommand;
use arkania\language\CustomTranslationFactory;
use arkania\player\CustomPlayer;
use arkania\player\PlayerManager;
use pocketmine\command\CommandSender;

class InviteCommand extends BaseSubCommand {

    public function __construct() {
        parent::__construct(
            'invite'
        );
    }

    protected function registerArguments(): array {
        return [
            new TargetArgument('player', false)
        ];
    }

    public function onRun(CommandSender $player, array $args): void {
        if (!$player instanceof CustomPlayer) return;

        if (!$player->hasFaction()) {
            $player->sendMessage(CustomTranslationFactory::arkania_faction_no_have());
            return;
        }

        $faction = $player->getFaction();
        if (!($faction->isOwner($player->getName()) || $faction->isOfficier($player->getName()))) {
            $player->sendMessage(CustomTranslationFactory::arkania_faction_no_permission());
            return;
        }

        $target = PlayerManager::getInstance()->getPlayerInstance($args['player']);
        if (is_null($target)) {
            $player->sendMessage(CustomTranslationFactory::arkania_player_not_found($args['player']));
            return;
        }
        $targetName = $target->getName();
        if ($faction->isMember($targetName)){
            $player->sendMessage(CustomTranslationFactory::arkania_faction_already_member($targetName));
            return;
        }

        if (!$target->isInvited()) {
            $target->addFactionInvite($faction->getName());
            $player->sendMessage(CustomTranslationFactory::arkania_faction_invite_send($targetName));
            $target->sendMessage(CustomTranslationFactory::arkania_faction_invite_receive($faction->getName(), $player->getName()));
        }else{
            $player->sendMessage(CustomTranslationFactory::arkania_faction_already_invited($targetName));
        }
    }

}