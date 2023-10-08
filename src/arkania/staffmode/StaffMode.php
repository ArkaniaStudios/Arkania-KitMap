<?php
declare(strict_types=1);

namespace arkania\staffmode;

use arkania\factions\FactionArgumentInvalidException;
use arkania\Main;
use arkania\player\CustomPlayer;
use arkania\ranks\RanksManager;
use arkania\utils\Utils;
use pocketmine\block\VanillaBlocks;
use pocketmine\item\VanillaItems;
use pocketmine\network\mcpe\convert\TypeConverter;
use pocketmine\network\mcpe\protocol\PlayerListPacket;
use pocketmine\network\mcpe\protocol\types\PlayerListEntry;
use pocketmine\player\GameMode;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\SingletonTrait;

class StaffMode {
    use SingletonTrait;

    /** @var Main */
    private Main $Main;

    /** @var array */
    private array $inventory = [];

    /** @var array */
    private array $armor = [];

    /** @var array */
    private array $staffmode = [];

    /** @var array */
    private array $vanish = [];

    /** @var array */
    private array $freeze = [];

    /** @var array */
    private array $gamemode = [];

    public function __construct() {
        $this->Main = Main::getInstance();
    }

    /**
     * @param Player $player
     * @return void
     */
    public function saveInventory(Player $player): void {
        $this->inventory[$player->getName()] = $player->getInventory()->getContents();
        $this->armor[$player->getName()] = $player->getArmorInventory()->getContents();
    }

    /**
     * @param Player $player
     * @return void
     */
    public function restorInventory(Player $player): void {
        $player->getInventory()->setContents($this->inventory[$player->getName()]);
        $player->getArmorInventory()->setContents($this->armor[$player->getName()]);
    }

    /**
     * @param Player $player
     * @param bool $value
     * @return void
     */
    public function setFreeze(Player $player, bool $value = true): void {
        if ($value){
            $this->freeze[$player->getName()] = $player->getName();
            $player->setNoClientPredictions();
        }else{
            unset($this->freeze[$player->getName()]);
            $player->setNoClientPredictions(false);
        }
    }

    /**
     * @param Player $player
     * @return bool
     */
    public function isFreeze(Player $player): bool {
        return isset($this->freeze[$player->getName()]);
    }

    public function isInVanish(Player $player): bool {
        return isset($this->vanish[$player->getName()]);
    }

    /**
     * @param CustomPlayer $player
     * @return void
     */
    public function setVanish(CustomPlayer $player): void {
        foreach (Server::getInstance()->getOnlinePlayers() as $onlinePlayer){
            if (!$onlinePlayer->hasPermission('arkania:permission.vanish')) {
                $onlinePlayer->hidePlayer($player);
                $onlinePlayer->sendMessage("[§c-§f] ". $player->getRankFullFormat());
                $entry = new PlayerListEntry();
                $entry->uuid = $player->getUniqueId();
                $pk = new PlayerListPacket();
                $pk->entries[] = $entry;
                $pk->type = PlayerListPacket::TYPE_REMOVE;
                $onlinePlayer->getNetworkSession()->sendDataPacket($pk);
            }else
                $onlinePlayer->showPlayer($player);
        }

        $player->setNameTag('[§cVanish§f] ' . $player->getName());
        $this->vanish[$player->getName()] = $player->getName();
    }

    /**
     * @param CustomPlayer $player
     * @return void
     * @throws FactionArgumentInvalidException
     */
    public function removeVanish(CustomPlayer $player): void {

        foreach ($this->Main->getServer()->getOnlinePlayers() as $onlinePlayer){
            if (!$onlinePlayer->hasPermission('arkania:permission.vanish')) {
                $onlinePlayer->showPlayer($player);
                $onlinePlayer->sendMessage("[§a+§f] ". $player->getRankFullFormat());
                $pk = new PlayerListPacket();
                $pk->type = PlayerListPacket::TYPE_ADD;
                $pk->entries[] = PlayerListEntry::createAdditionEntry(
                    $player->getUniqueId(),
                    $player->getId(),
                    $player->getDisplayName(),
                    TypeConverter::getInstance()->getSkinAdapter()->toSkinData($player->getSkin()),
                    $player->getXuid()
                );
                $onlinePlayer->getNetworkSession()->sendDataPacket($pk);
            }
        }

        RanksManager::getInstance()->updateNameTag($player->getRank(), $player);
        unset($this->vanish[$player->getName()]);
    }


    /**
     * @param Player $player
     * @return bool
     */
    public function isInStaffMode(Player $player): bool {
        return isset($this->staffmode[$player->getName()]);
    }

    /**
     * @param CustomPlayer $player
     * @return void
     */
    public function addStaffMode(CustomPlayer $player): void {

        $this->saveInventory($player);
        $player->getInventory()->clearAll();
        $player->getArmorInventory()->clearAll();

        $this->gamemode[$player->getName()] = $player->getGamemode()->name();
        $player->setGamemode(GameMode::ADVENTURE());
        $player->setFlying(true);
        $player->setAllowFlight(true);

        $player->getInventory()->setItem(0, VanillaItems::DYE()->setCustomName('§c- §fVanish §c-'));
        $player->getInventory()->setItem(2, VanillaItems::BOOK()->setCustomName('§c- §fPlayerInfos §c-'));
        $player->getInventory()->setItem(4, VanillaItems::COMPASS()->setCustomName('§c- §fRandomTp §c-'));
        $player->getInventory()->setItem(6, VanillaBlocks::ICE()->asItem()->setCustomName('§c- §fFreeze §c-'));
        $player->getInventory()->setItem(8, VanillaItems::STONE_AXE()->setCustomName('§c- §fSanctions §c-'));

        $this->setVanish($player);

        $this->staffmode[$player->getName()] = $player->getName();
        $player->sendMessage(Utils::getPrefix() . "§aVous êtes maintenant en staffmode.");
    }

    /**
     * @param CustomPlayer $player
     * @return void
     * @throws FactionArgumentInvalidException
     */
    public function removeStaffMode(CustomPlayer $player): void {
        $this->restorInventory($player);

        $player->setGamemode(GameMode::fromString($this->gamemode[$player->getName()]));
        $player->setFlying(false);
        $player->setAllowFlight(false);

        $this->removeVanish($player);

        unset($this->staffmode[$player->getName()]);
        unset($this->inventory[$player->getName()]);
        unset($this->armor[$player->getName()]);
        unset($this->gamemode[$player->getName()]);
        $player->sendMessage(Utils::getPrefix() . "§cVous n'êtes plus en StaffMode.");
    }

}