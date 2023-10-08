<?php
declare(strict_types=1);


namespace arkania\commands\player;

use arkania\api\commands\BaseCommand;
use arkania\game\KothManager;
use arkania\game\PiniataManager;
use arkania\language\CustomTranslationFactory;
use arkania\player\CustomPlayer;
use pocketmine\command\CommandSender;

class EventsCommand extends BaseCommand {

    public function __construct() {
        parent::__construct(
            'events',
            CustomTranslationFactory::arkania_events_description()
        );
    }

    protected function registerArguments(): array {
        return [];
    }

    public function onRun(CommandSender $player, array $parameters): void {
        if (!$player instanceof CustomPlayer) return;

        $piniata = PiniataManager::getInstance()->getEventStatus();
        if($piniata){
            $piniataStatus = CustomTranslationFactory::arkania_events_piniata_started();
        }else{
            $piniataStatus = CustomTranslationFactory::arkania_events_piniata_ended();
        }
        $koth = KothManager::getInstance()->getEventStatus();
        if($koth){
            $kothStatus = CustomTranslationFactory::arkania_events_piniata_started();
        }else {
            $kothStatus = CustomTranslationFactory::arkania_events_koth_ended();
        }
        $player->sendMessage(CustomTranslationFactory::arkania_events_message($kothStatus, $piniataStatus));
    }

}