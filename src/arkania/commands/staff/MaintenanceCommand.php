<?php
declare(strict_types=1);

namespace arkania\commands\staff;

use arkania\api\BaseCommand;
use arkania\language\CustomTranslationFactory;
use arkania\Main;
use arkania\permissions\Permissions;
use arkania\server\MaintenanceManager;
use arkania\utils\trait\Date;
use arkania\webhook\Embed;
use arkania\webhook\Message;
use arkania\webhook\Webhook;
use JsonException;
use pocketmine\command\CommandSender;
use pocketmine\command\utils\InvalidCommandSyntaxException;
use pocketmine\scheduler\ClosureTask;

class MaintenanceCommand extends BaseCommand {

    public function __construct() {
        parent::__construct(
            'maintenance',
            CustomTranslationFactory::arkania_maintenance_description(),
            '/maintenance <on/off>',
            ['whitelist'],
            Permissions::ARKANIA_MAINTENANCE
        );
    }

    /**
     * @throws JsonException
     */
    public function execute(CommandSender $player, string $commandLabel, array $args): void {

        if (count($args) !== 1) {
            throw new InvalidCommandSyntaxException();
        }

        if ($args[0] === 'on') {
            if (MaintenanceManager::getInstance()->isInMaintenance()) {
                $player->sendMessage(CustomTranslationFactory::arkania_maintenance_already('activé'));
            } else {
                $time = 30;
                MaintenanceManager::getInstance()->setMaintenance(true);
                $handler = $this->getMain()->getScheduler()->scheduleRepeatingTask(
                    new ClosureTask(
                        function () use ($player, &$time, &$handler) : void {
                            if ($time === 0) {
                                foreach (Main::getInstance()->getServer()->getOnlinePlayers() as $onlinePlayer) {
                                    if (!$onlinePlayer->hasPermission(Permissions::ARKANIA_MAINTENANCE_BYPASS)) {
                                        $onlinePlayer->disconnect(CustomTranslationFactory::arkania_maintenance_kick(Main::DISCORD));
                                    }
                                }
                                $player->getServer()->broadcastMessage(CustomTranslationFactory::arkania_maintenance_enabled($player->getName()));
                                $handler->cancel();
                            } else if($time % 10 === 0 || $time === 5 || $time <= 3) {
                                $player->sendMessage(CustomTranslationFactory::arkania_maintenance_time("$time"));
                            }
                            $time--;
                        }
                    ),
                    20
                );
                $webhook = new Webhook(Main::ADMIN_URL);
                $message = new Message();
                $embed = new Embed();
                $embed->setTitle('**MAINTENANCE - ENABLED**')
                    ->setContent('- Le serveur vient de passer en maintenance.' . PHP_EOL . PHP_EOL . '*Informations :*' . PHP_EOL . '- Staff: **' . $player->getName() . '**' . PHP_EOL . '- Date: **' . Date::create()->toString() . '**')
                    ->setFooter('Arkania - KitMap')
                    ->setColor(0xEF05BA)
                    ->setImage();
                $message->addEmbed($embed);
                $webhook->send($message);
                self::sendLogs($player, 'vient d\'activer la maintenance');
            }
        }elseif($args[0] === 'off'){
            if (!MaintenanceManager::getInstance()->isInMaintenance()) {
                $player->sendMessage(CustomTranslationFactory::arkania_maintenance_already('désactivé'));
            }else{
                MaintenanceManager::getInstance()->setMaintenance(false);
                $player->getServer()->broadcastMessage(CustomTranslationFactory::arkania_maintenance_disabled($player->getName()));
                $webhook = new Webhook(Main::ADMIN_URL);
                $message = new Message();
                $embed = new Embed();
                $embed->setTitle('**MAINTENANCE - DISABLED**')
                    ->setContent('- La maintenance vient d\'être désactivé.' . PHP_EOL . PHP_EOL . '*Informations :*' . PHP_EOL . '- Staff: **' . $player->getName() . '**' . PHP_EOL . '- Date: **' . Date::create()->toString() . '**')
                    ->setFooter('Arkania - KitMap')
                    ->setColor(0xEF05BA)
                    ->setImage();
                $message->addEmbed($embed);
                $webhook->send($message);
                self::sendLogs($player, 'vient de désactiver la maintenance');
            }
        }
    }
}