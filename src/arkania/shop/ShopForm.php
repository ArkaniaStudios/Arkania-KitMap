<?php
declare(strict_types=1);

namespace arkania\shop;

use arkania\economy\EconomyManager;
use arkania\form\base\BaseOption;
use arkania\form\CustomMenuForm;
use arkania\form\image\ButtonIcon;
use arkania\form\MenuForm;
use arkania\form\options\CustomFormResponse;
use arkania\form\options\Dropdown;
use arkania\form\options\Label;
use arkania\form\options\Slider;
use arkania\player\CustomPlayer;
use arkania\utils\Utils;
use pocketmine\block\VanillaBlocks;
use pocketmine\item\Item;
use pocketmine\item\StringToItemParser;
use pocketmine\item\VanillaItems;
use pocketmine\player\Player;
use pocketmine\utils\SingletonTrait;

class ShopForm {
    use SingletonTrait;

    public function __construct() {
        self::setInstance($this);
    }

    /**
     * @param Player $player
     * @return void
     */
    public function sendShopForm(Player $player): void {
        $form = new MenuForm(
            '§c- §fShop §c-',
            '§7» §rChoisissez une catégorie.',
            [
                new BaseOption('§7» §rBlocs', new ButtonIcon(ButtonIcon::IMAGE_TYPE_PATH, 'textures/blocks/dirt')),
                new BaseOption('§7» §rAgriculture', new ButtonIcon(ButtonIcon::IMAGE_TYPE_PATH, 'textures/items/bread')),
                new BaseOption('§7» §rMinerais', new ButtonIcon(ButtonIcon::IMAGE_TYPE_PATH, 'textures/items/diamond')),
                new BaseOption('§7» §rLoots', new ButtonIcon(ButtonIcon::IMAGE_TYPE_PATH, 'textures/items/bone')),
                new BaseOption('§7» §rDivers', new ButtonIcon(ButtonIcon::IMAGE_TYPE_PATH, 'textures/items/diamond_sword'))
            ],
            function (CustomPlayer $player, int $data) : void {
                switch($data){
                    case 0:
                        $this->sendBlocsForm($player);
                        break;
                    case 1:
                        $this->sendAgricultureForm($player);
                        break;
                    case 2:
                        $this->sendOreForm($player);
                        break;
                    case 3:
                        $this->sendLootForm($player);
                        break;
                    default:
                        $this->sendShopForm($player);
                        break;
                }
            }
        );
        $player->sendForm($form);
    }

    /**
     * @param Player $player
     * @return void
     */
    private function sendBlocsForm(Player $player): void {
        $form = new MenuForm(
            '§c- §fBlocs §c-',
            '§7» §rChoisissez une catégorie.',
            [
                new BaseOption('§7» §rBois de chêne', new ButtonIcon(ButtonIcon::IMAGE_TYPE_PATH, 'textures/blocks/log_oak')),
                new BaseOption('§7» §rBois de bouleau', new ButtonIcon(ButtonIcon::IMAGE_TYPE_PATH, 'textures/blocks/log_birch')),
                new BaseOption('§7» §rBois d\'acacia', new ButtonIcon(ButtonIcon::IMAGE_TYPE_PATH, 'textures/blocks/log_acacia')),
                new BaseOption('§7» §rBois de sapin', new ButtonIcon(ButtonIcon::IMAGE_TYPE_PATH, 'textures/blocks/log_big_oak')),
                new BaseOption('§7» §rPierre', new ButtonIcon(ButtonIcon::IMAGE_TYPE_PATH, 'textures/blocks/stone')),
                new BaseOption('§7» §rTerre', new ButtonIcon(ButtonIcon::IMAGE_TYPE_PATH, 'textures/blocks/dirt')),
                new BaseOption('§7» §cRetour', new ButtonIcon(ButtonIcon::IMAGE_TYPE_PATH, 'textures/blocks/barrier'))
            ],
            function(CustomPlayer $player, int $data) : void {
                if ($data === 0)
                    $this->sendLogOakForm($player);
                elseif($data === 1)
                    $this->sendLogBirchForm($player);
                elseif($data === 2)
                    $this->sendLogAcaciaForm($player);
                elseif($data === 3)
                    $this->sendLogSpruceForm($player);
                elseif($data === 4)
                    $this->sendStoneForm($player);
                elseif($data === 5)
                    $this->sendCobblestoneForm($player);
                elseif($data === 6)
                    $this->sendDirtForm($player);
                else
                    $this->sendShopForm($player);
            }
        );
        $player->sendForm($form);
    }

    /**
     * @param Player $player
     * @return void
     */
    public function sendLogOakForm(Player $player): void {
        $form = new CustomMenuForm(
            '§c- §fBois de chêne §c-',
            [
                new Label('label','-------------------------------' . PHP_EOL . '§7» §rVous avez actuellement §e' . EconomyManager::getInstance()->getMoney($player->getName()) . '' . PHP_EOL . '§a4/u' . PHP_EOL . '§c1/u' . PHP_EOL . '§f-------------------------------'),
                new Dropdown('action', '§7» §rAction: ', ['§aAcheter', '§cVendre']),
                new Slider('nombre', '§7» §rNombre: ', 0, 64)
            ],
            function (CustomPlayer $player, CustomFormResponse $response) : void {
                $action = ['§aAcheter', '$cVendre'];
                $slider = (int)$response->getFloat('nombre');

                if ($action[$response->getInt('action')] === '§aAcheter'){
                    $item = StringToItemParser::getInstance()->parse(VanillaBlocks::OAK_LOG()->asItem()->getVanillaName())->setCount($slider);
                    if (!$player->getInventory()->canAddItem($item)){
                        $player->sendMessage(Utils::getPrefix() . "§cVotre inventaire est complet vous ne pouvez donc pas acheter §e" . $slider . "§c tronc de chêne.");
                        return;
                    }

                    if (EconomyManager::getInstance()->getMoney($player->getName()) < $slider * 4){
                        $player->sendMessage(Utils::getPrefix() . "§cVous n'avez pas assez d'argent pour acheter §e" . $slider . "§c tronc de chêne.");
                        return;
                    }

                    EconomyManager::getInstance()->delMoney($player->getName(), $slider * 4);
                    $player->getInventory()->addItem($item);
                    $player->sendMessage(Utils::getPrefix() . "§aVous avez acheté §e" . $slider . '§a tronc de chêne pour un total de §e' . $slider * 4 . '§a.');
                }else{
                    $item = $this->countItem($player, VanillaBlocks::OAK_LOG()->asItem()->getTypeId());
                    $itemSell = StringToItemParser::getInstance()->parse(VanillaBlocks::OAK_LOG()->asItem()->getVanillaName())->setCount($slider);
                    if ($slider > $item){
                        $player->sendMessage(Utils::getPrefix() . "§cVous n'avez pas §e" . $slider . "§c tronc de chêne à vendre.");
                        return;
                    }
                    EconomyManager::getInstance()->addMoney($player->getName(), $slider);
                    $player->getInventory()->removeItem($itemSell);
                    $player->sendMessage(Utils::getPrefix() . "§aVous avez vendu §e" . $slider . "§a tronc de chêne pour §e$slider .");

                }
            }
        );
        $player->sendForm($form);
    }

    /**
     * @param Player $player
     * @return void
     */
    public function sendLogBirchForm(Player $player): void {
        $form = new CustomMenuForm(
            '§c- §fBois de bouleau §c-',
            [
                new Label('label','-------------------------------' . PHP_EOL . '§7» §rVous avez actuellement §e' . EconomyManager::getInstance()->getMoney($player->getName()) . '' . PHP_EOL . '§a4/u' . PHP_EOL . '§c1/u' . PHP_EOL . '§f-------------------------------'),
                new Dropdown('action', '§7» §rAction: ', ['§aAcheter', '§cVendre']),
                new Slider('nombre', '§7» §rNombre: ', 0, 64)
            ],
            function (CustomPlayer $player, CustomFormResponse $response) : void {
                $action = ['§aAcheter', '$cVendre'];
                $slider = (int)$response->getFloat('nombre');

                if ($action[$response->getInt('action')] === '§aAcheter') {
                    $item = StringToItemParser::getInstance()->parse(VanillaBlocks::BIRCH_LOG()->asItem()->getVanillaName())->setCount($slider);
                    if (!$player->getInventory()->canAddItem($item)) {
                        $player->sendMessage(Utils::getPrefix() . "§cVotre inventaire est complet vous ne pouvez donc pas acheter §e" . $slider . "§c tronc de bouleau.");
                        return;
                    }

                    if (EconomyManager::getInstance()->getMoney($player->getName()) < $slider * 4) {
                        $player->sendMessage(Utils::getPrefix() . "§cVous n'avez pas assez d'argent pour acheter §e" . $slider . "§c tronc de bouleau.");
                        return;
                    }

                    EconomyManager::getInstance()->delMoney($player->getName(), $slider * 4);
                    $player->getInventory()->addItem($item);
                    $player->sendMessage(Utils::getPrefix() . "§aVous avez acheté §e" . $slider . '§a tronc de bouleau pour un total de §e' . $slider * 4 . '§a.');
                } else {
                    $item = $this->countItem($player, VanillaBlocks::BIRCH_LOG()->asItem()->getTypeId());
                    $itemSell = StringToItemParser::getInstance()->parse(VanillaBlocks::BIRCH_LOG()->asItem()->getVanillaName())->setCount($slider);
                    if ($slider > $item) {
                        $player->sendMessage(Utils::getPrefix() . "§cVous n'avez pas §e" . $slider . "§c tronc de bouleau à vendre.");
                        return;
                    }
                    EconomyManager::getInstance()->addMoney($player->getName(), $slider);
                    $player->getInventory()->removeItem($itemSell);
                    $player->sendMessage(Utils::getPrefix() . "§aVous avez vendu §e" . $slider . "§a tronc de bouleau pour §e$slider .");
                }
            });
        $player->sendForm($form);
    }

