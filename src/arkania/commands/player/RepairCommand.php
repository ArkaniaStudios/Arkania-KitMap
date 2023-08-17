<?php
declare(strict_types=1);

namespace arkania\commands\player;

use arkania\api\commands\arguments\SubArgument;
use arkania\api\commands\BaseCommand;
use arkania\language\CustomTranslationFactory;
use arkania\permissions\Permissions;
use arkania\player\CustomPlayer;
use InvalidArgumentException;
use pocketmine\command\CommandSender;
use pocketmine\item\Durable;

class RepairCommand extends BaseCommand {

    public function __construct() {
        parent::__construct(
            'repair',
            CustomTranslationFactory::arkania_repair_description(),
            '/repair [hand|all]',
            [],
            ['fix'],
            Permissions::ARKANIA_REPAIR
        );
    }

    protected function registerArguments(): array {
        return [
            new SubArgument('all', true)
        ];
    }

    public function onRun(CommandSender $player, array $parameters): void {
        if (!$player instanceof CustomPlayer) return;

        if (!isset($parameters['all'])) {
            $item = $player->getInventory()->getItemInHand();
            if ($item->getTypeId() === 0) {
                $player->sendMessage(CustomTranslationFactory::arkania_repair_no_item());
                return;
            }
            if (!$item instanceof Durable) {
                $player->sendMessage(CustomTranslationFactory::arkania_repair_cant_repair());
                return;
            }
            try {
                $item->setDamage(0);
            }catch (InvalidArgumentException $e) {
                $player->sendMessage(CustomTranslationFactory::arkania_repair_no_need());
                return;
            }
            $player->getInventory()->setItemInHand($item);
            $player->sendMessage(CustomTranslationFactory::arkania_repair_success());
        }else{
            $items = $player->getInventory()->getContents();
            foreach ($items as $item) {
                if ($item->getTypeId() === 0) continue;
                if (!$item instanceof Durable) continue;
                try {
                    $item->setDamage(0);
                }catch (InvalidArgumentException $e) {
                    continue;
                }
                $player->getInventory()->setItemInHand($item);
            }
            $armorInv = $player->getArmorInventory();
            $armor = $armorInv->getContents();
            foreach ($armor as $slot => $item) {
                if ($item->getTypeId() === 0) continue;
                if (!$item instanceof Durable) continue;
                try {
                    $item->setDamage(0);
                }catch (InvalidArgumentException $e) {
                    continue;
                }
                $armorInv->setItem($slot, $item);
            }
            $player->sendMessage(CustomTranslationFactory::arkania_repair_success());
        }
    }
}