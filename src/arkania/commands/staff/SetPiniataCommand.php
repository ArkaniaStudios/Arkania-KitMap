<?php
declare(strict_types=1);

namespace arkania\commands\staff;

use arkania\api\commands\arguments\SubArgument;
use arkania\api\commands\BaseCommand;
use arkania\game\PiniataManager;
use arkania\language\CustomTranslationFactory;
use arkania\Main;
use arkania\npc\type\customs\Piniata;
use arkania\permissions\Permissions;
use arkania\player\CustomPlayer;
use pocketmine\command\CommandSender;
use pocketmine\entity\Location;
use pocketmine\Server;

class SetPiniataCommand extends BaseCommand {

    public function __construct() {
        parent::__construct(
            'piniata',
            CustomTranslationFactory::arkania_piniata_description(),
            '/piniata',
            [],
            [],
            Permissions::ARKANIA_PINIATA
        );
    }

    protected function registerArguments(): array {
        return [
            new SubArgument('create', true),
            new SubArgument('spawn', true)
        ];
    }

    public function onRun(CommandSender $player, array $parameters): void {
        if (!$player instanceof CustomPlayer) return;

        if (isset($parameters['create']) && $parameters['create'] === 'spawn'){
            Server::getInstance()->broadcastMessage(CustomTranslationFactory::arkania_piniata_start());
            $position = PiniataManager::getInstance()->getPositions();
            $entity = new Piniata(new Location($position['x'], $position['y'], $position['z'], Main::getInstance()->getServer()->getWorldManager()->getDefaultWorld(), 0, 0));
            $entity->setNameTag('§l§cPiniata' . "\n\n" . str_repeat('§a|', 10));
            $entity->setHealth(100);
            $entity->setNameTagAlwaysVisible();
            $entity->spawnToAll();
            return;
        }

        if (isset($parameters['create']) && $parameters['create'] === 'create') {
            PiniataManager::getInstance()->createSpawnLama();
            $player->sendMessage(CustomTranslationFactory::arkania_piniata_create());
            return;
        }

        $position = $player->getPosition();
        PiniataManager::getInstance()->setPositions([
            'x' => $position->getX(),
            'y' => $position->getY(),
            'z' => $position->getZ()
        ]);
        $player->sendMessage(CustomTranslationFactory::arkania_piniata_set_position());
    }
}