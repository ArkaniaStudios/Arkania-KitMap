<?php
declare(strict_types=1);

namespace arkania\area\commands\subs;

use arkania\api\commands\arguments\IntArgument;
use arkania\api\commands\BaseSubCommand;
use arkania\area\AreaManager;
use arkania\language\CustomTranslationFactory;
use arkania\player\CustomPlayer;
use pocketmine\command\CommandSender;

class PosCommand extends BaseSubCommand {

    public function __construct() {
        parent::__construct(
            'pos'
        );
    }

    protected function registerArguments(): array {
        return [
            new IntArgument('value')
        ];
    }

    public function onRun(CommandSender $player, array $args): void {
        if (!$player instanceof CustomPlayer) {
            return;
        }
        $position = $player->getPosition();
        if($args['value'] === 1) {
            AreaManager::$pos1[$player->getName()] = ['x' => $position->getX(), 'z' => $position->getZ()];
            $player->sendMessage(CustomTranslationFactory::arkania_area_select_pos1());
        } else if($args['value'] === 2) {
            AreaManager::$pos2[$player->getName()] = ['x' => $position->getX(), 'z' => $position->getZ()];
            $player->sendMessage(CustomTranslationFactory::arkania_area_select_pos2());
        }
    }

}