    /**
     * @param Player $player
     * @return void
     */
    public function sendLogAcaciaForm(Player $player): void {
        $form = new CustomMenuForm(
            '§c- §fBois d\'acacia §c-',
            [
                new Label('label','-------------------------------' . PHP_EOL . '§7» §rVous avez actuellement §e' . EconomyManager::getInstance()->getMoney($player->getName()) . '' . PHP_EOL . '§a4/u' . PHP_EOL . '§c1/u' . PHP_EOL . '§f-------------------------------'),
                new Dropdown('action', '§7» §rAction: ', ['§aAcheter', '§cVendre']),
                new Slider('nombre', '§7» §rNombre: ', 0, 64)
            ],
            function (CustomPlayer $player, CustomFormResponse $response) : void {
                $action = ['§aAcheter', '$cVendre'];
                $slider = (int)$response->getFloat('nombre');

                if ($action[$response->getInt('action')] === '§aAcheter') {
                    $item = StringToItemParser::getInstance()->parse(VanillaBlocks::ACACIA_LOG()->asItem()->getVanillaName())->setCount($slider);
                    if (!$player->getInventory()->canAddItem($item)) {
                        $player->sendMessage(Utils::getPrefix() . "§cVotre inventaire est complet vous ne pouvez donc pas acheter §e" . $slider . "§c tronc d'acacia.");
                        return;
                    }

                    if (EconomyManager::getInstance()->getMoney($player->getName()) < $slider * 4) {
                        $player->sendMessage(Utils::getPrefix() . "§cVous n'avez pas assez d'argent pour acheter §e" . $slider . "§c tronc d'acacia.");
                        return;
                    }

                    EconomyManager::getInstance()->delMoney($player->getName(), $slider * 4);
                    $player->getInventory()->addItem($item);
                    $player->sendMessage(Utils::getPrefix() . "§aVous avez acheté §e" . $slider . '§a tronc d\'acacia pour un total de §e' . $slider * 4 . '§a.');
                } else {
                    $item = $this->countItem($player, VanillaBlocks::ACACIA_LOG()->asItem()->getTypeId());
                    $itemSell = StringToItemParser::getInstance()->parse(VanillaBlocks::ACACIA_LOG()->asItem()->getVanillaName())->setCount($slider);
                    if ($slider > $item) {
                        $player->sendMessage(Utils::getPrefix() . "§cVous n'avez pas §e" . $slider . "§c tronc d'acacia à vendre.");
                        return;
                    }
                    EconomyManager::getInstance()->addMoney($player->getName(), $slider);
                    $player->getInventory()->removeItem($itemSell);
                    $player->sendMessage(Utils::getPrefix() . "§aVous avez vendu §e" . $slider . "§a tronc d'acacia pour §e$slider .");
                }
            });
        $player->sendForm($form);
    }

    /**
     * @param Player $player
     * @return void
     */
    public function sendLogSpruceForm(Player $player): void {
        $form = new CustomMenuForm(
            '§c- §fBois de sapin §c-',
            [
                new Label('label','-------------------------------' . PHP_EOL . '§7» §rVous avez actuellement §e' . EconomyManager::getInstance()->getMoney($player->getName()) . '' . PHP_EOL . '§a4/u' . PHP_EOL . '§c1/u' . PHP_EOL . '§f-------------------------------'),
                new Dropdown('action', '§7» §rAction: ', ['§aAcheter', '§cVendre']),
                new Slider('nombre', '§7» §rNombre: ', 0, 64)
            ],
            function (CustomPlayer $player, CustomFormResponse $response) : void {
                $action = ['§aAcheter', '$cVendre'];
                $slider = (int)$response->getFloat('nombre');

                if ($action[$response->getInt('action')] === '§aAcheter') {
                    $item = StringToItemParser::getInstance()->parse(VanillaBlocks::SPRUCE_LOG()->asItem()->getVanillaName())->setCount($slider);
                    if (!$player->getInventory()->canAddItem($item)) {
                        $player->sendMessage(Utils::getPrefix() . "§cVotre inventaire est complet vous ne pouvez donc pas acheter §e" . $slider . "§c tronc de sapin.");
                        return;
                    }

                    if (EconomyManager::getInstance()->getMoney($player->getName()) < $slider * 4) {
                        $player->sendMessage(Utils::getPrefix() . "§cVous n'avez pas assez d'argent pour acheter §e" . $slider . "§c tronc de sapin.");
                        return;
                    }

                    EconomyManager::getInstance()->delMoney($player->getName(), $slider * 4);
                    $player->getInventory()->addItem($item);
                    $player->sendMessage(Utils::getPrefix() . "§aVous avez acheté §e" . $slider . '§a tronc de sapin pour un total de §e' . $slider * 4 . '§a.');
                } else {
                    $item = $this->countItem($player, VanillaBlocks::SPRUCE_LOG()->asItem()->getTypeId());
                    $itemSell = StringToItemParser::getInstance()->parse(VanillaBlocks::SPRUCE_LOG()->asItem()->getVanillaName())->setCount($slider);
                    if ($slider > $item) {
                        $player->sendMessage(Utils::getPrefix() . "§cVous n'avez pas §e" . $slider . "§c tronc de sapin à vendre.");
                        return;
                    }
                    EconomyManager::getInstance()->addMoney($player->getName(), $slider);
                    $player->getInventory()->removeItem($itemSell);
                    $player->sendMessage(Utils::getPrefix() . "§aVous avez vendu §e" . $slider . "§a tronc de sapin pour §e$slider .");
                }
            });
        $player->sendForm($form);
    }

    /**
     * @param Player $player
     * @return void
     */
    public function sendStoneForm(Player $player): void {
        $form = new CustomMenuForm(
            '§c- §fPierre §c-',
            [
                new Label('label','-------------------------------' . PHP_EOL . '§7» §rVous avez actuellement §e' . EconomyManager::getInstance()->getMoney($player->getName()) . '' . PHP_EOL . '§a5/u' . PHP_EOL . '§c1/u' . PHP_EOL . '§f-------------------------------'),
                new Dropdown('action', '§7» §rAction: ', ['§aAcheter', '§cVendre']),
                new Slider('nombre', '§7» §rNombre: ', 0, 64)
            ],
            function (CustomPlayer $player, CustomFormResponse $response) : void {
                $action = ['§aAcheter', '$cVendre'];
                $slider = (int)$response->getFloat('nombre');

                if ($action[$response->getInt('action')] === '§aAcheter') {
                    $item = StringToItemParser::getInstance()->parse(VanillaBlocks::STONE()->asItem()->getVanillaName())->setCount($slider);
                    if (!$player->getInventory()->canAddItem($item)) {
                        $player->sendMessage(Utils::getPrefix() . "§cVotre inventaire est complet vous ne pouvez donc pas acheter §e" . $slider . "§c pierre.");
                        return;
                    }

                    if (EconomyManager::getInstance()->getMoney($player->getName()) < $slider * 5) {
                        $player->sendMessage(Utils::getPrefix() . "§cVous n'avez pas assez d'argent pour acheter §e" . $slider . "§c pierre.");
                        return;
                    }

                    EconomyManager::getInstance()->delMoney($player->getName(), $slider * 5);
                    $player->getInventory()->addItem($item);
                    $player->sendMessage(Utils::getPrefix() . "§aVous avez acheté §e" . $slider . '§a pierre pour un total de §e' . $slider * 5 . '§a.');
                } else {
                    $item = $this->countItem($player, VanillaBlocks::STONE()->asItem()->getTypeId());
                    $itemSell = StringToItemParser::getInstance()->parse(VanillaBlocks::STONE()->asItem()->getVanillaName())->setCount($slider);
                    if ($slider > $item) {
                        $player->sendMessage(Utils::getPrefix() . "§cVous n'avez pas §e" . $slider . "§c pierre à vendre.");
                        return;
                    }
                    EconomyManager::getInstance()->addMoney($player->getName(), $slider);
                    $player->getInventory()->removeItem($itemSell);
                    $player->sendMessage(Utils::getPrefix() . "§aVous avez vendu §e" . $slider . "§a pierre pour §e$slider .");
                }
            });
        $player->sendForm($form);
    }

    /**
     * @param Player $player
     * @return void
     */
    public function sendCobblestoneForm(Player $player): void {
        $form = new CustomMenuForm(
            '§c- §fCobblestone §c-',
            [
                new Label('label','-------------------------------' . PHP_EOL . '§7» §rVous avez actuellement §e' . EconomyManager::getInstance()->getMoney($player->getName()) . '' . PHP_EOL . '§a3/u' . PHP_EOL . '§c1/u' . PHP_EOL . '§f-------------------------------'),
                new Dropdown('action', '§7» §rAction: ', ['§aAcheter', '§cVendre']),
                new Slider('nombre', '§7» §rNombre: ', 0, 64)
            ],
            function (CustomPlayer $player, CustomFormResponse $response) : void {
                $action = ['§aAcheter', '$cVendre'];
                $slider = (int)$response->getFloat('nombre');

                if ($action[$response->getInt('action')] === '§aAcheter') {
                    $item = StringToItemParser::getInstance()->parse(VanillaBlocks::COBBLESTONE()->asItem()->getVanillaName())->setCount($slider);
                    if (!$player->getInventory()->canAddItem($item)) {
                        $player->sendMessage(Utils::getPrefix() . "§cVotre inventaire est complet vous ne pouvez donc pas acheter §e" . $slider . "§c pierre taillé.");
                        return;
                    }

                    if (EconomyManager::getInstance()->getMoney($player->getName()) < $slider * 3) {
                        $player->sendMessage(Utils::getPrefix() . "§cVous n'avez pas assez d'argent pour acheter §e" . $slider . "§c pierre taillé.");
                        return;
                    }

                    EconomyManager::getInstance()->delMoney($player->getName(), $slider * 3);
                    $player->getInventory()->addItem($item);
                    $player->sendMessage(Utils::getPrefix() . "§aVous avez acheté §e" . $slider . '§a pierre taillé pour un total de §e' . $slider * 3 . '§a.');
                } else {
                    $item = $this->countItem($player, VanillaBlocks::COBBLESTONE()->asItem()->getTypeId());
                    $itemSell = StringToItemParser::getInstance()->parse(VanillaBlocks::COBBLESTONE()->asItem()->getVanillaName())->setCount($slider);
                    if ($slider > $item) {
                        $player->sendMessage(Utils::getPrefix() . "§cVous n'avez pas §e" . $slider . "§c pierre taillé à vendre.");
                        return;
                    }
                    EconomyManager::getInstance()->addMoney($player->getName(), $slider);
                    $player->getInventory()->removeItem($itemSell);
                    $player->sendMessage(Utils::getPrefix() . "§aVous avez vendu §e" . $slider . "§a pierre taillé pour §e$slider .");
                }
            });
        $player->sendForm($form);
    }

