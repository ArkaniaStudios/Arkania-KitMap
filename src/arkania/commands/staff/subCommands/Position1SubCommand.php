<?php
declare(strict_types=1);

namespace arkania\commands\staff\subCommands;

use arkania\api\commands\BaseSubCommand;
use pocketmine\command\CommandSender;

class Position1SubCommand extends BaseSubCommand {

    public function __construct() {
        parent::__construct(
            'pos1'
        );
    }

    protected function registerArguments(): array {
        return [];
    }

    public function onRun(CommandSender $player, string $aliasUsed, array $args): void {
        $player->sendMessage('test');
    }

}