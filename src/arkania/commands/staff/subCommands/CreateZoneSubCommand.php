<?php
declare(strict_types=1);

namespace arkania\commands\staff\subCommands;

use arkania\api\commands\BaseSubCommand;
use arkania\game\MoneyZoneManager;
use arkania\language\CustomTranslationFactory;
use arkania\player\CustomPlayer;
use pocketmine\command\CommandSender;

class CreateZoneSubCommand extends BaseSubCommand {

    public function __construct() {
        parent::__construct(
            'create'
        );
    }

    protected function registerArguments(): array {
        return [];
    }

    public function onRun(CommandSender $player, string $aliasUsed, array $args): void {
        if (!$player instanceof CustomPlayer) return;

        if (!isset(Position1SubCommand::$pos1['x']) || !isset(Position1SubCommand::$pos1['z']) || !isset(Position2SubCommand::$pos2['x']) || !isset(Position2SubCommand::$pos2['z'])) {
            $player->sendMessage(CustomTranslationFactory::arkania_moneyzone_position_not_set());
            return;
        }

        MoneyZoneManager::getInstance()->setPositions(Position1SubCommand::$pos1, Position2SubCommand::$pos2);
        MoneyZoneManager::getInstance()->createMoneyZone();
        $player->sendMessage(CustomTranslationFactory::arkania_moneyzone_created());
    }
}