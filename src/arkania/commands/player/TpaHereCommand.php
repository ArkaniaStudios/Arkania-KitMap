<?php
declare(strict_types=1);

namespace arkania\commands\player;

use arkania\api\commands\arguments\TargetArgument;
use arkania\api\commands\BaseCommand;
use arkania\language\CustomTranslationFactory;
use arkania\player\CustomPlayer;
use arkania\teleportation\TeleportationManager;
use pocketmine\command\CommandSender;
use pocketmine\command\utils\InvalidCommandSyntaxException;

class TpaHereCommand extends BaseCommand {

    public function __construct() {
        parent::__construct(
            'tpahere',
            CustomTranslationFactory::arkania_teleportation_tpa_description()
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

        $target = $player->getServer()->getPlayerExact($parameters['target']);

        if (!$target instanceof CustomPlayer){
            $player->sendMessage(CustomTranslationFactory::arkania_player_not_found($target));
            return;
        }
        TeleportationManager::getInstance()->sendTeleportationToPlayer($player, $target);
    }
}