    public function sendDirtForm(Player $player): void {
        $form = new CustomMenuForm(
            '§c- §fTerre §c-',
            [
                new Label('label','-------------------------------' . PHP_EOL . '§7» §rVous avez actuellement §e' . EconomyManager::getInstance()->getMoney($player->getName()) . '' . PHP_EOL . '§a20/u' . PHP_EOL . '§c1/u' . PHP_EOL . '§f-------------------------------'),
                new Dropdown('action', '§7» §rAction: ', ['§aAcheter', '§cVendre']),
                new Slider('nombre', '§7» §rNombre: ', 0, 64)
            ],
            function (CustomPlayer $player, CustomFormResponse $response) : void {
                $action = ['§aAcheter', '$cVendre'];
                $slider = (int)$response->getFloat('nombre');

                if ($action[$response->getInt('action')] === '§aAcheter') {
                    $item = StringToItemParser::getInstance()->parse(VanillaBlocks::DIRT()->asItem()->getVanillaName())->setCount($slider);
                    if (!$player->getInventory()->canAddItem($item)) {
                        $player->sendMessage(Utils::getPrefix() . "§cVotre inventaire est complet vous ne pouvez donc pas acheter §e" . $slider . "§c terre.");
                        return;
                    }

                    if (EconomyManager::getInstance()->getMoney($player->getName()) < $slider * 20) {
                        $player->sendMessage(Utils::getPrefix() . "§cVous n'avez pas assez d'argent pour acheter §e" . $slider . "§c terre.");
                        return;
                    }

                    EconomyManager::getInstance()->delMoney($player->getName(), $slider * 20);
                    $player->getInventory()->addItem($item);
                    $player->sendMessage(Utils::getPrefix() . "§aVous avez acheté §e" . $slider . '§a terre pour un total de §e' . $slider * 20 . '§a.');
                } else {
                    $item = $this->countItem($player, VanillaBlocks::DIRT()->asItem()->getTypeId());
                    $itemSell = StringToItemParser::getInstance()->parse(VanillaBlocks::DIRT()->asItem()->getVanillaName())->setCount($slider);
                    if ($slider > $item) {
                        $player->sendMessage(Utils::getPrefix() . "§cVous n'avez pas §e" . $slider . "§c terre à vendre.");
                        return;
                    }
                    EconomyManager::getInstance()->addMoney($player->getName(), $slider);
                    $player->getInventory()->removeItem($itemSell);
                    $player->sendMessage(Utils::getPrefix() . "§aVous avez vendu §e" . $slider . "§a terre pour §e$slider .");
                }
            });
        $player->sendForm($form);
    }

    /**
     * @param Player $player
     * @return void
     */
    private function sendAgricultureForm(Player $player): void {
        $form = new MenuForm(
            '§c- §fAgriculture §c-',
            '§7» §rChoisissez une catégorie.',
            [
                new BaseOption('§7» §rCactus', new ButtonIcon(ButtonIcon::IMAGE_TYPE_PATH, 'textures/blocks/cactus_side')),
                new BaseOption('§7» §rCitrouille', new ButtonIcon(ButtonIcon::IMAGE_TYPE_PATH, 'textures/blocks/pumpkin_side')),
                new BaseOption('§7» §rPastèque', new ButtonIcon(ButtonIcon::IMAGE_TYPE_PATH, 'textures/items/melon')),
                new BaseOption('§7» §rPomme de terre', new ButtonIcon(ButtonIcon::IMAGE_TYPE_PATH, 'textures/items/potato')),
                new BaseOption('§7» §rCarrotes', new ButtonIcon(ButtonIcon::IMAGE_TYPE_PATH, 'textures/items/carrot')),
                new BaseOption('§7» §rBlé', new ButtonIcon(ButtonIcon::IMAGE_TYPE_PATH, 'textures/items/wheat')),
                new BaseOption('§7» §rGraine', new ButtonIcon(ButtonIcon::IMAGE_TYPE_PATH, 'textures/items/seeds_wheat')),
                new BaseOption('§7» §cRetour', new ButtonIcon(ButtonIcon::IMAGE_TYPE_PATH, 'textures/blocks/barrier'))
            ],
            function (CustomPlayer $player, int $data) : void {
                if ($data === 0)
                    $this->sendCactusForm($player);
                elseif($data === 1)
                    $this->sendPumpkinForm($player);
                elseif($data === 2)
                    $this->sendWaterMelonForm($player);
                elseif($data === 3)
                    $this->sendPotetoForm($player);
                elseif($data === 4)
                    $this->sendCarrotForm($player);
                elseif($data === 5)
                    $this->sendWheatForm($player);
                elseif($data === 6)
                    $this->sendSeedForm($player);
                else
                    $this->sendShopForm($player);
            }
        );
        $player->sendForm($form);
    }

    public function sendCactusForm(Player $player): void {
        $form = new CustomMenuForm(
            '§c- §fCactus §c-',
            [
                new Label('label','-------------------------------' . PHP_EOL . '§7» §rVous avez actuellement §e' . EconomyManager::getInstance()->getMoney($player->getName()) . '' . PHP_EOL . '§a50/u' . PHP_EOL . '§c5/u' . PHP_EOL . '§f-------------------------------'),
                new Dropdown('action', '§7» §rAction: ', ['§aAcheter', '§cVendre']),
                new Slider('nombre', '§7» §rNombre: ', 0, 64)
            ],
            function (CustomPlayer $player, CustomFormResponse $response) : void {
                $action = ['§aAcheter', '$cVendre'];
                $slider = (int)$response->getFloat('nombre');

                if ($action[$response->getInt('action')] === '§aAcheter') {
                    $item = StringToItemParser::getInstance()->parse(VanillaBlocks::CACTUS()->asItem()->getVanillaName())->setCount($slider);
                    if (!$player->getInventory()->canAddItem($item)) {
                        $player->sendMessage(Utils::getPrefix() . "§cVotre inventaire est complet vous ne pouvez donc pas acheter §e" . $slider . "§c cactus.");
                        return;
                    }

                    if (EconomyManager::getInstance()->getMoney($player->getName()) < $slider * 50) {
                        $player->sendMessage(Utils::getPrefix() . "§cVous n'avez pas assez d'argent pour acheter §e" . $slider . "§c cactus.");
                        return;
                    }

                    EconomyManager::getInstance()->delMoney($player->getName(), $slider * 50);
                    $player->getInventory()->addItem($item);
                    $player->sendMessage(Utils::getPrefix() . "§aVous avez acheté §e" . $slider . '§a cactus pour un total de §e' . $slider * 50 . '§a.');
                } else {
                    $item = $this->countItem($player, VanillaBlocks::CACTUS()->asItem()->getTypeId());
                    $itemSell = StringToItemParser::getInstance()->parse(VanillaBlocks::CACTUS()->asItem()->getVanillaName())->setCount($slider);
                    if ($slider > $item) {
                        $player->sendMessage(Utils::getPrefix() . "§cVous n'avez pas §e" . $slider . "§c cactus à vendre.");
                        return;
                    }
                    EconomyManager::getInstance()->addMoney($player->getName(), $slider);
                    $player->getInventory()->removeItem($itemSell);
                    $player->sendMessage(Utils::getPrefix() . "§aVous avez vendu §e" . $slider . "§a cactus pour §e$slider .");
                }
            });
        $player->sendForm($form);
    }

    /**
     * @param Player $player
     * @return void
     */
    public function sendPumpkinForm(Player $player): void {
        $form = new CustomMenuForm(
            '§c- §fCitrouille §c-',
            [
                new Label('label','-------------------------------' . PHP_EOL . '§7» §rVous avez actuellement §e' . EconomyManager::getInstance()->getMoney($player->getName()) . '' . PHP_EOL . '§a225/u' . PHP_EOL . '§c3/u' . PHP_EOL . '§f-------------------------------'),
                new Dropdown('action', '§7» §rAction: ', ['§aAcheter', '§cVendre']),
                new Slider('nombre', '§7» §rNombre: ', 0, 64)
            ],
            function (CustomPlayer $player, CustomFormResponse $response) : void {
                $action = ['§aAcheter', '$cVendre'];
                $slider = (int)$response->getFloat('nombre');

                if ($action[$response->getInt('action')] === '§aAcheter') {
                    $item = StringToItemParser::getInstance()->parse(VanillaBlocks::PUMPKIN()->asItem()->getVanillaName())->setCount($slider);
                    if (!$player->getInventory()->canAddItem($item)) {
                        $player->sendMessage(Utils::getPrefix() . "§cVotre inventaire est complet vous ne pouvez donc pas acheter §e" . $slider . "§c citrouilles.");
                        return;
                    }

                    if (EconomyManager::getInstance()->getMoney($player->getName()) < $slider * 225) {
                        $player->sendMessage(Utils::getPrefix() . "§cVous n'avez pas assez d'argent pour acheter §e" . $slider . "§c citrouilles.");
                        return;
                    }

                    EconomyManager::getInstance()->delMoney($player->getName(), $slider * 225);
                    $player->getInventory()->addItem($item);
                    $player->sendMessage(Utils::getPrefix() . "§aVous avez acheté §e" . $slider . '§a citrouilles pour un total de §e' . $slider * 225 . '§a.');
                } else {
                    $item = $this->countItem($player, VanillaBlocks::PUMPKIN()->asItem()->getTypeId());
                    $itemSell = StringToItemParser::getInstance()->parse(VanillaBlocks::PUMPKIN()->asItem()->getVanillaName())->setCount($slider);
                    if ($slider > $item) {
                        $player->sendMessage(Utils::getPrefix() . "§cVous n'avez pas §e" . $slider . "§c citrouilles à vendre.");
                        return;
                    }
                    EconomyManager::getInstance()->addMoney($player->getName(), $slider);
                    $player->getInventory()->removeItem($itemSell);
                    $player->sendMessage(Utils::getPrefix() . "§aVous avez vendu §e" . $slider . "§a citrouilles pour §e$slider .");
                }
            });
        $player->sendForm($form);
    }

