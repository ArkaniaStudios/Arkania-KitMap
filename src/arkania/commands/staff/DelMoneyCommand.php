<?php
declare(strict_types=1);

namespace arkania\commands\staff;

use arkania\api\BaseCommand;
use arkania\economy\EconomyManager;
use arkania\economy\events\PlayerAddMoneyEvent;
use arkania\economy\events\PlayerDelMoneyEvent;
use arkania\language\CustomTranslationFactory;
use arkania\permissions\Permissions;
use arkania\utils\Utils;
use pocketmine\command\CommandSender;
use pocketmine\command\utils\InvalidCommandSyntaxException;

class DelMoneyCommand extends BaseCommand {

    public function __construct() {
        parent::__construct(
            'delmoney',
            CustomTranslationFactory::arkania_economy_delmoney_description(),
            '/delmoney <player> <amount>',
            permission: Permissions::ARKANIA_DELMONEY
        );
    }

    /**
     * @param CommandSender $player
     * @param string $commandLabel
     * @param array $args
     * @return void
     * @throws \JsonException
     */
    public function execute(CommandSender $player, string $commandLabel, array $args): void {
        if (count($args) !== 2) {
            throw new InvalidCommandSyntaxException();
        }
        $target = $args[0];
        $amount = $args[1];
        if (Utils::isValidNumber($amount)){
            if (EconomyManager::getInstance()->getMoney($target) - $amount < 0) {
                $player->sendMessage(CustomTranslationFactory::arkania_economy_invalid_amount($amount));
                return;
            }
            EconomyManager::getInstance()->delMoney($target, (int)$amount);
            (new PlayerDelMoneyEvent($player, $target, $amount))->call();
            $player->sendMessage(CustomTranslationFactory::arkania_economy_delmoney_success($amount, $target));
        }else{
            $player->sendMessage(CustomTranslationFactory::arkania_economy_invalid_amount($amount));
        }
    }
}