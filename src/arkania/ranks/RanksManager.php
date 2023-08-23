<?php
declare(strict_types=1);

namespace arkania\ranks;

use arkania\factions\FactionArgumentInvalidException;
use arkania\language\CustomTranslationFactory;
use arkania\Main;
use arkania\path\Path;
use arkania\path\PathTypeIds;
use arkania\permissions\Permissions;
use arkania\player\CustomPlayer;
use arkania\player\PlayerManager;
use arkania\player\PlayerNotFoundException;
use arkania\ranks\elements\RanksFormatInfo;
use arkania\ranks\elements\RanksPermissions;
use arkania\webhook\Embed;
use arkania\webhook\Message;
use arkania\webhook\Webhook;
use JsonException;
use pocketmine\lang\Translatable;
use pocketmine\permission\PermissionAttachment;
use pocketmine\utils\SingletonTrait;

class RanksManager {
    use SingletonTrait;

    /**
     * @throws JsonException
     */
    public function __construct() {
        if (!file_exists(Main::getInstance()->getDataFolder() . 'ranks')) {
            mkdir(Main::getInstance()->getDataFolder() . 'ranks');
        }
        if (!file_exists(Main::getInstance()->getDataFolder() . 'ranks/Joueur.json')){
            $rank = new Ranks(
                'Joueur',
                new RanksFormatInfo('§7[§f{PLAYER_STATUS}§7] [§e{FACTION}§7] [§8Joueur§7] §r{PLAYER_NAME} §7» §r{MESSAGE}'),
                new RanksFormatInfo('§7[§e{FACTION}§7] {LINE} §f{PLAYER_NAME}'),
                null,
                '§8',
                true
            );
            $rank->create();
        }
    }

    /** @var PermissionAttachment[] */
    private array $attachment = [];

    /**
     * @param Ranks $rank
     * @return Translatable
     * @throws JsonException
     * @throws RankFailureException
     */
    public function addRank(Ranks $rank) : Translatable {
        if (!$rank->create()) {
            throw new RankFailureException(CustomTranslationFactory::arkania_ranks_create_failure($rank->getName())->getText());
        }
        $webhook = new Webhook(Main::ADMIN_URL);
        $message = new Message();
        $embed = new Embed();
        $embed->setTitle('**RANKS - ADD**')
            ->setContent('- Ajout d\'un nouveau grade' . PHP_EOL . PHP_EOL . '*Informations:*' . PHP_EOL . '- Nom: ' . $rank->getName() . PHP_EOL . '- Couleur: ' . $rank->getColor() . PHP_EOL . '- Prefix: ' . $rank->getRankFormatInfo()->getFormat() . PHP_EOL . '- Nametag: ' . $rank->getRankNametagFormatInfo()->getFormat())
            ->setFooter('ArkaniaStudios - Ranks')
            ->setColor(0xEFEB05)
            ->setImage();
        $message->addEmbed($embed);
        $webhook->send($message);
        return CustomTranslationFactory::arkania_ranks_create_success($rank->getName());
    }

    public function exists(string $name) : bool {
        return file_exists(Main::getInstance()->getDataFolder() . 'ranks/' . $name . '.json');
    }

    /**
     * @throws RankFailureException
     * @throws JsonException
     */
    public function delRank(string $name) : Translatable {
        if (!$this->exists($name)) {
            throw new RankFailureException(CustomTranslationFactory::arkania_ranks_delete_failure($name)->getText());
        }

        foreach (glob(Main::getInstance()->getDataFolder() . 'players/*.json') as $file) {
            $player = json_decode(file_get_contents($file), true, 512, JSON_THROW_ON_ERROR);
            if ($player['rank'] === $name) {
                $player['rank'] = 'Joueur';
                file_put_contents($file, json_encode($player, JSON_THROW_ON_ERROR));
            }
        }

        unlink(Main::getInstance()->getDataFolder() . 'ranks/' . $name . '.json');
        $webhook = new Webhook(Main::ADMIN_URL);
        $message = new Message();
        $embed = new Embed();
        $embed->setTitle('**RANKS - DELETE**')
            ->setContent('- Suppression d\'un grade' . PHP_EOL . PHP_EOL . '*Informations:*' . PHP_EOL . '- Nom: ' . $name)
            ->setFooter('ArkaniaStudios - Ranks')
            ->setColor(0xEFEB05)
            ->setImage();
        $message->addEmbed($embed);
        $webhook->send($message);
        return CustomTranslationFactory::arkania_ranks_delete_success($name);
    }

