<?php
declare(strict_types=1);

namespace arkania\commands\staff\subCommands;

use arkania\api\commands\BaseSubCommand;
use arkania\language\CustomTranslationFactory;
use arkania\player\CustomPlayer;
use pocketmine\command\CommandSender;

class Position1SubCommand extends BaseSubCommand {

    /** @var (string|mixed)[] */
    public static array $pos1 = [];

    public function __construct() {
        parent::__construct(
            'pos1',
            'test'
        );
    }

    protected function registerArguments(): array {
        return [];
    }

    public function onRun(CommandSender $player, array $args): void {
        if (!$player instanceof CustomPlayer) return;

        $position = $player->getPosition();
        self::$pos1 = [
            'x' => $position->getX(),
            'z' => $position->getZ(),
        ];
        $player->sendMessage(CustomTranslationFactory::arkania_moneyzone_position_set('1', 'x ' . self::$pos1['x'] . ' | z ' . self::$pos1['z']));

    }

}