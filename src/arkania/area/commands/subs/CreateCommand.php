<?php
declare(strict_types=1);

namespace arkania\area\commands\subs;

use arkania\api\commands\arguments\StringArgument;
use arkania\api\commands\BaseSubCommand;
use arkania\area\AreaManager;
use arkania\area\positions\CreateAreaPosition;
use arkania\language\CustomTranslationFactory;
use arkania\player\CustomPlayer;
use pocketmine\command\CommandSender;
use pocketmine\math\Vector3;

class CreateCommand extends BaseSubCommand {

    public function __construct() {
        parent::__construct(
            'create'
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
        if(!isset(AreaManager::$pos2[$player->getName()]) or !isset(AreaManager::$pos1[$player->getName()])) {
            $player->sendMessage('area.position.missing');
            return;
        }

        $name = $args['name'];
        if(preg_match('/[^a-zA-Z0-9_]/', $name)) {
            $player->sendMessage(CustomTranslationFactory::arkania_faction_name_invalid());
            return;
        }
        if(AreaManager::getInstance()->existArea($name)) {
            $player->sendMessage(CustomTranslationFactory::arkania_area_already_exist($name));
            return;
        }
        $pos1X = AreaManager::$pos1[$player->getName()]['x'];
        $pos1Z = AreaManager::$pos1[$player->getName()]['z'];
        $pos2X = AreaManager::$pos2[$player->getName()]['x'];
        $pos2Z = AreaManager::$pos2[$player->getName()]['z'];
        AreaManager::getInstance()->createArea(
            new CreateAreaPosition(
                $args['name'],
                new Vector3(
                    (int)min($pos1X, $pos2X),
                    0,
                    (int)min($pos1Z, $pos2Z)
                ),
                new Vector3(
                    (int)max($pos1X, $pos2X),
                    0,
                    (int)max($pos1Z, $pos2Z)
                ),
                [
                    'canPlaceBlock' => false,
                    'canBreakBlock' => false,
                    'pvp' => false,
                    'canClaim' => false,
                    'canUseCommand' => false,
                    'canDropItem' => false,
                    'canPickUpItem' => false,
                    'canApplyDamage' => false
                ]
            )
        );
        $player->sendMessage(CustomTranslationFactory::arkania_area_created($name));
    }

}