<?php
declare(strict_types=1);

namespace arkania\commands\staff\subCommands;

use arkania\api\commands\BaseSubCommand;
use arkania\language\CustomTranslationFactory;
use arkania\player\CustomPlayer;
use pocketmine\command\CommandSender;

class Position2SubCommand extends BaseSubCommand {

    /** @var (string|mixed)[] */
    public static array $pos2 = [];

    public function __construct() {
        parent::__construct(
            'pos2'
        );
    }

    protected function registerArguments(): array {
        return [];
    }

    public function onRun(CommandSender $player, array $args): void {
        if (!$player instanceof CustomPlayer) return;

        $position = $player->getPosition();
        self::$pos2 = [
            'x' => $position->getX(),
            'z' => $position->getZ(),
        ];
        $player->sendMessage(CustomTranslationFactory::arkania_moneyzone_position_set('2', 'x ' . self::$pos2['x'] . ' | z ' . self::$pos2['z']));
    }
}