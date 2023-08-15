<?php
declare(strict_types=1);

namespace arkania\commands\player;

use arkania\api\commands\BaseCommand;
use arkania\language\CustomTranslationFactory;
use arkania\player\CustomPlayer;
use arkania\teleportation\TeleportationManager;
use pocketmine\command\CommandSender;

class TpaacceptCommand extends BaseCommand {

    public function __construct() {
        parent::__construct(
            'tpa',
            CustomTranslationFactory::arkania_teleportation_tpaaccept_description()
        );
    }

    protected function registerArguments(): array {
        return [];
    }

    public function onRun(CommandSender $player, string $commandLabel, array $parameters): void {
        if (!$player instanceof CustomPlayer) return;

        TeleportationManager::getInstance()->acceptTeleportationToPlayer($player);
    }
}