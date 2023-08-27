<?php
declare(strict_types=1);
/**
 *     _      ____    _  __     _      _   _   ___      _             __     __  ____
 *    / \    |  _ \  | |/ /    / \    | \ | | |_ _|    / \            \ \   / / |___ \
 *   / _ \   | |_) | | ' /    / _ \   |  \| |  | |    / _ \    _____   \ \ / /    __) |
 *  / ___ \  |  _ <  | . \   / ___ \  | |\  |  | |   / ___ \  |_____|   \ V /    / __/
 * /_/   \_\ |_| \_\ |_|\_\ /_/   \_\ |_| \_| |___| /_/   \_\            \_/    |_____|
 *
 * @author: Julien
 * @link: https://github.com/ArkaniaStudios
 */

namespace arkania\area\commands;

use arkania\api\commands\arguments\SubArgument;
use arkania\api\commands\BaseCommand;
use arkania\area\AreaManager;
use arkania\area\commands\subs\AdminCommand;
use arkania\area\commands\subs\CreateCommand;
use arkania\area\commands\subs\ListCommand;
use arkania\area\commands\subs\PosCommand;
use arkania\area\commands\subs\RemoveCommand;
use arkania\area\positions\CreateAreaPosition;
use arkania\form\FormManager;
use arkania\language\CustomTranslationFactory;
use arkania\permissions\Permissions;
use arkania\player\CustomPlayer;
use arkania\utils\Utils;
use pocketmine\command\CommandSender;
use pocketmine\command\utils\InvalidCommandSyntaxException;
use pocketmine\math\Vector3;

class AreaCommand extends BaseCommand {

    public function __construct() {
        parent::__construct(
            'area',
            'système d\'area',
            '/area',
            [
                new CreateCommand(),
                new PosCommand(),
                new RemoveCommand(),
                new ListCommand(),
                new AdminCommand(),
            ],
            ['area'],
            Permissions::ARKANIA_AREA
        );
    }

    protected function registerArguments(): array {
        return [];
    }

    public function onRun(CommandSender $player, array $parameters): void {
        if(!$player instanceof CustomPlayer) {
            return;
        }
        AreaManager::getInstance()->reload();
        $player->sendMessage(CustomTranslationFactory::arkania_area_reload());
    }
}