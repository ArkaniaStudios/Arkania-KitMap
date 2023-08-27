<?php
declare(strict_types=1);

namespace arkania\factions\commands\subs;

use arkania\api\commands\BaseSubCommand;
use arkania\factions\task\SeeChunkTask;
use arkania\language\CustomTranslationFactory;
use arkania\Main;
use arkania\player\CustomPlayer;
use pocketmine\command\CommandSender;

class ChunkCommand extends BaseSubCommand {

    public function __construct() {
        parent::__construct(
            'chunk'
        );
    }

    protected function registerArguments(): array {
        return [];
    }

    public function onRun(CommandSender $player, array $args): void {
        if (!$player instanceof CustomPlayer) return;

        if ($player->isChunkView()) {
            $player->setChunkView(false);
            $player->sendMessage(CustomTranslationFactory::arkania_faction_view_chunk_disable());
        } else {
            $player->setChunkView();
            $player->sendMessage(CustomTranslationFactory::arkania_faction_view_chunk_enable());
            Main::getInstance()->getScheduler()->scheduleRepeatingTask(new SeeChunkTask(), 20);
        }
    }
}