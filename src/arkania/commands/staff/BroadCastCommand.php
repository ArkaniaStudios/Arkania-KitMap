<?php
declare(strict_types=1);

namespace arkania\commands\staff;

use arkania\api\commands\arguments\StringArgument;
use arkania\api\commands\arguments\TextArgument;
use arkania\api\commands\BaseCommand;
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
            [],
            ['bc'],
            Permissions::ARKANIA_BROADCAST
        );
    }

    protected function registerArguments(): array {
        return [
            new StringArgument('type', true),
            new TextArgument('message', true)
        ];
    }

    public function onRun(CommandSender $player, array $parameters): void {
        if ($player instanceof ConsoleCommandSender) {
            if (count($parameters) === 0) {
                throw new InvalidCommandSyntaxException();
            }
            if ($parameters['type'] === 'important'){
                $message = $parameters['message'];
                foreach ($player->getServer()->getOnlinePlayers() as $players) {
                    $players->sendMessage('§e----------------------- (§cANNONCE§e) -----------------------');
                    $players->sendMessage(' ');
                    $players->sendMessage('§c' . $message);
                    $players->sendMessage(' ');
                    $players->sendMessage('§e---------------------------------------------------------');
                }
            }else{
                $message = $parameters;
                foreach ($player->getServer()->getOnlinePlayers() as $players) {
                    $players->sendMessage('§c' . implode(' ', $message));
                }
            }
        }elseif($player instanceof CustomPlayer){
            if (count($parameters) !== 0) {
                throw new InvalidCommandSyntaxException();
            }
            FormManager::getInstance()->sendBroadCastForm($player);
        }
    }
}