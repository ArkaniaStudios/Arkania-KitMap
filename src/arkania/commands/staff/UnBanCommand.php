<?php
declare(strict_types=1);

namespace arkania\commands\staff;

use arkania\api\commands\arguments\StringArgument;
use arkania\api\commands\BaseCommand;
use arkania\language\CustomTranslationFactory;
use arkania\permissions\Permissions;
use arkania\player\CustomPlayer;
use arkania\sanctions\ban\BanManager;
use arkania\utils\Utils;
use pocketmine\command\CommandSender;

class UnBanCommand extends BaseCommand {

    public function __construct() {
        parent::__construct(
            'unban',
            CustomTranslationFactory::arkania_unban_description(),
            '/unban <player>',
            [],
            [],
            Permissions::ARKANIA_UNBAN
        );
    }

    protected function registerArguments(): array {
        return [
            new StringArgument('player')
        ];
    }

    public function onRun(CommandSender $player, array $parameters): void {
        if (!$player instanceof CustomPlayer) return;

        $target = $parameters['player'];
        if (BanManager::getInstance()->getBan($target) !== null) {
            BanManager::getInstance()->removeBan($target);
            $player->sendMessage(Utils::getPrefix() . "§aVous avez débanni §e" . $target . "§a.");
        }else
            $player->sendMessage(Utils::getPrefix() . "§cCette personne n'est pas banni.");

    }

}