    /**
     * @param Player $player
     * @return void
     */
    public function sendWaterMelonForm(Player $player): void {
        $form = new CustomMenuForm(
            '§c- §fPastèque §c-',
            [
                new Label('label','-------------------------------' . PHP_EOL . '§7» §rVous avez actuellement §e' . EconomyManager::getInstance()->getMoney($player->getName()) . '' . PHP_EOL . '§a225/u' . PHP_EOL . '§c3/u' . PHP_EOL . '§f-------------------------------'),
                new Dropdown('action', '§7» §rAction: ', ['§aAcheter', '§cVendre']),
                new Slider('nombre', '§7» §rNombre: ', 0, 64)
            ],
            function (CustomPlayer $player, CustomFormResponse $response) : void {
                $action = ['§aAcheter', '$cVendre'];
                $slider = (int)$response->getFloat('nombre');

                if ($action[$response->getInt('action')] === '§aAcheter') {
                    $item = StringToItemParser::getInstance()->parse(VanillaBlocks::MELON_BLOCK()->asItem()->getVanillaName())->setCount($slider);
                    if (!$player->getInventory()->canAddItem($item)) {
                        $player->sendMessage(Utils::getPrefix() . "§cVotre inventaire est complet vous ne pouvez donc pas acheter §e" . $slider . "§c pastèque.");
                        return;
                    }

                    if (EconomyManager::getInstance()->getMoney($player->getName()) < $slider * 225) {
                        $player->sendMessage(Utils::getPrefix() . "§cVous n'avez pas assez d'argent pour acheter §e" . $slider . "§c pastèque.");
                        return;
                    }

                    EconomyManager::getInstance()->delMoney($player->getName(), $slider * 225);
                    $player->getInventory()->addItem($item);
                    $player->sendMessage(Utils::getPrefix() . "§aVous avez acheté §e" . $slider . '§a pastèque pour un total de §e' . $slider * 225 . '§a.');
                } else {
                    $item = $this->countItem($player, VanillaBlocks::MELON_BLOCK()->asItem()->getTypeId());
                    $itemSell = StringToItemParser::getInstance()->parse(VanillaBlocks::MELON_BLOCK()->asItem()->getVanillaName())->setCount($slider);
                    if ($slider > $item) {
                        $player->sendMessage(Utils::getPrefix() . "§cVous n'avez pas §e" . $slider . "§c pastèque à vendre.");
                        return;
                    }
                    EconomyManager::getInstance()->addMoney($player->getName(), $slider);
                    $player->getInventory()->removeItem($itemSell);
                    $player->sendMessage(Utils::getPrefix() . "§aVous avez vendu §e" . $slider . "§a pastèque pour §e$slider .");
                }
            });
        $player->sendForm($form);
    }

    /**
     * @param Player $player
     * @return void
     */
    public function sendPotetoForm(Player $player): void {
        $form = new CustomMenuForm(
            '§c- §fPomme de terre §c-',
            [
                new Label('label','-------------------------------' . PHP_EOL . '§7» §rVous avez actuellement §e' . EconomyManager::getInstance()->getMoney($player->getName()) . '' . PHP_EOL . '§a50/u' . PHP_EOL . '§c1/u' . PHP_EOL . '§f-------------------------------'),
                new Dropdown('action', '§7» §rAction: ', ['§aAcheter', '§cVendre']),
                new Slider('nombre', '§7» §rNombre: ', 0, 64)
            ],
            function (CustomPlayer $player, CustomFormResponse $response) : void {
                $action = ['§aAcheter', '$cVendre'];
                $slider = (int)$response->getFloat('nombre');

                if ($action[$response->getInt('action')] === '§aAcheter') {
                    $item = StringToItemParser::getInstance()->parse(VanillaItems::POTATO()->getVanillaName())->setCount($slider);
                    if (!$player->getInventory()->canAddItem($item)) {
                        $player->sendMessage(Utils::getPrefix() . "§cVotre inventaire est complet vous ne pouvez donc pas acheter §e" . $slider . "§c pomme de terre.");
                        return;
                    }

                    if (EconomyManager::getInstance()->getMoney($player->getName()) < $slider * 50) {
                        $player->sendMessage(Utils::getPrefix() . "§cVous n'avez pas assez d'argent pour acheter §e" . $slider . "§c pomme de terre.");
                        return;
                    }

                    EconomyManager::getInstance()->delMoney($player->getName(), $slider * 50);
                    $player->getInventory()->addItem($item);
                    $player->sendMessage(Utils::getPrefix() . "§aVous avez acheté §e" . $slider . '§a pomme de terre pour un total de §e' . $slider * 50 . '§a.');
                } else {
                    $item = $this->countItem($player, VanillaItems::POTATO()->getTypeId());
                    $itemSell = StringToItemParser::getInstance()->parse(VanillaItems::POTATO()->getVanillaName())->setCount($slider);
                    if ($slider > $item) {
                        $player->sendMessage(Utils::getPrefix() . "§cVous n'avez pas §e" . $slider . "§c pomme de terre à vendre.");
                        return;
                    }
                    EconomyManager::getInstance()->addMoney($player->getName(), $slider);
                    $player->getInventory()->removeItem($itemSell);
                    $player->sendMessage(Utils::getPrefix() . "§aVous avez vendu §e" . $slider . "§a pomme de terre pour §e$slider .");
                }
            });
        $player->sendForm($form);
    }

    /**
     * @param Player $player
     * @return void
     */
    public function sendCarrotForm(Player $player): void {
        $form = new CustomMenuForm(
            '§c- §fCarrote §c-',
            [
                new Label('label','-------------------------------' . PHP_EOL . '§7» §rVous avez actuellement §e' . EconomyManager::getInstance()->getMoney($player->getName()) . '' . PHP_EOL . '§a50/u' . PHP_EOL . '§c1/u' . PHP_EOL . '§f-------------------------------'),
                new Dropdown('action', '§7» §rAction: ', ['§aAcheter', '§cVendre']),
                new Slider('nombre', '§7» §rNombre: ', 0, 64)
            ],
            function (CustomPlayer $player, CustomFormResponse $response) : void {
                $action = ['§aAcheter', '$cVendre'];
                $slider = (int)$response->getFloat('nombre');

                if ($action[$response->getInt('action')] === '§aAcheter') {
                    $item = StringToItemParser::getInstance()->parse(VanillaItems::CARROT()->getVanillaName())->setCount($slider);
                    if (!$player->getInventory()->canAddItem($item)) {
                        $player->sendMessage(Utils::getPrefix() . "§cVotre inventaire est complet vous ne pouvez donc pas acheter §e" . $slider . "§c carrote.");
                        return;
                    }

                    if (EconomyManager::getInstance()->getMoney($player->getName()) < $slider * 50) {
                        $player->sendMessage(Utils::getPrefix() . "§cVous n'avez pas assez d'argent pour acheter §e" . $slider . "§c carrote.");
                        return;
                    }

                    EconomyManager::getInstance()->delMoney($player->getName(), $slider * 50);
                    $player->getInventory()->addItem($item);
                    $player->sendMessage(Utils::getPrefix() . "§aVous avez acheté §e" . $slider . '§a carrote pour un total de §e' . $slider * 50 . '§a.');
                } else {
                    $item = $this->countItem($player, VanillaItems::CARROT()->getTypeId());
                    $itemSell = StringToItemParser::getInstance()->parse(VanillaItems::CARROT()->getVanillaName())->setCount($slider);
                    if ($slider > $item) {
                        $player->sendMessage(Utils::getPrefix() . "§cVous n'avez pas §e" . $slider . "§c carrote à vendre.");
                        return;
                    }
                    EconomyManager::getInstance()->addMoney($player->getName(), $slider);
                    $player->getInventory()->removeItem($itemSell);
                    $player->sendMessage(Utils::getPrefix() . "§aVous avez vendu §e" . $slider . "§a carrote pour §e$slider .");
                }
            });
        $player->sendForm($form);
    }

    /**
     * @param Player $player
     * @return void
     */
    public function sendWheatForm(Player $player): void {
        $form = new CustomMenuForm(
            '§c- §fBlé §c-',
            [
                new Label('label','-------------------------------' . PHP_EOL . '§7» §rVous avez actuellement §e' . EconomyManager::getInstance()->getMoney($player->getName()) . '' . PHP_EOL . '§a75/u' . PHP_EOL . '§c2/u' . PHP_EOL . '§f-------------------------------'),
                new Dropdown('action', '§7» §rAction: ', ['§aAcheter', '§cVendre']),
                new Slider('nombre', '§7» §rNombre: ', 0, 64)
            ],
            function (CustomPlayer $player, CustomFormResponse $response) : void {
                $action = ['§aAcheter', '$cVendre'];
                $slider = (int)$response->getFloat('nombre');

                if ($action[$response->getInt('action')] === '§aAcheter') {
                    $item = StringToItemParser::getInstance()->parse(VanillaItems::WHEAT()->getVanillaName())->setCount($slider);
                    if (!$player->getInventory()->canAddItem($item)) {
                        $player->sendMessage(Utils::getPrefix() . "§cVotre inventaire est complet vous ne pouvez donc pas acheter §e" . $slider . "§c blé.");
                        return;
                    }

                    if (EconomyManager::getInstance()->getMoney($player->getName()) < $slider * 75) {
                        $player->sendMessage(Utils::getPrefix() . "§cVous n'avez pas assez d'argent pour acheter §e" . $slider . "§c blé.");
                        return;
                    }

                    EconomyManager::getInstance()->delMoney($player->getName(), $slider * 75);
                    $player->getInventory()->addItem($item);
                    $player->sendMessage(Utils::getPrefix() . "§aVous avez acheté §e" . $slider . '§a blé pour un total de §e' . $slider * 75 . '§a.');
                } else {
                    $item = $this->countItem($player, VanillaItems::WHEAT()->getTypeId());
                    $itemSell = StringToItemParser::getInstance()->parse(VanillaItems::WHEAT()->getVanillaName())->setCount($slider);
                    if ($slider > $item) {
                        $player->sendMessage(Utils::getPrefix() . "§cVous n'avez pas §e" . $slider . "§c blé à vendre.");
                        return;
                    }
                    EconomyManager::getInstance()->addMoney($player->getName(), $slider);
                    $player->getInventory()->removeItem($itemSell);
                    $player->sendMessage(Utils::getPrefix() . "§aVous avez vendu §e" . $slider . "§a blé pour §e$slider .");
                }
            });
        $player->sendForm($form);
    }

