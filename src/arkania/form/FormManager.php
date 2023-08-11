<?php
declare(strict_types=1);

namespace arkania\form;

use arkania\form\options\CustomFormResponse;
use arkania\form\options\Input;
use arkania\form\options\Label;
use arkania\form\options\Toggle;
use arkania\player\CustomPlayer;
use pocketmine\utils\SingletonTrait;

class FormManager {
    use SingletonTrait;

    public function sendBroadCastForm(CustomPlayer $player) : void {
        $form = new CustomMenuForm(
            'Broadcast',
            [
                new Label('description', 'Permet d\'envoyer un message à tous les joueurs connectés sur le serveur.'),
                new Toggle('type', 'Important | Normal', true),
                new Input('message', 'Message')
            ],
            function (CustomPlayer $player, CustomFormResponse $response) : void {
                $type = $response->getBool('type');
                $message = $response->getString('message');
                if ($type) {
                    foreach ($player->getServer()->getOnlinePlayers() as $players) {
                        $text = '§e----------------------- §cANNONCE §e(§c' . $player->getName() . '§e) -----------------------';
                        $text = rtrim($text);
                        $text = str_replace(['§e', '§c'], ['', ''], $text);
                        $text = strlen($text);
                        $text = str_repeat('-', $text - 2);
                        $players->sendMessage('§e----------------------- §cANNONCE §e(§c' . $player->getName() . '§e) -----------------------' . "\n\n" . '§c' . $response->getString('message') . "\n\n" . '§e' . $text);
                    }
                }else{
                    $player->getServer()->broadcastMessage('§l§e[!] §r§e' . $message);
                }
            }
        );
        $player->sendForm($form);
    }

}