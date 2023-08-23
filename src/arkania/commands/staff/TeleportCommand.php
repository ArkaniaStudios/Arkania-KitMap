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

class TeleportCommand extends BaseCommand {

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

    public function onRun(CommandSender $player, array $parameters): void {
        if (!$player instanceof CustomPlayer) return;

        $target = $parameters['target'];
        if ($target === '@a'){
            $player->sendMessage('§cThis command is not available for all players');
            return;
        }
        $target = PlayerManager::getInstance()->getPlayerInstance($target);
        $player->teleport($target?->getPosition());
        $player->sendMessage(CustomTranslationFactory::arkania_teleport_success($target->getName()));
    }

}