    public function sendSeedForm(Player $player): void {
        $form = new CustomMenuForm(
            '§c- §fGraine §c-',
            [
                new Label('label','-------------------------------' . PHP_EOL . '§7» §rVous avez actuellement §e' . EconomyManager::getInstance()->getMoney($player->getName()) . '' . PHP_EOL . '§a10/u' . PHP_EOL . '§c1/u' . PHP_EOL . '§f-------------------------------'),
                new Dropdown('action', '§7» §rAction: ', ['§aAcheter', '§cVendre']),
                new Slider('nombre', '§7» §rNombre: ', 0, 64)
            ],
            function (CustomPlayer $player, CustomFormResponse $response) : void {
                $action = ['§aAcheter', '$cVendre'];
                $slider = (int)$response->getFloat('nombre');

                if ($action[$response->getInt('action')] === '§aAcheter') {
                    $item = StringToItemParser::getInstance()->parse(VanillaItems::WHEAT_SEEDS()->getVanillaName())->setCount($slider);
                    if (!$player->getInventory()->canAddItem($item)) {
                        $player->sendMessage(Utils::getPrefix() . "§cVotre inventaire est complet vous ne pouvez donc pas acheter §e" . $slider . "§c graine.");
                        return;
                    }

                    if (EconomyManager::getInstance()->getMoney($player->getName()) < $slider * 10) {
                        $player->sendMessage(Utils::getPrefix() . "§cVous n'avez pas assez d'argent pour acheter §e" . $slider . "§c graine.");
                        return;
                    }

                    EconomyManager::getInstance()->delMoney($player->getName(), $slider * 10);
                    $player->getInventory()->addItem($item);
                    $player->sendMessage(Utils::getPrefix() . "§aVous avez acheté §e" . $slider . '§a graine pour un total de §e' . $slider * 10 . '§a.');
                } else {
                    $item = $this->countItem($player, VanillaItems::WHEAT_SEEDS()->getTypeId());
                    $itemSell = StringToItemParser::getInstance()->parse(VanillaItems::WHEAT_SEEDS()->getVanillaName())->setCount($slider);
                    if ($slider > $item) {
                        $player->sendMessage(Utils::getPrefix() . "§cVous n'avez pas §e" . $slider . "§c graine à vendre.");
                        return;
                    }
                    EconomyManager::getInstance()->addMoney($player->getName(), $slider);
                    $player->getInventory()->removeItem($itemSell);
                    $player->sendMessage(Utils::getPrefix() . "§aVous avez vendu §e" . $slider . "§a graine pour §e$slider .");
                }
            });
        $player->sendForm($form);
    }

    private function sendOreForm(Player $player): void {
        $form = new MenuForm(
            '§c- §fMinerais §c-',
            '§7» §rChoisissez une catégorie.',
            [
                new BaseOption('§7» §rSilex', new ButtonIcon(ButtonIcon::IMAGE_TYPE_PATH, 'textures/items/flint')),
                new BaseOption('§7» §rCharbon', new ButtonIcon(ButtonIcon::IMAGE_TYPE_PATH, 'textures/items/coal')),
                new BaseOption('§7» §rFer', new ButtonIcon(ButtonIcon::IMAGE_TYPE_PATH, 'textures/items/iron_ingot')),
                new BaseOption('§7» §rOr', new ButtonIcon(ButtonIcon::IMAGE_TYPE_PATH, 'textures/items/gold_ingot')),
                new BaseOption('§7» §rRedstone', new ButtonIcon(ButtonIcon::IMAGE_TYPE_PATH, 'textures/items/redstone_dust')),
                new BaseOption('§7» §rDiamant', new ButtonIcon(ButtonIcon::IMAGE_TYPE_PATH, 'textures/items/diamond')),
                new BaseOption('§7» §rLapis Lazuli', new ButtonIcon(ButtonIcon::IMAGE_TYPE_PATH, 'textures/blocks/lapis_block')),
                new BaseOption('§7» §rÉmeraude', new ButtonIcon(ButtonIcon::IMAGE_TYPE_PATH, 'textures/items/emerald')),
                new BaseOption('§7» §cRetour', new ButtonIcon(ButtonIcon::IMAGE_TYPE_PATH, 'textures/blocks/barrier'))
            ],
            function (CustomPlayer $player, int $data) : void {
                if ($data === 0)
                    $this->sendFlintForm($player);
                elseif($data === 1)
                    $this->sendCoalForm($player);
                elseif($data === 2)
                    $this->sendIronForm($player);
                elseif($data === 3)
                    $this->sendGoldForm($player);
                elseif($data === 4)
                    $this->sendRedstoneForm($player);
                elseif($data === 5)
                    $this->sendDiamondForm($player);
                elseif($data === 6)
                    $this->sendLapisForm($player);
                elseif($data === 7)
                    $this->sendEmeraldForm($player);
                else
                    $this->sendShopForm($player);
            }
        );
        $player->sendForm($form);
    }

    private function sendFlintForm(Player $player): void  {
        $form = new CustomMenuForm(
            '§c- §fSilex §c-',
            [
                new Label('label','-------------------------------' . PHP_EOL . '§7» §rVous avez actuellement §e' . EconomyManager::getInstance()->getMoney($player->getName()) . '' . PHP_EOL . '§a15/u' . PHP_EOL . '§c1/u' . PHP_EOL . '§f-------------------------------'),
                new Dropdown('action', '§7» §rAction: ', ['§aAcheter', '§cVendre']),
                new Slider('nombre', '§7» §rNombre: ', 0, 64)
            ],
            function (CustomPlayer $player, CustomFormResponse $response) : void {
                $action = ['§aAcheter', '$cVendre'];
                $slider = (int)$response->getFloat('nombre');

                if ($action[$response->getInt('action')] === '§aAcheter') {
                    $item = StringToItemParser::getInstance()->parse(VanillaItems::FLINT()->getVanillaName())->setCount($slider);
                    if (!$player->getInventory()->canAddItem($item)) {
                        $player->sendMessage(Utils::getPrefix() . "§cVotre inventaire est complet vous ne pouvez donc pas acheter §e" . $slider . "§c silex.");
                        return;
                    }

                    if (EconomyManager::getInstance()->getMoney($player->getName()) < $slider * 15) {
                        $player->sendMessage(Utils::getPrefix() . "§cVous n'avez pas assez d'argent pour acheter §e" . $slider . "§c silex.");
                        return;
                    }

                    EconomyManager::getInstance()->delMoney($player->getName(), $slider * 15);
                    $player->getInventory()->addItem($item);
                    $player->sendMessage(Utils::getPrefix() . "§aVous avez acheté §e" . $slider . '§a silex pour un total de §e' . $slider * 15 . '§a.');
                } else {
                    $item = $this->countItem($player, VanillaItems::FLINT()->getTypeId());
                    $itemSell = StringToItemParser::getInstance()->parse(VanillaItems::FLINT()->getVanillaName())->setCount($slider);
                    if ($slider > $item) {
                        $player->sendMessage(Utils::getPrefix() . "§cVous n'avez pas §e" . $slider . "§c silex à vendre.");
                        return;
                    }
                    EconomyManager::getInstance()->addMoney($player->getName(), $slider);
                    $player->getInventory()->removeItem($itemSell);
                    $player->sendMessage(Utils::getPrefix() . "§aVous avez vendu §e" . $slider . "§a silex pour §e$slider .");
                }
            });
        $player->sendForm($form);
    }

    private function sendCoalForm(Player $player): void  {
        $form = new CustomMenuForm(
            '§c- §fCharbon §c-',
            [
                new Label('label','-------------------------------' . PHP_EOL . '§7» §rVous avez actuellement §e' . EconomyManager::getInstance()->getMoney($player->getName()) . '' . PHP_EOL . '§a200/u' . PHP_EOL . '§c1/u' . PHP_EOL . '§f-------------------------------'),
                new Dropdown('action', '§7» §rAction: ', ['§aAcheter', '§cVendre']),
                new Slider('nombre', '§7» §rNombre: ', 0, 64)
            ],
            function (CustomPlayer $player, CustomFormResponse $response) : void {
                $action = ['§aAcheter', '$cVendre'];
                $slider = (int)$response->getFloat('nombre');

                if ($action[$response->getInt('action')] === '§aAcheter') {
                    $item = StringToItemParser::getInstance()->parse(VanillaItems::COAL()->getVanillaName())->setCount($slider);
                    if (!$player->getInventory()->canAddItem($item)) {
                        $player->sendMessage(Utils::getPrefix() . "§cVotre inventaire est complet vous ne pouvez donc pas acheter §e" . $slider . "§c charbon.");
                        return;
                    }

                    if (EconomyManager::getInstance()->getMoney($player->getName()) < $slider * 200) {
                        $player->sendMessage(Utils::getPrefix() . "§cVous n'avez pas assez d'argent pour acheter §e" . $slider . "§c charbon.");
                        return;
                    }

                    EconomyManager::getInstance()->delMoney($player->getName(), $slider * 200);
                    $player->getInventory()->addItem($item);
                    $player->sendMessage(Utils::getPrefix() . "§aVous avez acheté §e" . $slider . '§a charbon pour un total de §e' . $slider * 200 . '§a.');
                } else {
                    $item = $this->countItem($player, VanillaItems::COAL()->getTypeId());
                    $itemSell = StringToItemParser::getInstance()->parse(VanillaItems::COAL()->getVanillaName())->setCount($slider);
                    if ($slider > $item) {
                        $player->sendMessage(Utils::getPrefix() . "§cVous n'avez pas §e" . $slider . "§c charbon à vendre.");
                        return;
                    }
                    EconomyManager::getInstance()->addMoney($player->getName(), $slider);
                    $player->getInventory()->removeItem($itemSell);
                    $player->sendMessage(Utils::getPrefix() . "§aVous avez vendu §e" . $slider . "§a charbon pour §e$slider .");
                }
            });
        $player->sendForm($form);
    }

