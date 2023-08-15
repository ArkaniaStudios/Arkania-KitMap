<?php
declare(strict_types=1);

namespace arkania\commands\staff;

use arkania\api\commands\BaseCommand;
use arkania\commands\staff\subCommands\Position1SubCommand;
use arkania\language\CustomTranslationFactory;
use arkania\permissions\Permissions;
use arkania\player\CustomPlayer;
use pocketmine\command\CommandSender;
use pocketmine\command\utils\InvalidCommandSyntaxException;

class SetMoneyZoneCommand extends BaseCommand {

    public function __construct() {
        parent::__construct(
            'setmoneyzone',
            CustomTranslationFactory::arkania_moneyzone_description(),
            '/setmoneyzone <pos1|pos2|create>',
            [
                new Position1SubCommand()
            ],
            permission: Permissions::ARKANIA_SETMONEYZONE
        );
    }

    protected function registerArguments(): array {
        return [];
    }

    public function onRun(CommandSender $player, string $commandLabel, array $parameters): void {
        if (!$player instanceof CustomPlayer) return;

        throw new InvalidCommandSyntaxException();
    }

}