    /**
     * @param string $playerName
     * @param string $rankName
     * @return void
     * @throws PlayerNotFoundException|JsonException
     */
    public function setPlayerRank(string $playerName, string $rankName) : void {
        if (PlayerManager::getInstance()->exist($playerName)){
            $path = Path::config('players/' . $playerName, PathTypeIds::JSON());
            $path->set('rank', $rankName);
            $path->save();

            if (($player = PlayerManager::getInstance()->getPlayerInstance($playerName))){
                if (PlayerManager::getInstance()->isOnline($player->getName() ?? '') && $player instanceof CustomPlayer) {
                    $this->reloadPermissions($player);
                    $this->updateNametag($player->getRank(), $player);
                }
            }
        }else{
            throw new PlayerNotFoundException($playerName);
        }
    }

    /**
     * @param CustomPlayer $player
     * @return PermissionAttachment|null
     * @throws PermissionNullableException
     */
    private function getAttachment(CustomPlayer $player) : ?PermissionAttachment {
        $name = $player->getName();
        if (isset($this->attachment[$name])){
            return $this->attachment[$name];
        }
        throw new PermissionNullableException('PermissionAttachment is null');
    }

    public function getPermissions(CustomPlayer $player) : array {
        $rank = $player->getRank();
        try {
            $permissionManager = new RanksPermissions($rank);
        }catch (RankNotExistException $exception){
            Main::getInstance()->getLogger()->warning($exception->getMessage());
            return [];
        }
        return $permissionManager->getPermissions($player);
    }

    public function reloadPermissions(CustomPlayer $player) : void {
        try {
            $attachment = $this->getAttachment($player);
        }catch (PermissionNullableException $exception){
            Main::getInstance()->getLogger()->warning($exception->getMessage());
            $attachment = $player->addAttachment(Main::getInstance());
            $this->attachment[$player->getName()] = $attachment;
        }
        $attachment->clearPermissions();
        foreach ($this->getPermissions($player) as $permission) {
            $attachment->setPermission($permission, true);
        }
    }

    public function register(CustomPlayer $player) : void {
        $name = $player->getName();
        if (!isset($this->attachment[$name])){
            $this->attachment[$name] = $player->addAttachment(Main::getInstance());
            $this->reloadPermissions($player);
            $player->setNameTag($this->getNametag($player->getRank()));
            $player->setNameTagAlwaysVisible();
        }
    }

    public function unregister(CustomPlayer $player) : void {
        $name = $player->getName();
        if (isset($this->attachment[$name])){
            $player->removeAttachment($this->attachment[$name]);
            unset($this->attachment[$name]);
        }
    }

    public function getFormat(string $rankName) : string {
        $rank = new Ranks($rankName);
        $format = $rank->getRankDataPath()->get('format');
        if ($format === null) {
            return '';
        }
        return $format;
    }

    public function getNametag(string $rankName) : string {
        $rank = new Ranks($rankName);
        $format = $rank->getRankDataPath()->get('nametag');
        if ($format === null) {
            return '';
        }
        return $format;
    }

    /**
     * @throws FactionArgumentInvalidException
     */
    public function updateNametag(string $rankName, CustomPlayer $player) : void {
        $rank = new Ranks($rankName);
        $format = $rank->getRankDataPath()->get('nametag');
        if ($format === null) {
            return;
        }
        $player->setNameTag(str_replace(['{FACTION}', '{LINE}', '{PLAYER_NAME}'], [$player->getFaction()?->getName() ?? '...', "\n", $player->getName()], $format) . "\n" . $player->getTitle()['color'] . $player->getTitle()['title']);
        $player->setNameTagAlwaysVisible();
    }
}