    private function sendIronForm(Player $player): void {
        $form = new CustomMenuForm(
            '§c- §fFer §c-',
            [
                new Label('label','-------------------------------' . PHP_EOL . '§7» §rVous avez actuellement §e' . EconomyManager::getInstance()->getMoney($player->getName()) . '' . PHP_EOL . '§a200/u' . PHP_EOL . '§c10/u' . PHP_EOL . '§f-------------------------------'),
                new Dropdown('action', '§7» §rAction: ', ['§aAcheter', '§cVendre']),
                new Slider('nombre', '§7» §rNombre: ', 0, 64)
            ],
            function (CustomPlayer $player, CustomFormResponse $response) : void {
                $action = ['§aAcheter', '$cVendre'];
                $slider = (int)$response->getFloat('nombre');

                if ($action[$response->getInt('action')] === '§aAcheter') {
                    $item = StringToItemParser::getInstance()->parse(VanillaItems::IRON_INGOT()->getVanillaName())->setCount($slider);
                    if (!$player->getInventory()->canAddItem($item)) {
                        $player->sendMessage(Utils::getPrefix() . "§cVotre inventaire est complet vous ne pouvez donc pas acheter §e" . $slider . "§c lingot(s) de fer.");
                        return;
                    }

                    if (EconomyManager::getInstance()->getMoney($player->getName()) < $slider * 200) {
                        $player->sendMessage(Utils::getPrefix() . "§cVous n'avez pas assez d'argent pour acheter §e" . $slider . "§c lingot(s) de fer.");
                        return;
                    }

                    EconomyManager::getInstance()->delMoney($player->getName(), $slider * 200);
                    $player->getInventory()->addItem($item);
                    $player->sendMessage(Utils::getPrefix() . "§aVous avez acheté §e" . $slider . '§a lingot(s) de fer pour un total de §e' . $slider * 200 . '§a.');
                } else {
                    $item = $this->countItem($player, VanillaItems::IRON_INGOT()->getTypeId());
                    $itemSell = StringToItemParser::getInstance()->parse(VanillaItems::IRON_INGOT()->getVanillaName())->setCount($slider);
                    if ($slider > $item) {
                        $player->sendMessage(Utils::getPrefix() . "§cVous n'avez pas §e" . $slider . "§c lingot(s) de fer à vendre.");
                        return;
                    }
                    EconomyManager::getInstance()->addMoney($player->getName(), $slider);
                    $player->getInventory()->removeItem($itemSell);
                    $player->sendMessage(Utils::getPrefix() . "§aVous avez vendu §e" . $slider . "§a lingot(s) de fer pour §e$slider .");
                }
            });
        $player->sendForm($form);
    }

    private function sendGoldForm(Player $player): void {
        $form = new CustomMenuForm(
            '§c- §fOr §c-',
            [
                new Label('label','-------------------------------' . PHP_EOL . '§7» §rVous avez actuellement §e' . EconomyManager::getInstance()->getMoney($player->getName()) . '' . PHP_EOL . '§a500/u' . PHP_EOL . '§c30/u' . PHP_EOL . '§f-------------------------------'),
                new Dropdown('action', '§7» §rAction: ', ['§aAcheter', '§cVendre']),
                new Slider('nombre', '§7» §rNombre: ', 0, 64)
            ],
            function (CustomPlayer $player, CustomFormResponse $response) : void {
                $action = ['§aAcheter', '$cVendre'];
                $slider = (int)$response->getFloat('nombre');

                if ($action[$response->getInt('action')] === '§aAcheter') {
                    $item = StringToItemParser::getInstance()->parse(VanillaItems::GOLD_INGOT()->getVanillaName())->setCount($slider);
                    if (!$player->getInventory()->canAddItem($item)) {
                        $player->sendMessage(Utils::getPrefix() . "§cVotre inventaire est complet vous ne pouvez donc pas acheter §e" . $slider . "§c lingot(s) d'or.");
                        return;
                    }

                    if (EconomyManager::getInstance()->getMoney($player->getName()) < $slider * 500) {
                        $player->sendMessage(Utils::getPrefix() . "§cVous n'avez pas assez d'argent pour acheter §e" . $slider . "§c lingot(s) d'or.");
                        return;
                    }

                    EconomyManager::getInstance()->delMoney($player->getName(), $slider * 500);
                    $player->getInventory()->addItem($item);
                    $player->sendMessage(Utils::getPrefix() . "§aVous avez acheté §e" . $slider . '§a lingot(s) d\'or pour un total de §e' . $slider * 500 . '§a.');
                } else {
                    $item = $this->countItem($player, VanillaItems::GOLD_INGOT()->getTypeId());
                    $itemSell = StringToItemParser::getInstance()->parse(VanillaItems::GOLD_INGOT()->getVanillaName())->setCount($slider);
                    if ($slider > $item) {
                        $player->sendMessage(Utils::getPrefix() . "§cVous n'avez pas §e" . $slider . "§c lingot(s) d'or à vendre.");
                        return;
                    }
                    EconomyManager::getInstance()->addMoney($player->getName(), $slider);
                    $player->getInventory()->removeItem($itemSell);
                    $player->sendMessage(Utils::getPrefix() . "§aVous avez vendu §e" . $slider . "§a lingot(s) d'or pour §e$slider .");
                }
            });
        $player->sendForm($form);
    }

    private function sendRedstoneForm(Player $player): void {
        $form = new CustomMenuForm(
            '§c- §fRedstone §c-',
            [
                new Label('label','-------------------------------' . PHP_EOL . '§7» §rVous avez actuellement §e' . EconomyManager::getInstance()->getMoney($player->getName()) . '' . PHP_EOL . '§a50/u' . PHP_EOL . '§c2/u' . PHP_EOL . '§f-------------------------------'),
                new Dropdown('action', '§7» §rAction: ', ['§aAcheter', '§cVendre']),
                new Slider('nombre', '§7» §rNombre: ', 0, 64)
            ],
            function (CustomPlayer $player, CustomFormResponse $response) : void {
                $action = ['§aAcheter', '$cVendre'];
                $slider = (int)$response->getFloat('nombre');

                if ($action[$response->getInt('action')] === '§aAcheter') {
                    $item = StringToItemParser::getInstance()->parse(VanillaItems::REDSTONE_DUST()->getVanillaName())->setCount($slider);
                    if (!$player->getInventory()->canAddItem($item)) {
                        $player->sendMessage(Utils::getPrefix() . "§cVotre inventaire est complet vous ne pouvez donc pas acheter §e" . $slider . "§c poudre(s) de redstone.");
                        return;
                    }

                    if (EconomyManager::getInstance()->getMoney($player->getName()) < $slider * 50) {
                        $player->sendMessage(Utils::getPrefix() . "§cVous n'avez pas assez d'argent pour acheter §e" . $slider . "§c poudre(s) de redstone.");
                        return;
                    }

                    EconomyManager::getInstance()->delMoney($player->getName(), $slider * 50);
                    $player->getInventory()->addItem($item);
                    $player->sendMessage(Utils::getPrefix() . "§aVous avez acheté §e" . $slider . '§a poudre(s) de redstone pour un total de §e' . $slider * 50 . '§a.');
                } else {
                    $item = $this->countItem($player, VanillaItems::REDSTONE_DUST()->getTypeId());
                    $itemSell = StringToItemParser::getInstance()->parse(VanillaItems::REDSTONE_DUST()->getVanillaName())->setCount($slider);
                    if ($slider > $item) {
                        $player->sendMessage(Utils::getPrefix() . "§cVous n'avez pas §e" . $slider . "§c poudre(s) de redstone à vendre.");
                        return;
                    }
                    EconomyManager::getInstance()->addMoney($player->getName(), $slider);
                    $player->getInventory()->removeItem($itemSell);
                    $player->sendMessage(Utils::getPrefix() . "§aVous avez vendu §e" . $slider . "§a poudre(s) de redstone pour §e$slider .");
                }
            });
        $player->sendForm($form);
    }

    private function sendDiamondForm(Player $player): void {
        $form = new CustomMenuForm(
            '§c- §fDiamant §c-',
            [
                new Label('label','-------------------------------' . PHP_EOL . '§7» §rVous avez actuellement §e' . EconomyManager::getInstance()->getMoney($player->getName()) . '' . PHP_EOL . '§a500/u' . PHP_EOL . '§c30/u' . PHP_EOL . '§f-------------------------------'),
                new Dropdown('action', '§7» §rAction: ', ['§aAcheter', '§cVendre']),
                new Slider('nombre', '§7» §rNombre: ', 0, 64)
            ],
            function (CustomPlayer $player, CustomFormResponse $response) : void {
                $action = ['§aAcheter', '$cVendre'];
                $slider = (int)$response->getFloat('nombre');

                if ($action[$response->getInt('action')] === '§aAcheter') {
                    $item = StringToItemParser::getInstance()->parse(VanillaItems::DIAMOND()->getVanillaName())->setCount($slider);
                    if (!$player->getInventory()->canAddItem($item)) {
                        $player->sendMessage(Utils::getPrefix() . "§cVotre inventaire est complet vous ne pouvez donc pas acheter §e" . $slider . "§c diamant(s).");
                        return;
                    }

                    if (EconomyManager::getInstance()->getMoney($player->getName()) < $slider * 500) {
                        $player->sendMessage(Utils::getPrefix() . "§cVous n'avez pas assez d'argent pour acheter §e" . $slider . "§c diamant(s).");
                        return;
                    }

                    EconomyManager::getInstance()->delMoney($player->getName(), $slider * 500);
                    $player->getInventory()->addItem($item);
                    $player->sendMessage(Utils::getPrefix() . "§aVous avez acheté §e" . $slider . '§a diamant(s) pour un total de §e' . $slider * 500 . '§a.');
                } else {
                    $item = $this->countItem($player, VanillaItems::DIAMOND()->getTypeId());
                    $itemSell = StringToItemParser::getInstance()->parse(VanillaItems::DIAMOND()->getVanillaName())->setCount($slider);
                    if ($slider > $item) {
                        $player->sendMessage(Utils::getPrefix() . "§cVous n'avez pas §e" . $slider . "§c diamant(s) à vendre.");
                        return;
                    }
                    EconomyManager::getInstance()->addMoney($player->getName(), $slider);
                    $player->getInventory()->removeItem($itemSell);
                    $player->sendMessage(Utils::getPrefix() . "§aVous avez vendu §e" . $slider . "§a diamant(s) pour §e$slider .");
                }
            });
        $player->sendForm($form);
    }


