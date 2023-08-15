<?php
declare(strict_types=1);

namespace arkania\commands\staff;

use arkania\api\commands\arguments\StringArgument;
use arkania\api\commands\BaseCommand;
use arkania\language\CustomTranslationFactory;
use arkania\permissions\Permissions;
use arkania\player\PlayerManager;
use pocketmine\command\CommandSender;
use pocketmine\command\utils\InvalidCommandSyntaxException;

class DeleteUserCommand extends BaseCommand{
    public function __construct() {
        parent::__construct(
            'deleteuser',
            CustomTranslationFactory::arkania_deleteuser_description(),
            '/deleteuser <player>',
            [],
            ['deleteu'],
            Permissions::ARKANIA_DELETEUSER
        );
    }

    protected function registerArguments(): array {
        return [
            new StringArgument('target', false)
        ];
    }

    public function onRun(CommandSender $player, string $commandLabel, array $parameters): void {
        if (count($parameters) !== 1){
            throw new InvalidCommandSyntaxException();
        }
        $target = $parameters['target'];
        if (!PlayerManager::getInstance()->exist($target)) {
            $player->sendMessage(CustomTranslationFactory::arkania_player_no_exist($target));
            return;
        }
        PlayerManager::getInstance()->removePlayerData($target);
    }

}