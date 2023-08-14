<?php
declare(strict_types=1);

namespace arkania\commands\staff;

use arkania\api\commands\arguments\IntArgument;
use arkania\api\commands\arguments\StringArgument;
use arkania\api\commands\BaseCommand;
use arkania\economy\EconomyManager;
use arkania\economy\events\PlayerAddMoneyEvent;
use arkania\language\CustomTranslationFactory;
use arkania\permissions\Permissions;
use arkania\utils\Utils;
use JsonException;
use pocketmine\command\CommandSender;
use pocketmine\command\utils\InvalidCommandSyntaxException;

class AddMoneyCommand extends BaseCommand {

    public function __construct() {
        parent::__construct(
            'addmoney',
            CustomTranslationFactory::arkania_economy_addmoney_description(),
            '/addmoney <player> <amount>',
            permission: Permissions::ARKANIA_ADDMONEY
        );
    }

    protected function registerArguments(): array {
        return [
            new StringArgument('target', false),
            new IntArgument('amount', false)
        ];
    }

    /**
     * @throws JsonException
     */
    public function onRun(CommandSender $player, string $commandLabel, array $parameters): void {
        if (count($parameters) !== 2) {
            throw new InvalidCommandSyntaxException();
        }
        $target = $parameters['target'];
        $amount = $parameters['amount'];
        if (Utils::isValidNumber($amount)){
            EconomyManager::getInstance()->addMoney($target, (int)$amount);
            (new PlayerAddMoneyEvent($player, $target, $amount))->call();
            $player->sendMessage(CustomTranslationFactory::arkania_economy_addmoney_success($amount, $target));
        }else{
            $player->sendMessage(CustomTranslationFactory::arkania_economy_invalid_amount($amount));
        }
    }
}