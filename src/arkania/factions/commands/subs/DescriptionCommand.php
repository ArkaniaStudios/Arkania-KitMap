<?php
declare(strict_types=1);

namespace arkania\factions\commands\subs;

use arkania\api\commands\arguments\TextArgument;
use arkania\api\commands\BaseSubCommand;
use arkania\language\CustomTranslationFactory;
use arkania\player\CustomPlayer;
use pocketmine\command\CommandSender;

class DescriptionCommand extends BaseSubCommand {

    public function __construct() {
        parent::__construct(
            'description'
        );
    }

    protected function registerArguments(): array {
        return [
            new TextArgument('description', false)
        ];
    }

    public function onRun(CommandSender $player, array $args): void {
        if (!$player instanceof CustomPlayer) return;

        if (!$player->hasFaction()){
            $player->sendMessage(CustomTranslationFactory::arkania_faction_no_have());
            return;
        }

        $faction = $player->getFaction();
        if (!$faction->isOwner($player->getName())){
            $player->sendMessage(CustomTranslationFactory::arkania_faction_not_owner());
            return;
        }

        $description = $args['description'];
        if (strlen($description) < 1  || strlen($description) > 100){
            $player->sendMessage(CustomTranslationFactory::arkania_faction_description_invalid());
            return;
        }

        $faction->setDescription($description);
        $player->sendMessage(CustomTranslationFactory::arkania_faction_modify_description($description));
    }

}