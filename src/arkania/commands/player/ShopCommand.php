<?php
declare(strict_types=1);

namespace arkania\commands\player;

use arkania\api\commands\BaseCommand;
use arkania\player\CustomPlayer;
use arkania\shop\ShopForm;
use pocketmine\command\CommandSender;

class ShopCommand extends BaseCommand {

    public function __construct() {
        parent::__construct(
            'shop',
            'Open the shop'
        );
    }

    protected function registerArguments(): array {
        return [];
    }

    public function onRun(CommandSender $player, array $parameters): void {
        if (!$player instanceof CustomPlayer) return;

        ShopForm::getInstance()->sendShopForm($player);
    }
}