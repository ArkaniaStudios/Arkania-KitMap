<?php

/*
 *
 *     _      ____    _  __     _      _   _   ___      _                 _   _   _____   _____  __        __   ___    ____    _  __
 *    / \    |  _ \  | |/ /    / \    | \ | | |_ _|    / \               | \ | | | ____| |_   _| \ \      / /  / _ \  |  _ \  | |/ /
 *   / _ \   | |_) | | ' /    / _ \   |  \| |  | |    / _ \     _____    |  \| | |  _|     | |    \ \ /\ / /  | | | | | |_) | | ' /
 *  / ___ \  |  _ <  | . \   / ___ \  | |\  |  | |   / ___ \   |_____|   | |\  | | |___    | |     \ V  V /   | |_| | |  _ <  | . \
 * /_/   \_\ |_| \_\ |_|\_\ /_/   \_\ |_| \_| |___| /_/   \_\            |_| \_| |_____|   |_|      \_/\_/     \___/  |_| \_\ |_|\_\
 *
 * Arkania is a Minecraft Bedrock server created in 2019,
 * we mainly use PocketMine-MP to create content for our server
 * but we use something else like WaterDog PE
 *
 * @author Arkania-Team
 * @link https://arkaniastudios.com
 *
 */

declare(strict_types=1);

namespace arkania\commands\player;

use arkania\api\commands\arguments\StringArgument;
use arkania\api\commands\BaseCommand;
use arkania\language\CustomTranslationFactory;
use arkania\player\CustomPlayer;
use pocketmine\command\CommandSender;
use pocketmine\command\utils\InvalidCommandSyntaxException;

class ScoreBoardCommand extends BaseCommand {

	/** @var (string|CustomPlayer)[]  */
	public static array $scoreboard = [];

	public function __construct() {
		parent::__construct(
			'scoreboard',
			CustomTranslationFactory::arkania_scoreboard_description(),
			'/scoreboard <on|off>',
            [],
			['sb']
		);
	}

	public function onRun(CommandSender $player, array $parameters): void {
        if (!$player instanceof CustomPlayer) {
            return;
        }

        if (count($parameters) === 0) {
            throw new InvalidCommandSyntaxException();
        }

        switch ($parameters['status']) {
            case 'on':
                if (isset(self::$scoreboard[$player->getName()])) {
                    $player->sendMessage(CustomTranslationFactory::arkania_scoreboard_already('activé'));

                    return;
                }
                self::$scoreboard[$player->getName()] = $player;
                $player->sendMessage(CustomTranslationFactory::arkania_scoreboard_on());
                break;

            case 'off':
                if (!isset(self::$scoreboard[$player->getName()])) {
                    $player->sendMessage(CustomTranslationFactory::arkania_scoreboard_already('désactivé'));

                    return;
                }
                unset(self::$scoreboard[$player->getName()]);
                $player->sendMessage(CustomTranslationFactory::arkania_scoreboard_off());
                break;
            default:
                throw new InvalidCommandSyntaxException();
        }
    }

    protected function registerArguments(): array {
        return [
            new StringArgument('status', false)
        ];
    }
}
