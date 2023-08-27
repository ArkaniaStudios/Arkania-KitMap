<?php
declare(strict_types=1);

namespace arkania\factions\commands\subs;

use arkania\api\commands\BaseSubCommand;
use arkania\language\CustomTranslationFactory;
use arkania\player\CustomPlayer;
use arkania\utils\Utils;
use pocketmine\block\inventory\ChestInventory;
use pocketmine\block\inventory\EnderChestInventory;
use pocketmine\block\tile\Chest;
use pocketmine\block\VanillaBlocks;
use pocketmine\command\CommandSender;

class ChestCommand extends BaseSubCommand {

    public function __construct() {
        parent::__construct(
            'chest'
        );
    }

    protected function registerArguments(): array {
        return [];
    }

    public function onRun(CommandSender $player, array $args): void {
        if (!$player instanceof CustomPlayer) return;

        if (!$player->hasFaction()) {
            $player->sendMessage(CustomTranslationFactory::arkania_faction_no_have());
            return;
        }

        $faction = $player->getFaction();
        $content = $faction->getChestContent();
        $position = $player->getPosition();
        $position->y += 3;
        Utils::sendFakeBlock($player, VanillaBlocks::CHEST(), 0, 3, 0, 'Coffre - ' . $faction->getName(), Chest::class);
        $chestInv = new ChestInventory($position);
        var_dump($content);
        $chestInv->setContents($content);
        $player->setInventory('faction_chest');
        $player->setCurrentWindow($chestInv);
    }

}