<?php
declare(strict_types=1);

namespace arkania\area\commands\subs;

use arkania\api\commands\arguments\StringArgument;
use arkania\api\commands\BaseSubCommand;
use arkania\area\AreaManager;
use arkania\language\CustomTranslationFactory;
use arkania\player\CustomPlayer;
use pocketmine\command\CommandSender;

class RemoveCommand extends BaseSubCommand {

    public function __construct() {
        parent::__construct(
            'remove'
        );
    }

    protected function registerArguments(): array {
        return [
            new StringArgument('name')
        ];
    }

    /**
     * @throws \JsonException
     */
    public function onRun(CommandSender $player, array $args): void {
        if (!$player instanceof CustomPlayer) return;

        $name = $args['name'];
        if(!AreaManager::getInstance()->existArea($name)) {
            $player->sendMessage(CustomTranslationFactory::arkania_area_not_exist($name));
            return;
        }
        AreaManager::getInstance()->delArea($name);
        $player->sendMessage(CustomTranslationFactory::arkania_area_delete_success($name));

    }

}