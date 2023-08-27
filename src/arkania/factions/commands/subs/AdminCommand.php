<?php
declare(strict_types=1);

namespace arkania\factions\commands\subs;

use arkania\api\commands\BaseSubCommand;
use arkania\language\CustomTranslationFactory;
use arkania\permissions\Permissions;
use arkania\player\CustomPlayer;
use pocketmine\command\CommandSender;

class AdminCommand extends BaseSubCommand {

    public function __construct() {
        parent::__construct(
            'admin'
        );
        $this->setPermission(Permissions::ARKANIA_FACTION_ADMIN);
    }

    protected function registerArguments(): array {
        return [];
    }

    public function onRun(CommandSender $player, array $args): void {
        if (!$player instanceof CustomPlayer) return;

        if ($player->isFactionAdmin()){
            $player->setFactionAdmin(false);
            $player->sendMessage(CustomTranslationFactory::arkania_faction_admin_disable());
        }else{
            $player->setFactionAdmin(true);
            $player->sendMessage(CustomTranslationFactory::arkania_faction_admin_enable());
        }
    }
}