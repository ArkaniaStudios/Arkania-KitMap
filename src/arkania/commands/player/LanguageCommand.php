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
use arkania\utils\trait\ArgumentOrderException;
use pocketmine\command\CommandSender;

class LanguageCommand extends BaseCommand {
	public function __construct() {
		parent::__construct(
			'language',
			CustomTranslationFactory::arkania_language_description(),
			'/language <type>',
            [],
			['lang', 'langage']
		);
	}

    /**
     * @throws ArgumentOrderException
     */
    protected function registerArguments(): array {
        return [
            new StringArgument('type', false)
        ];
    }

    public function onRun(CommandSender $player, string $commandLabel, array $parameters): void {
        if (!$player instanceof CustomPlayer) {
            return;
        }

        if (\count($parameters) === 0) {
            $player->sendMessage(CustomTranslationFactory::arkania_language_usage());

            return;
        }

        $player->setLanguage($parameters['type']);
    }
}
