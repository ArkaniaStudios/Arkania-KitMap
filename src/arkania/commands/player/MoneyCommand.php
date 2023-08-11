<?php
declare(strict_types=1);

namespace arkania\commands\player;

use arkania\api\BaseCommand;
use arkania\economy\EconomyManager;
use arkania\language\CustomTranslationFactory;
use pocketmine\command\CommandSender;

class MoneyCommand extends BaseCommand {

    public function __construct() {
        parent::__construct(
            'money',
            CustomTranslationFactory::arkania_economy_money_description()
        );
    }

    public function execute(CommandSender $player, string $commandLabel, array $args): void {
        if (count($args) < 1) {
            $money = EconomyManager::getInstance()->getMoney($player->getName());
            $player->sendMessage(CustomTranslationFactory::arkania_economy_money_self((string)$money));
        }else{
            $target = $args[0];
            if (!EconomyManager::getInstance()->hasAccount($target)){
                $player->sendMessage(CustomTranslationFactory::arkania_economy_money_not_found($target));
                return;
            }
            $money = EconomyManager::getInstance()->getMoney($target);
            $player->sendMessage(CustomTranslationFactory::arkania_economy_money_target($target, (string)$money));
        }
    }
}