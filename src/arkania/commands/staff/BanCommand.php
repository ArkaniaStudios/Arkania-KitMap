<?php
declare(strict_types=1);

namespace arkania\commands\staff;

use arkania\api\commands\arguments\StringArgument;
use arkania\api\commands\arguments\TargetArgument;
use arkania\api\commands\arguments\TextArgument;
use arkania\api\commands\BaseCommand;
use arkania\language\CustomTranslationFactory;
use arkania\Main;
use arkania\permissions\Permissions;
use arkania\player\CustomPlayer;
use arkania\sanctions\ban\Ban;
use arkania\sanctions\ban\BanManager;
use arkania\utils\Utils;
use arkania\webhook\Embed;
use arkania\webhook\Message;
use arkania\webhook\Webhook;
use pocketmine\command\CommandSender;
use pocketmine\command\utils\InvalidCommandSyntaxException;

class BanCommand extends BaseCommand {

    public function __construct() {
        parent::__construct(
            'ban',
            CustomTranslationFactory::arkania_ban_description(),
            '/ban <player> <time> <raison>',
            permission: Permissions::ARKANIA_BAN
        );
    }

    protected function registerArguments(): array {
        return [
            new TargetArgument('target'),
            new StringArgument('time'),
            new TextArgument('raison')
        ];
    }

    public function onRun(CommandSender $player, array $parameters): void {
        if (!$player instanceof CustomPlayer) return;

        $target = $parameters['target'];

        if ($parameters['time'] > '30j') {
            if (!$player->hasPermission(Permissions::ARKANIA_BAN_BYPASS)) {
                $player->sendMessage(CustomTranslationFactory::arkania_ban_cant_ban_more_time());
                return;
            }
        }
        $val = substr($parameters['time'], -1);
        if ($val === 'j'){
            $temps = time() + ((int)$parameters['time']* 86400);
            $format = (int)$parameters['time'] . ' jour(s)';
        }elseif($val === 'h'){
            $temps = time() + ((int)$parameters['time']* 3600);
            $format = (int)$parameters['time'] . ' heure(s)';
        }elseif($val === 'm'){
            $temps = time() + ((int)$parameters['time']* 60);
            $format = (int)$parameters['time'] . ' minute(s)';
        }elseif($val === 's'){
            $temps = time() + ((int)$parameters['time']);
            $format = (int)$parameters['time'] . ' seconde(s)';
        }else
            throw new InvalidCommandSyntaxException();

        if (!isset($args[2])) {
            if (!$player->hasPermission(Permissions::ARKANIA_BAN_BYPASS)) {
                $player->sendMessage(CustomTranslationFactory::arkania_ban_cant_ban_no_reason());
                return;
            }
            $raison = 'Aucun';
        }else{
            $raison = [];
            for ($i = 2;$i < count($args);$i++)
                $raison[] = $args[$i];
            $raison = implode(' ', $raison);
        }
        BanManager::getInstance()->insertBan(
            new Ban(
                $target,
                [
                    'reason' => $raison,
                    'sanctioner' => $player->getName(),
                    'sanction_date' => time(),
                    'expiration_date' => $temps
                ]
            )
        );
        Main::getInstance()->getServer()->broadcastMessage(Utils::getPrefix() . "§e" . $target . "§c vient de se faire bannir du serveur §cdurant §e" . $format . "§c pour le motif §e" . $raison . "§c !");

        $webhook = new Webhook(Main::ADMIN_URL);
        $message = new Message();
        $embed = new Embed();
        $embed->setTitle("**Bannissement**")
            ->setContent('**' . $player->getName() . "** vient de bannir **" . $target . "** d'arkania." . PHP_EOL . PHP_EOL . "*Informations*" . PHP_EOL . "- Banni par **" . $player->getName() . "**" . PHP_EOL . "- Durée : **" . $format . "**" . PHP_EOL . "- Server : **KitMap**" . PHP_EOL . "- Raison : **" . $raison . "**")
            ->setFooter('・Sanction système - ArkaniaStudios')
            ->setColor(0xE70235)
            ->setImage();
        $message->addEmbed($embed);
        $webhook->send($message);

        if (Main::getInstance()->getServer()->getPlayerExact($target) instanceof CustomPlayer)
            Main::getInstance()->getServer()->getPlayerExact($target)->disconnect("§7» §cVous avez été banni d'Arkania:\n§7» §cStaff: " . $player->getName() . "\n§7» §cTemps: §e" . $format . "\n§7» §cMotif: §e" . $raison);
    }

}