    private function sendLapisForm(Player $player): void {
        $form = new CustomMenuForm(
            '§c- §fLapis Lazuli §c-',
            [
                new Label('label','-------------------------------' . PHP_EOL . '§7» §rVous avez actuellement §e' . EconomyManager::getInstance()->getMoney($player->getName()) . '' . PHP_EOL . '§a300/u' . PHP_EOL . '§c3/u' . PHP_EOL . '§f-------------------------------'),
                new Dropdown('action', '§7» §rAction: ', ['§aAcheter', '§cVendre']),
                new Slider('nombre', '§7» §rNombre: ', 0, 64)
            ],
            function (CustomPlayer $player, CustomFormResponse $response) : void {
                $action = ['§aAcheter', '$cVendre'];
                $slider = (int)$response->getFloat('nombre');

                if ($action[$response->getInt('action')] === '§aAcheter') {
                    $item = StringToItemParser::getInstance()->parse(VanillaItems::LAPIS_LAZULI()->getVanillaName())->setCount($slider);
                    if (!$player->getInventory()->canAddItem($item)) {
                        $player->sendMessage(Utils::getPrefix() . "§cVotre inventaire est complet vous ne pouvez donc pas acheter §e" . $slider . "§c lapis lazuli.");
                        return;
                    }

                    if (EconomyManager::getInstance()->getMoney($player->getName()) < $slider * 300) {
                        $player->sendMessage(Utils::getPrefix() . "§cVous n'avez pas assez d'argent pour acheter §e" . $slider . "§c lapis lazuli.");
                        return;
                    }

                    EconomyManager::getInstance()->delMoney($player->getName(), $slider * 300);
                    $player->getInventory()->addItem($item);
                    $player->sendMessage(Utils::getPrefix() . "§aVous avez acheté §e" . $slider . '§a lapis lazuli pour un total de §e' . $slider * 300 . '§a.');
                } else {
                    $item = $this->countItem($player, VanillaItems::LAPIS_LAZULI()->getTypeId());
                    $itemSell = StringToItemParser::getInstance()->parse(VanillaItems::LAPIS_LAZULI()->getVanillaName())->setCount($slider);
                    if ($slider > $item) {
                        $player->sendMessage(Utils::getPrefix() . "§cVous n'avez pas §e" . $slider . "§c lapis lazuli à vendre.");
                        return;
                    }
                    EconomyManager::getInstance()->addMoney($player->getName(), $slider);
                    $player->getInventory()->removeItem($itemSell);
                    $player->sendMessage(Utils::getPrefix() . "§aVous avez vendu §e" . $slider . "§a lapis lazuli pour §e$slider .");
                }
            });
        $player->sendForm($form);    }

    private function sendEmeraldForm(Player $player): void  {
        $form = new CustomMenuForm(
            '§c- §fÉmeraude §c-',
            [
                new Label('label','-------------------------------' . PHP_EOL . '§7» §rVous avez actuellement §e' . EconomyManager::getInstance()->getMoney($player->getName()) . '' . PHP_EOL . '§a1000/u' . PHP_EOL . '§c40/u' . PHP_EOL . '§f-------------------------------'),
                new Dropdown('action', '§7» §rAction: ', ['§aAcheter', '§cVendre']),
                new Slider('nombre', '§7» §rNombre: ', 0, 64)
            ],
            function (CustomPlayer $player, CustomFormResponse $response) : void {
                $action = ['§aAcheter', '$cVendre'];
                $slider = (int)$response->getFloat('nombre');

                if ($action[$response->getInt('action')] === '§aAcheter') {
                    $item = StringToItemParser::getInstance()->parse(VanillaItems::EMERALD()->getVanillaName())->setCount($slider);
                    if (!$player->getInventory()->canAddItem($item)) {
                        $player->sendMessage(Utils::getPrefix() . "§cVotre inventaire est complet vous ne pouvez donc pas acheter §e" . $slider . "§c émeraude(s).");
                        return;
                    }

                    if (EconomyManager::getInstance()->getMoney($player->getName()) < $slider * 1000) {
                        $player->sendMessage(Utils::getPrefix() . "§cVous n'avez pas assez d'argent pour acheter §e" . $slider . "§c émeraude(s).");
                        return;
                    }

                    EconomyManager::getInstance()->delMoney($player->getName(), $slider * 1000);
                    $player->getInventory()->addItem($item);
                    $player->sendMessage(Utils::getPrefix() . "§aVous avez acheté §e" . $slider . '§a émeraude(s) pour un total de §e' . $slider * 1000 . '§a.');
                } else {
                    $item = $this->countItem($player, VanillaItems::EMERALD()->getTypeId());
                    $itemSell = StringToItemParser::getInstance()->parse(VanillaItems::EMERALD()->getVanillaName())->setCount($slider);
                    if ($slider > $item) {
                        $player->sendMessage(Utils::getPrefix() . "§cVous n'avez pas §e" . $slider . "§c émeraude(s) à vendre.");
                        return;
                    }
                    EconomyManager::getInstance()->addMoney($player->getName(), $slider);
                    $player->getInventory()->removeItem($itemSell);
                    $player->sendMessage(Utils::getPrefix() . "§aVous avez vendu §e" . $slider . "§a émeraude(s) pour §e$slider .");
                }
            });
        $player->sendForm($form);
    }

    private function sendLootForm(Player $player): void {
        $form = new MenuForm(
            '§c- Nourriture §c-',
            '§7» §rChoisissez une catégorie de nourriture.',
            [
                new BaseOption('§7» §rOs', new ButtonIcon(ButtonIcon::IMAGE_TYPE_PATH, 'textures/items/bone')),
                new BaseOption('§7» §rBoeuf cru', new ButtonIcon(ButtonIcon::IMAGE_TYPE_PATH, 'textures/items/hoglin_meat_raw')),
                new BaseOption('§7» §rBoeuf cuit', new ButtonIcon(ButtonIcon::IMAGE_TYPE_PATH, 'textures/items/hoglin_meat_cooked')),
                new BaseOption('§7» §rMouton cru', new ButtonIcon(ButtonIcon::IMAGE_TYPE_PATH, 'textures/items/mutton_raw')),
                new BaseOption('§7» §rmMouton cuit', new ButtonIcon(ButtonIcon::IMAGE_TYPE_PATH, 'textures/items/mutton_cooked')),
                new BaseOption('§7» §cRetour', new ButtonIcon(ButtonIcon::IMAGE_TYPE_PATH, 'textures/blocks/barrier'))
            ],
            function (CustomPlayer $player, int $data) : void {
                if ($data === 0)
                    $this->sendBoneForm($player);
                elseif($data === 1)
                    $this->sendRawBeefForm($player);
                elseif($data === 2)
                    $this->sendBeefForm($player);
                elseif($data === 3)
                    $this->sendRawSeepMeatForm($player);
                elseif($data === 4)
                    $this->sendSheepMeatForm($player);
                else
                    $this->sendShopForm($player);
            }
        );
        $player->sendForm($form);
    }

    private function sendBoneForm(Player $player): void  {
        $form = new CustomMenuForm(
            '§c- §fOs §c-',
            [
                new Label('label','-------------------------------' . PHP_EOL . '§7» §rVous avez actuellement §e' . EconomyManager::getInstance()->getMoney($player->getName()) . '' . PHP_EOL . '§a300/u' . PHP_EOL . '§c30/u' . PHP_EOL . '§f-------------------------------'),
                new Dropdown('action', '§7» §rAction: ', ['§aAcheter', '§cVendre']),
                new Slider('nombre', '§7» §rNombre: ', 0, 64)
            ],
            function (CustomPlayer $player, CustomFormResponse $response) : void {
                $action = ['§aAcheter', '$cVendre'];
                $slider = (int)$response->getFloat('nombre');

                if ($action[$response->getInt('action')] === '§aAcheter') {
                    $item = StringToItemParser::getInstance()->parse(VanillaItems::BONE()->getVanillaName())->setCount($slider);
                    if (!$player->getInventory()->canAddItem($item)) {
                        $player->sendMessage(Utils::getPrefix() . "§cVotre inventaire est complet vous ne pouvez donc pas acheter §e" . $slider . "§c os.");
                        return;
                    }

                    if (EconomyManager::getInstance()->getMoney($player->getName()) < $slider * 300) {
                        $player->sendMessage(Utils::getPrefix() . "§cVous n'avez pas assez d'argent pour acheter §e" . $slider . "§c os.");
                        return;
                    }

                    EconomyManager::getInstance()->delMoney($player->getName(), $slider * 300);
                    $player->getInventory()->addItem($item);
                    $player->sendMessage(Utils::getPrefix() . "§aVous avez acheté §e" . $slider . '§a os pour un total de §e' . $slider * 300 . '§a.');
                } else {
                    $item = $this->countItem($player, VanillaItems::BONE()->getTypeId());
                    $itemSell = StringToItemParser::getInstance()->parse(VanillaItems::BONE()->getVanillaName())->setCount($slider);
                    if ($slider > $item) {
                        $player->sendMessage(Utils::getPrefix() . "§cVous n'avez pas §e" . $slider . "§c os à vendre.");
                        return;
                    }
                    EconomyManager::getInstance()->addMoney($player->getName(), $slider);
                    $player->getInventory()->removeItem($itemSell);
                    $player->sendMessage(Utils::getPrefix() . "§aVous avez vendu §e" . $slider . "§a os pour §e$slider .");
                }
            });
        $player->sendForm($form);
    }

