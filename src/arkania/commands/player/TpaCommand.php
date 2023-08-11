<?php
declare(strict_types=1);

namespace arkania\commands\player;

use arkania\api\BaseCommand;
use arkania\language\CustomTranslationFactory;
use arkania\player\CustomPlayer;
use pocketmine\command\CommandSender;
use pocketmine\command\utils\InvalidCommandSyntaxException;

class TpaCommand extends BaseCommand {

    public function __construct() {
        parent::__construct(
            'tpa',
            CustomTranslationFactory::arkania_teleportation_tpa_description()
        );
    }

    public function execute(CommandSender $player, string $commandLabel, array $args): void {
        if (!$player instanceof CustomPlayer) return;

        if (count($args) !== 1){
            throw new InvalidCommandSyntaxException();
        }

    }

}