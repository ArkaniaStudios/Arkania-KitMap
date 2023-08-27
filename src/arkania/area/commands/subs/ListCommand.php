<?php
declare(strict_types=1);

namespace arkania\area\commands\subs;

use arkania\api\commands\BaseSubCommand;
use arkania\area\AreaManager;
use arkania\language\CustomTranslationFactory;
use arkania\player\CustomPlayer;
use pocketmine\command\CommandSender;

class ListCommand extends BaseSubCommand {

    public function __construct() {
        parent::__construct(
            'list'
        );
    }

    protected function registerArguments(): array {
        return [];
    }

    public function onRun(CommandSender $player, array $args): void {
        if (!$player instanceof CustomPlayer) return;

        $areaList = '';
        foreach (AreaManager::getInstance()->getAllArea() as $area => $data) {
            $areaList .= $area . "\n";
        }
        $player->sendMessage(CustomTranslationFactory::arkania_area_list($areaList));

    }

}