    private function sendRawBeefForm(Player $player): void  {
        $form = new CustomMenuForm(
            '§c- §fBoeuf Cru §c-',
            [
                new Label('label','-------------------------------' . PHP_EOL . '§7» §rVous avez actuellement §e' . EconomyManager::getInstance()->getMoney($player->getName()) . '' . PHP_EOL . '§a40/u' . PHP_EOL . '§c1/u' . PHP_EOL . '§f-------------------------------'),
                new Dropdown('action', '§7» §rAction: ', ['§aAcheter', '§cVendre']),
                new Slider('nombre', '§7» §rNombre: ', 0, 64)
            ],
            function (CustomPlayer $player, CustomFormResponse $response) : void {
                $action = ['§aAcheter', '$cVendre'];
                $slider = (int)$response->getFloat('nombre');

                if ($action[$response->getInt('action')] === '§aAcheter') {
                    $item = StringToItemParser::getInstance()->parse(VanillaItems::RAW_BEEF()->getVanillaName())->setCount($slider);
                    if (!$player->getInventory()->canAddItem($item)) {
                        $player->sendMessage(Utils::getPrefix() . "§cVotre inventaire est complet vous ne pouvez donc pas acheter §e" . $slider . "§c boeuf(s) cru.");
                        return;
                    }

                    if (EconomyManager::getInstance()->getMoney($player->getName()) < $slider * 40) {
                        $player->sendMessage(Utils::getPrefix() . "§cVous n'avez pas assez d'argent pour acheter §e" . $slider . "§c boeuf(s) cru.");
                        return;
                    }

                    EconomyManager::getInstance()->delMoney($player->getName(), $slider * 40);
                    $player->getInventory()->addItem($item);
                    $player->sendMessage(Utils::getPrefix() . "§aVous avez acheté §e" . $slider . '§a boeuf(s) cru pour un total de §e' . $slider * 40 . '§a.');
                } else {
                    $item = $this->countItem($player, VanillaItems::RAW_BEEF()->getTypeId());
                    $itemSell = StringToItemParser::getInstance()->parse(VanillaItems::RAW_BEEF()->getVanillaName())->setCount($slider);
                    if ($slider > $item) {
                        $player->sendMessage(Utils::getPrefix() . "§cVous n'avez pas §e" . $slider . "§c boeuf(s) cru à vendre.");
                        return;
                    }
                    EconomyManager::getInstance()->addMoney($player->getName(), $slider);
                    $player->getInventory()->removeItem($itemSell);
                    $player->sendMessage(Utils::getPrefix() . "§aVous avez vendu §e" . $slider . "§a boeuf(s) cru pour §e$slider .");
                }
            });

        $player->sendForm($form);
    }

    private function sendBeefForm(Player $player): void {
        $form = new CustomMenuForm(
            '§c- §fBoeuf Cuit §c-',
            [
                new Label('label','-------------------------------' . PHP_EOL . '§7» §rVous avez actuellement §e' . EconomyManager::getInstance()->getMoney($player->getName()) . '' . PHP_EOL . '§a50/u' . PHP_EOL . '§c1/u' . PHP_EOL . '§f-------------------------------'),
                new Dropdown('action', '§7» §rAction: ', ['§aAcheter', '§cVendre']),
                new Slider('nombre', '§7» §rNombre: ', 0, 64)
            ],
            function (CustomPlayer $player, CustomFormResponse $response) : void {
                $action = ['§aAcheter', '$cVendre'];
                $slider = (int)$response->getFloat('nombre');

                if ($action[$response->getInt('action')] === '§aAcheter') {
                    $item = StringToItemParser::getInstance()->parse(VanillaItems::COOKED_BEEF()->getVanillaName())->setCount($slider);
                    if (!$player->getInventory()->canAddItem($item)) {
                        $player->sendMessage(Utils::getPrefix() . "§cVotre inventaire est complet vous ne pouvez donc pas acheter §e" . $slider . "§c boeuf(s) cuit(s).");
                        return;
                    }

                    if (EconomyManager::getInstance()->getMoney($player->getName()) < $slider * 50) {
                        $player->sendMessage(Utils::getPrefix() . "§cVous n'avez pas assez d'argent pour acheter §e" . $slider . "§c boeuf(s) cuit(s).");
                        return;
                    }

                    EconomyManager::getInstance()->delMoney($player->getName(), $slider * 50);
                    $player->getInventory()->addItem($item);
                    $player->sendMessage(Utils::getPrefix() . "§aVous avez acheté §e" . $slider . '§a boeuf(s) cuit(s) pour un total de §e' . $slider * 50 . '§a.');
                } else {
                    $item = $this->countItem($player, VanillaItems::COOKED_BEEF()->getTypeId());
                    $itemSell = StringToItemParser::getInstance()->parse(VanillaItems::COOKED_BEEF()->getVanillaName())->setCount($slider);
                    if ($slider > $item) {
                        $player->sendMessage(Utils::getPrefix() . "§cVous n'avez pas §e" . $slider . "§c boeuf(s) cuit(s) à vendre.");
                        return;
                    }
                    EconomyManager::getInstance()->addMoney($player->getName(), $slider);
                    $player->getInventory()->removeItem($itemSell);
                    $player->sendMessage(Utils::getPrefix() . "§aVous avez vendu §e" . $slider . "§a boeuf(s) cuit(s) pour §e$slider .");
                }
            });
        $player->sendForm($form);
    }

    private function sendRawSeepMeatForm(Player $player): void  {
        $form = new CustomMenuForm(
            '§c- §fMouton cru §c-',
            [
                new Label('label','-------------------------------' . PHP_EOL . '§7» §rVous avez actuellement §e' . EconomyManager::getInstance()->getMoney($player->getName()) . '' . PHP_EOL . '§a40/u' . PHP_EOL . '§c1/u' . PHP_EOL . '§f-------------------------------'),
                new Dropdown('action', '§7» §rAction: ', ['§aAcheter', '§cVendre']),
                new Slider('nombre', '§7» §rNombre: ', 0, 64)
            ],
            function (CustomPlayer $player, CustomFormResponse $response) : void {
                $action = ['§aAcheter', '$cVendre'];
                $slider = (int)$response->getFloat('nombre');

                if ($action[$response->getInt('action')] === '§aAcheter') {
                    $item = StringToItemParser::getInstance()->parse(VanillaItems::RAW_MUTTON()->getVanillaName())->setCount($slider);
                    if (!$player->getInventory()->canAddItem($item)) {
                        $player->sendMessage(Utils::getPrefix() . "§cVotre inventaire est complet vous ne pouvez donc pas acheter §e" . $slider . "§c mouton(s) cru.");
                        return;
                    }

                    if (EconomyManager::getInstance()->getMoney($player->getName()) < $slider * 40) {
                        $player->sendMessage(Utils::getPrefix() . "§cVous n'avez pas assez d'argent pour acheter §e" . $slider . "§c mouton(s) cru.");
                        return;
                    }

                    EconomyManager::getInstance()->delMoney($player->getName(), $slider * 40);
                    $player->getInventory()->addItem($item);
                    $player->sendMessage(Utils::getPrefix() . "§aVous avez acheté §e" . $slider . '§a mouton(s) cru pour un total de §e' . $slider * 40 . '§a.');
                } else {
                    $item = $this->countItem($player, VanillaItems::RAW_MUTTON()->getTypeId());
                    $itemSell = StringToItemParser::getInstance()->parse(VanillaItems::RAW_MUTTON()->getVanillaName())->setCount($slider);
                    if ($slider > $item) {
                        $player->sendMessage(Utils::getPrefix() . "§cVous n'avez pas §e" . $slider . "§c mouton(s) cru à vendre.");
                        return;
                    }
                    EconomyManager::getInstance()->addMoney($player->getName(), $slider);
                    $player->getInventory()->removeItem($itemSell);
                    $player->sendMessage(Utils::getPrefix() . "§aVous avez vendu §e" . $slider . "§a mouton(s) cru pour §e$slider .");
                }
            });
        $player->sendForm($form);
    }

    private function sendSheepMeatForm(Player $player): void {
        $form = new CustomMenuForm(
            '§c- §fMouton cuit §c-',
            [
                new Label('label','-------------------------------' . PHP_EOL . '§7» §rVous avez actuellement §e' . EconomyManager::getInstance()->getMoney($player->getName()) . '' . PHP_EOL . '§a50/u' . PHP_EOL . '§c1/u' . PHP_EOL . '§f-------------------------------'),
                new Dropdown('action', '§7» §rAction: ', ['§aAcheter', '§cVendre']),
                new Slider('nombre', '§7» §rNombre: ', 0, 64)
            ],
            function (CustomPlayer $player, CustomFormResponse $response) : void {
                $action = ['§aAcheter', '$cVendre'];
                $slider = (int)$response->getFloat('nombre');

                if ($action[$response->getInt('action')] === '§aAcheter') {
                    $item = StringToItemParser::getInstance()->parse(VanillaItems::COOKED_MUTTON()->getVanillaName())->setCount($slider);
                    if (!$player->getInventory()->canAddItem($item)) {
                        $player->sendMessage(Utils::getPrefix() . "§cVotre inventaire est complet vous ne pouvez donc pas acheter §e" . $slider . "§c mouton(s) cuit(s).");
                        return;
                    }

                    if (EconomyManager::getInstance()->getMoney($player->getName()) < $slider * 50) {
                        $player->sendMessage(Utils::getPrefix() . "§cVous n'avez pas assez d'argent pour acheter §e" . $slider . "§c mouton(s) cuit(s).");
                        return;
                    }

                    EconomyManager::getInstance()->delMoney($player->getName(), $slider * 50);
                    $player->getInventory()->addItem($item);
                    $player->sendMessage(Utils::getPrefix() . "§aVous avez acheté §e" . $slider . '§a mouton(s) cuit(s) pour un total de §e' . $slider * 50 . '§a.');
                } else {
                    $item = $this->countItem($player, VanillaItems::COOKED_MUTTON()->getTypeId());
                    $itemSell = StringToItemParser::getInstance()->parse(VanillaItems::COOKED_MUTTON()->getVanillaName())->setCount($slider);
                    if ($slider > $item) {
                        $player->sendMessage(Utils::getPrefix() . "§cVous n'avez pas §e" . $slider . "§c mouton(s) cuit(s) à vendre.");
                        return;
                    }
                    EconomyManager::getInstance()->addMoney($player->getName(), $slider);
                    $player->getInventory()->removeItem($itemSell);
                    $player->sendMessage(Utils::getPrefix() . "§aVous avez vendu §e" . $slider . "§a mouton(s) cuit(s) pour §e$slider .");
                }
            });
        $player->sendForm($form);
    }

    /**
     * @param Player $player
     * @param int $id
     * @return int
     */
    private function countItem(Player $player, int $id): int {
        $count = 0;
        foreach ($player->getInventory()->getContents() as $item){
            if ($item instanceof Item){
                if ($item->getTypeId()() == $id){
                    $count += $item->getCount();
                }
            }
        }
        return $count;
    }
    
}