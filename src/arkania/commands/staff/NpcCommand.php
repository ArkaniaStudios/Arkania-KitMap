<?php
declare(strict_types=1);

namespace arkania\commands\staff;

use arkania\api\commands\arguments\SubArgument;
use arkania\api\commands\BaseCommand;
use arkania\form\FormManager;
use arkania\game\PiniataManager;
use arkania\language\CustomTranslationFactory;
use arkania\Main;
use arkania\npc\type\customs\Piniata;
use arkania\permissions\Permissions;
use arkania\player\CustomPlayer;
use pocketmine\command\CommandSender;
use pocketmine\entity\Location;
use pocketmine\Server;

class NpcCommand extends BaseCommand {
    public static array $npc = [];


    public function __construct() {
        parent::__construct(
            'npc',
            CustomTranslationFactory::arkania_npc_description(),
            '/npc',
            [],
            [],
            Permissions::COMMAND_NPC
        );
    }

    protected function registerArguments(): array {
        return [
            new SubArgument('create', true),
            new SubArgument('disband', true),
            new SubArgument('rotate', true),
            new SubArgument('edit', true)
        ];
    }

    public function onRun(CommandSender $player, array $parameters): void {
        if(!$player instanceof CustomPlayer) return;

        if($parameters === []) {
            FormManager::getInstance()->sendNpcCreationForm($player);
            return;
        }
        $argument = strtolower($parameters['create']);
        if($argument === 'create') {
            FormManager::getInstance()->sendNpcCreationForm($player);
        } else if($argument === 'disband') {
            self::$npc[$player->getName()] = 'disband';
            $player->sendMessage(CustomTranslationFactory::npc_tap_for_disband());
        } else if($argument === 'rotate') {
            self::$npc[$player->getName()] = 'rotate';
            $player->sendMessage(CustomTranslationFactory::npc_tap_for_rotate());
        } else if($argument === 'edit') {
            self::$npc[$player->getName()] = 'edit';
            $player->sendMessage(CustomTranslationFactory::npc_tap_for_edit());
        }
    }

}