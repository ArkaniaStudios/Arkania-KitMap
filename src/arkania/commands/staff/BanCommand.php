<?php
declare(strict_types=1);

namespace arkania\commands\staff;

use arkania\api\commands\arguments\StringArgument;
use arkania\api\commands\arguments\TargetArgument;
use arkania\api\commands\arguments\TextArgument;
use arkania\api\commands\BaseCommand;
use arkania\language\CustomTranslationFactory;
use arkania\permissions\Permissions;
use arkania\player\CustomPlayer;
use arkania\sanctions\ban\BanManager;
use pocketmine\command\CommandSender;

class BanCommand extends BaseCommand {

    public function __construct() {
        parent::__construct(
            'ban',
            CustomTranslationFactory::arkania_ban_description(),
            '/ban <player> <time> <raison>',
            permission: Permissions::ARKANIA_BAN
        );
    }

    protected function registerArguments(): array {
        return [
            new TargetArgument('target'),
            new StringArgument('time'),
            new TextArgument('raison')
        ];
    }

    public function onRun(CommandSender $player, array $parameters): void {
        if (!$player instanceof CustomPlayer) return;

        $target = $parameters['target'];
        if (BanManager::getInstance()->getBan($target) !== null) {
            $player->sendMessage(CustomTranslationFactory::arkania_ban_already_banned($target));
            return;
        }

        $time = $parameters['time'];

    }

}