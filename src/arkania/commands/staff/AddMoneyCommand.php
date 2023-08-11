<?php
declare(strict_types=1);

namespace arkania\commands\staff;

use arkania\api\BaseCommand;
use arkania\economy\EconomyManager;
use arkania\economy\events\PlayerAddMoneyEvent;
use arkania\language\CustomTranslationFactory;
use arkania\permissions\Permissions;
use arkania\utils\Utils;
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

    /**
     * @throws \JsonException
     */
    public function execute(CommandSender $player, string $commandLabel, array $args): void {
        if (count($args) !== 2) {
            throw new InvalidCommandSyntaxException();
        }
        $target = $args[0];
        $amount = $args[1];
        if (Utils::isValidNumber($amount)){
            EconomyManager::getInstance()->addMoney($target, (int)$amount);
            (new PlayerAddMoneyEvent($player, $target, $amount))->call();
            $player->sendMessage(CustomTranslationFactory::arkania_economy_addmoney_success($amount, $target));
        }else{
            $player->sendMessage(CustomTranslationFactory::arkania_economy_invalid_amount($amount));
        }
    }
}