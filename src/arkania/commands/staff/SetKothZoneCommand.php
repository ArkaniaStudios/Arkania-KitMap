<?php
declare(strict_types=1);

namespace arkania\commands\staff;

use arkania\api\commands\BaseCommand;
use arkania\commands\staff\subCommands\CreateKothZoneSubCommand;
use arkania\commands\staff\subCommands\Position1SubCommand;
use arkania\commands\staff\subCommands\Position2SubCommand;
use arkania\language\CustomTranslationFactory;
use arkania\permissions\Permissions;
use arkania\player\CustomPlayer;
use pocketmine\command\CommandSender;
use pocketmine\command\utils\InvalidCommandSyntaxException;

class SetKothZoneCommand extends BaseCommand {

    public function __construct() {
        parent::__construct(
            'koth',
            CustomTranslationFactory::arkania_koth_description(),
            '/koth <pos1|pos2|create>',
            [
                new CreateKothZoneSubCommand(),
                new Position1SubCommand(),
                new Position2SubCommand()
            ],
            [],
            Permissions::ARKANIA_KOTH
        );
    }

    protected function registerArguments(): array {
        return [];
    }

    public function onRun(CommandSender $player, array $parameters): void {
        if (!$player instanceof CustomPlayer) return;

        throw new InvalidCommandSyntaxException();
    }

}