<?php
declare(strict_types=1);

namespace arkania\commands\player;

use arkania\api\commands\arguments\TextArgument;
use arkania\api\commands\BaseCommand;
use arkania\language\CustomTranslationFactory;
use arkania\permissions\Permissions;
use arkania\player\CustomPlayer;
use arkania\utils\Utils;
use pocketmine\command\CommandSender;

class RenameCommand extends BaseCommand {

    public function __construct() {
        parent::__construct(
            'rename',
            CustomTranslationFactory::arkania_rename_description(),
            '/rename <name>',
            permission: Permissions::ARKANIA_RENAME
        );
    }

    protected function registerArguments(): array {
        return [
            new TextArgument('name', false)
        ];
    }

    public function onRun(CommandSender $player, array $parameters): void {
        if (!$player instanceof CustomPlayer) return;

        if (strlen($parameters['name']) < 3 || strlen($parameters['name']) > 32) {
            $player->sendMessage(CustomTranslationFactory::arkania_rename_error_length());
            return;
        }

        if (!preg_match('/^[a-zA-Z0-9_]+$/', $parameters['name'])) {
            $player->sendMessage(CustomTranslationFactory::arkania_rename_error_format());
            return;
        }
        $item = $player->getInventory()->getItemInHand()->setCustomName(Utils::removeColor($parameters['name']));
        $player->getInventory()->setItemInHand($item);
        $player->sendMessage(CustomTranslationFactory::arkania_rename_success($parameters['name']));
    }
}