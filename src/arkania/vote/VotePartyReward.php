<?php
declare(strict_types=1);

namespace arkania\vote;

use arkania\Main;
use pocketmine\console\ConsoleCommandSender;

class VotePartyReward extends BaseVoteReward {


    public static function create() : self {
        return new self();
    }

    public function giveReward(): void {
        foreach (Main::getInstance()->getServer()->getOnlinePlayers() as $player){
            $inventory = $player->getInventory();
            $server = $player->getServer();
            foreach ($this->items as $rewardItem) {
                $result = $inventory->addItem($rewardItem);
                if(count($result) !== 0){
                    foreach ($result as $item) {
                        $player->dropItem($item);
                    }
                }
            }
            foreach ($this->commands as $type => $command) {
                switch ($type) {
                    case 0:
                        $server->dispatchCommand($player, $command);
                        break;
                    case 1:
                        $server->dispatchCommand(new ConsoleCommandSender($server, $server->getLanguage()), str_replace('{PLAYER}', $player->getName(), $command));
                        break;
                }
            }

            foreach ($this->xp as $xp => $level) {
                if ($level) {
                    $player->getXpManager()->addXpLevels($xp);
                } else {
                    $player->getXpManager()->addXp($xp);
                }
            }

            //TODO: Ajouter de l'argent au joueur
        }
    }
}