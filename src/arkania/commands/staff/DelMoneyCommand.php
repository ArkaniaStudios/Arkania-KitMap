<?php
declare(strict_types=1);

namespace arkania\commands\staff;

use arkania\api\commands\arguments\IntArgument;
use arkania\api\commands\arguments\StringArgument;
use arkania\api\commands\BaseCommand;
use arkania\economy\EconomyManager;
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

    protected function registerArguments(): array {
        return [
            new StringArgument('target', false),
            new IntArgument('amount', false)
        ];
    }

    public function onRun(CommandSender $player, array $parameters): void {
        if (count($parameters) !== 2) {
            throw new InvalidCommandSyntaxException();
        }
        $target = $parameters['target'];
        $amount = $parameters['amount'];
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