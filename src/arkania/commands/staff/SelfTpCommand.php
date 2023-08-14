<?php
declare(strict_types=1);

namespace arkania\commands\staff;

use arkania\api\commands\arguments\TargetArgument;
use arkania\api\commands\BaseCommand;
use arkania\language\CustomTranslationFactory;
use arkania\permissions\Permissions;
use arkania\player\CustomPlayer;
use arkania\player\PlayerManager;
use pocketmine\command\CommandSender;
use pocketmine\command\utils\InvalidCommandSyntaxException;

class SelfTpCommand extends BaseCommand {

    public function __construct() {
        parent::__construct(
            'teleport',
            CustomTranslationFactory::arkania_teleport_description(),
            '/teleport <player>',
            aliases: ['tp'],
            permission: Permissions::ARKANIA_TELEPORT
        );
    }

    protected function registerArguments(): array {
        return [
            new TargetArgument('target', false)
        ];
    }

    public function onRun(CommandSender $player, string $commandLabel, array $parameters): void {
        if (!$player instanceof CustomPlayer) return;

        if (count($parameters) !== 1){
            throw new InvalidCommandSyntaxException();
        }

        $target = $parameters['target'];
        if (PlayerManager::getInstance()->isOnline($target) && $target instanceof CustomPlayer){
            $target->teleport($player->getPosition());
            $player->sendMessage(CustomTranslationFactory::arkania_teleport_success_self($target->getName()));
        }
    }

}