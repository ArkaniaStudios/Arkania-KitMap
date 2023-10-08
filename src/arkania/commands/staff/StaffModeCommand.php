<?php
declare(strict_types=1);

namespace arkania\commands\staff;

use arkania\api\commands\BaseCommand;
use arkania\permissions\Permissions;
use arkania\player\CustomPlayer;
use arkania\staffmode\StaffMode;
use pocketmine\command\CommandSender;

class StaffModeCommand extends BaseCommand {

    public function __construct() {
        parent::__construct(
            'staffmode',
            'Staffmode',
            permission: Permissions::ARKANIA_STAFFMODE
        );
    }


    protected function registerArguments(): array {
        return [];
    }

    public function onRun(CommandSender $player, array $parameters): void {
        if (!$player instanceof CustomPlayer)
            return;

        if (StaffMode::getInstance()->isInStaffMode($player))
            StaffMode::getInstance()->removeStaffMode($player);
        else
            StaffMode::getInstance()->addStaffMode($player);
    }

}