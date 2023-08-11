<?php
declare(strict_types=1);

namespace arkania\commands\staff;

use arkania\api\BaseCommand;
use arkania\form\FormManager;
use arkania\language\CustomTranslationFactory;
use arkania\permissions\Permissions;
use arkania\player\CustomPlayer;
use pocketmine\command\CommandSender;
use pocketmine\command\utils\InvalidCommandSyntaxException;
use pocketmine\console\ConsoleCommandSender;

class BroadCastCommand extends BaseCommand {

    public function __construct() {
        parent::__construct(
            'broadcast',
            CustomTranslationFactory::arkania_broadcast_description(),
            '/bc',
            ['bc'],
            Permissions::ARKANIA_BROADCAST
        );
    }

    public function execute(CommandSender $player, string $commandLabel, array $args): void {

        if ($player instanceof ConsoleCommandSender) {
            if (count($args) === 0) {
                throw new InvalidCommandSyntaxException();
            }
            if ($args[0] === 'important'){
                $message = array_slice($args, 1);
                foreach ($player->getServer()->getOnlinePlayers() as $players) {
                    $players->sendMessage('§e----------------------- (§cANNONCE§e) -----------------------');
                    $players->sendMessage(' ');
                    $players->sendMessage('§c' . implode(' ', $message));
                    $players->sendMessage(' ');
                    $players->sendMessage('§e---------------------------------------------------------');
                }
            }else{
                $message = $args;
                foreach ($player->getServer()->getOnlinePlayers() as $players) {
                    $players->sendMessage('§c' . implode(' ', $message));
                }
            }
        }elseif($player instanceof CustomPlayer){
            if (count($args) !== 0) {
                throw new InvalidCommandSyntaxException();
            }
            FormManager::getInstance()->sendBroadCastForm($player);
        }
    }
}