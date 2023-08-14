<?php
declare(strict_types=1);

namespace arkania\commands\staff;

use arkania\api\commands\arguments\StringArgument;
use arkania\api\commands\BaseCommand;
use arkania\language\CustomTranslationFactory;
use arkania\Main;
use arkania\permissions\Permissions;
use arkania\utils\trait\Date;
use arkania\webhook\Embed;
use arkania\webhook\Message;
use arkania\webhook\Webhook;
use pocketmine\command\CommandSender;
use pocketmine\command\utils\InvalidCommandSyntaxException;

class DeopCommand extends BaseCommand {

    public function __construct() {
        parent::__construct(
            'deop',
            CustomTranslationFactory::arkania_deop_description(),
            '/deop <player: target>',
            permission: Permissions::ARKANIA_DEOP
        );
    }

    protected function registerArguments(): array {
        return [
            new StringArgument('target', false)
        ];
    }

    public function onRun(CommandSender $player, string $commandLabel, array $parameters): void {
        if (count($parameters) !== 1) {
            throw new InvalidCommandSyntaxException();
        }

        $target = $parameters['target'];

        if($this->getMain()->getServer()->isOp($target)) {
            $this->getMain()->getServer()->removeOp($target);
            $player->sendMessage(CustomTranslationFactory::arkania_deop_success($target));
            $webhook = new Webhook(Main::ADMIN_URL);
            $message = new Message();
            $embed = new Embed();
            $embed->setTitle('**DEOP - PLAYER**')
                ->setContent('- Le joueur **' . $target . '** vient d\'être deop.' . PHP_EOL . PHP_EOL . '*Informations:*' . PHP_EOL . '- Staff: **' . $player->getName() . '**' . PHP_EOL . '- Joueur: **' . $target . '**' . PHP_EOL . '- Date: **' . Date::create()->toString() . '**')
                ->setFooter('Arkania - Opérateur')
                ->setColor(0xEF054F)
                ->setImage();
            $message->addEmbed($embed);
            $webhook->send($message);
            self::sendLogs($player, 'vient de deop ' . $target);
        } else {
            $player->sendMessage(CustomTranslationFactory::arkania_deop_already($target));
        }
    }
}