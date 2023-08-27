<?php
declare(strict_types=1);

namespace arkania\commands\staff;

use arkania\api\commands\arguments\TargetArgument;
use arkania\api\commands\arguments\TextArgument;
use arkania\api\commands\BaseCommand;
use arkania\language\CustomTranslationFactory;
use arkania\Main;
use arkania\permissions\Permissions;
use arkania\player\CustomPlayer;
use arkania\player\PlayerManager;
use arkania\webhook\Embed;
use arkania\webhook\Message;
use arkania\webhook\Webhook;
use pocketmine\command\CommandSender;
use pocketmine\Server;

class KickCommand extends BaseCommand {

    public function __construct() {
        parent::__construct(
            'kick',
            CustomTranslationFactory::arkania_kick_description(),
            '/kick <player> <raison>',
            permission: Permissions::ARKANIA_KICK
        );
    }

    protected function registerArguments(): array {
        return [
            new TargetArgument('target'),
            new TextArgument('raison')
        ];
    }

    public function onRun(CommandSender $player, array $parameters): void {
        if (!$player instanceof CustomPlayer) return;

        $target = PlayerManager::getInstance()->getPlayerInstance($parameters['target']);
        if ($target === null) {
            $player->sendMessage(CustomTranslationFactory::arkania_player_not_found($parameters['target']));
            return;
        }

        $target->kick($parameters['raison']);
        Server::getInstance()->broadcastMessage(CustomTranslationFactory::arkania_kick_broadcast($target->getName(), $player->getName()));
        $webhook = new Webhook(Main::ADMIN_URL);
        $message = new Message();
        $embed = new Embed();
        $embed->setTitle('**SANCTION - KICK**')
            ->setContent('**Joueur : **' . $target->getName() . "\n" . '**Raison : **' . $parameters['raison'] . "\n" . '**Sanctionneur : **' . $player->getName())
            ->setFooter('Arkania - Sanction')
            ->setColor(0xF45242)
            ->setImage();
        $message->addEmbed($embed);
        $webhook->send($message);
    }

}