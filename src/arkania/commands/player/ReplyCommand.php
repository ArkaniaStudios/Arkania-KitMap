<?php
declare(strict_types=1);

namespace arkania\commands\player;

use arkania\api\commands\arguments\TextArgument;
use arkania\api\commands\BaseCommand;
use arkania\language\CustomTranslationFactory;
use arkania\player\CustomPlayer;
use arkania\player\PlayerManager;
use pocketmine\command\CommandSender;

class ReplyCommand extends BaseCommand {

    public function __construct() {
        parent::__construct(
            'reply',
            CustomTranslationFactory::arkania_tell_reply_description(),
            '/r <message>',
            [],
            ['re', 'r']
        );
    }

    protected function registerArguments(): array {
        return [
            new TextArgument('message', false)
        ];
    }

    public function onRun(CommandSender $player, array $parameters): void {
        if ($player instanceof CustomPlayer) {
            if ($player->getLastMessage() === null) {
                $player->sendMessage(CustomTranslationFactory::arkania_tell_reply_no_target());
                return;
            }
            $target = PlayerManager::getInstance()->getPlayerInstance($player->getLastMessage());
            if ($target === null) {
                $player->sendMessage(CustomTranslationFactory::arkania_player_not_found($player->getLastMessage()));
                return;
            }
            $message = $parameters['message'];
            $player->sendMessage(CustomTranslationFactory::arkania_tell_message($target->getName(), $message), false);
            $target->sendMessage(CustomTranslationFactory::arkania_tell_target_message($player->getName(), $message), false);
            $target->setLastMessage($player->getName());
        }
    }

}