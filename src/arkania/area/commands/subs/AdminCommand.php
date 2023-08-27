<?php
declare(strict_types=1);

namespace arkania\area\commands\subs;

use arkania\api\commands\BaseSubCommand;
use arkania\area\AreaManager;
use arkania\language\CustomTranslationFactory;
use arkania\player\CustomPlayer;
use pocketmine\command\CommandSender;

class AdminCommand extends BaseSubCommand {

    public function __construct() {
        parent::__construct(
            'admin'
        );
    }

    protected function registerArguments(): array {
        return [];
    }

    public function onRun(CommandSender $player, array $args): void {
        if (!$player instanceof CustomPlayer) return;

        if(AreaManager::getInstance()->isAdminMode($player)) {
            AreaManager::getInstance()->unsetAdminMode($player);
            $player->sendMessage(CustomTranslationFactory::arkania_area_admin_disable());
        } else {
            AreaManager::getInstance()->setAdminMode($player);
            $player->sendMessage(CustomTranslationFactory::arkania_area_admin_enable());
        }

    }

}