<?php

/*
 *
 *     _      ____    _  __     _      _   _   ___      _                 _   _   _____   _____  __        __   ___    ____    _  __
 *    / \    |  _ \  | |/ /    / \    | \ | | |_ _|    / \               | \ | | | ____| |_   _| \ \      / /  / _ \  |  _ \  | |/ /
 *   / _ \   | |_) | | ' /    / _ \   |  \| |  | |    / _ \     _____    |  \| | |  _|     | |    \ \ /\ / /  | | | | | |_) | | ' /
 *  / ___ \  |  _ <  | . \   / ___ \  | |\  |  | |   / ___ \   |_____|   | |\  | | |___    | |     \ V  V /   | |_| | |  _ <  | . \
 * /_/   \_\ |_| \_\ |_|\_\ /_/   \_\ |_| \_| |___| /_/   \_\            |_| \_| |_____|   |_|      \_/\_/     \___/  |_| \_\ |_|\_\
 *
 * Arkania is a Minecraft Bedrock server created in 2019,
 * we mainly use PocketMine-MP to create content for our server
 * but we use something else like WaterDog PE
 *
 * @author Arkania-Team
 * @link https://arkaniastudios.com
 *
 */

declare(strict_types=1);

namespace arkania\form;

use arkania\area\AreaManager;
use arkania\form\base\BaseOption;
use arkania\form\options\CustomFormResponse;
use arkania\form\options\Dropdown;
use arkania\form\options\Input;
use arkania\form\options\Label;
use arkania\form\options\Toggle;
use arkania\language\CustomTranslationFactory;
use arkania\Main;
use arkania\npc\base\CustomEntity;
use arkania\npc\base\SimpleEntity;
use arkania\npc\type\customs\FloatingText;
use arkania\npc\type\HumanEntity;
use arkania\permissions\Permissions;
use arkania\player\CustomPlayer;
use arkania\report\ReportManager;
use arkania\titles\TitleManager;
use arkania\utils\trait\Date;
use arkania\utils\Utils;
use arkania\webhook\Embed;
use arkania\webhook\Message;
use arkania\webhook\Webhook;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\SingletonTrait;

class FormManager {
	use SingletonTrait;

	public function sendBroadCastForm(CustomPlayer $player) : void {
		$form = new CustomMenuForm(
			'Broadcast',
			[
				new Label('description', 'Permet d\'envoyer un message à tous les joueurs connectés sur le serveur.'),
				new Toggle('type', 'Important | Normal', false),
				new Input('message', 'Message')
			],
			function (CustomPlayer $player, CustomFormResponse $response) : void {
				$type = $response->getBool('type');
				$message = $response->getString('message');
				if (!$type) {
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
			},
			function (CustomPlayer $player) : void {}
		);
		$player->sendForm($form);
	}

	public function sendReportForm(CustomPlayer $player) : void {
		$playerList = [];
		foreach (Server::getInstance()->getOnlinePlayers() as $players) {
			$playerList[] = $players->getName();
		}
		$form = new CustomMenuForm(
			'Report',
			[
				new Label('description', 'Permet de signaler un joueur.'),
				new Dropdown('target', 'Joueur', $playerList),
				new Input('raison', 'Raison')
			],
			function (CustomPlayer $player, CustomFormResponse $response) use ($playerList) : void {

				$target = $response->getInt('target');
				$raison = $response->getString('raison');

				$count = count(ReportManager::getInstance()->getReports($player->getName())) + 1;
				ReportManager::getInstance()->addReport(
					$player->getName(),
					$playerList[$target],
					$raison,
					$player->hasPermission(Permissions::ARKANIA_STAFF),
					(string) $count
				);
				$webhook = new Webhook(Main::ADMIN_URL);
				$message = new Message();
				$embed = new Embed();
				$embed->setTitle('NEW - REPORT')
					->setContent('- Un nouveau report vient d\'avoir lieu.' . "\n" . "\n" . '*Informations:*' . "\n" . '- Joueur: **' . $player->getName() . '**' . "\n" . '- Cible: **' . $playerList[$target] . '**' . "\n" . '- Raison: **' . $raison . '**' . "\n" . '- Nombre de reports: **' . $count . '**' . "\n" . '- Date: **' . Date::create()->toString() . '**')
					->setFooter('Arkania - Report')
					->setcolor(0x6F4392)
					->setImage();
				$message->addEmbed($embed);
				$webhook->send($message);
				$player->sendMessage(CustomTranslationFactory::arkania_report_success($playerList[$target], $raison));
			},
			function (CustomPlayer $player) : void {}
		);
		$player->sendForm($form);
	}

	public function sendReportLogsForm(CustomPlayer $player) : void {
		$buttons = [];
		$button = [];
		foreach (ReportManager::getInstance()->getAllReportFile() as $report) {
			$buttons[] = new BaseOption(str_replace('.json', '', $report));
			$button[] = str_replace('.json', '', $report);
		}
		$form = new MenuForm(
			'Report - Logs',
			'Permet de voir les logs des reports',
			$buttons,
			function (CustomPlayer $player, int $data) use ($button) : void {
				$this->sendSpecificReportForm($player, $button[$data]);
			}
		);
		$player->sendForm($form);
	}

	private function sendSpecificReportForm(CustomPlayer $player, string $target) : void {
		$buttons = [];
		$button = [];
		foreach (ReportManager::getInstance()->getReports($target) as $number => $report) {
			$buttons[] = new BaseOption($report['reported'] . "\n" . $report['date']);
			$button[] = $number;
		}
		$form = new MenuForm(
			'Report - Logs',
			'Permet de voir les logs des reports',
			$buttons,
			function (CustomPlayer $player, int $data) use ($target, $button) : void {
				$this->sendSpecificReportInfoForm($player, $target, $button[$data]);
			}
		);
		$player->sendForm($form);
	}

	private function sendSpecificReportInfoForm(CustomPlayer $player, string $target, int $reportNumber) : void {
		$path = ReportManager::getInstance()->getReports($target)[$reportNumber];
		$form = new MenuForm(
			'Report - Logs',
			'§oInformations concernant le report:' . "\n" . 'Joueur: §e' . $target . "\n" . '§rRaison: §e' . $path['raison'] . "\n" . '§rNombre de reports: §e' . $reportNumber . "\n" . '§rDate: §e' . $path['date'],
			[
				new BaseOption('Supprimer le report'),
				new BaseOption('Retour')
			],
			function (CustomPlayer $player, int $data) use ($target, $reportNumber) : void {
				if ($data === 0) {
					ReportManager::getInstance()->removeReport($target, (string) $reportNumber);
					$player->sendMessage(CustomTranslationFactory::arkania_reportlogs_delete_success());
				}else{
					$this->sendReportLogsForm($player);
				}
			}
		);
		$player->sendForm($form);
	}

	public function sendTitleForm(CustomPlayer $player) : void {
		/** @var BaseOption[] $titles */
		$titles = [];
		/** @var (string|mixed)[] $titlesName */
		$titlesName = [];
		foreach ($player->getTitles() as $title) {
			$titles[] = new BaseOption($title['title']);
			$titlesName[] = $title;
		}
		$form = new MenuForm(
			'Title',
			'Permet de changer son titre',
			$titles,
			function (CustomPlayer $player, int $data) use ($titlesName) : void {
				$player->setTitle(TitleManager::getInstance()->getTitle($titlesName[$data]['title']));
				$player->sendMessage(CustomTranslationFactory::arkania_title_changed($titlesName[$data]['title']));
			},
			function (CustomPlayer $player) : void {}
		);
		$player->sendForm($form);
	}

	public function sendNpcWithItemForm(CustomPlayer $player, SimpleEntity|CustomEntity $entity) : void {
		$form = new MenuForm(
			'§c- §fNPC §c-',
			'',
			[
				new BaseOption('§7» §rChanger de nom'),
				new BaseOption('§7» §rChanger la taille'),
				new BaseOption('§7» §rChanger de Skin'),
				new BaseOption('§7» §rChanger les positions'),
				new BaseOption('§7» §rChanger l\'inventaire'),
				new BaseOption('§7» §rAjouter une commande'),
				new BaseOption('§7» §rSupprimer une commande'),
				new BaseOption('§7» §rSupprimer le NPC')
			],
			function (CustomPlayer $player, int $data) use ($entity) : void {
				switch ($data) {
					case 0:
						$this->sendNpcChangeName($player, $entity);
						break;
					case 1:
						$this->sendNpcChangeSize($player, $entity);
						break;
					case 2:
						if($entity instanceof HumanEntity) {
							$this->sendNpcChangSkinForm($player, $entity);
						} else {
							$player->sendMessage(CustomTranslationFactory::npc_skin_not_human());
						}
						break;
					case 3:
						$this->sendNpcChangePositionForm($player, $entity);
						break;
					case 4:
						if($entity instanceof HumanEntity) {
							$this->sendNpcChangeInventory($player, $entity);
						} else {
							$player->sendMessage(CustomTranslationFactory::npc_inventory_not_human());
						}
						break;
					case 5:
						if(empty($entity->getCommands()) || $entity->getCommands() === null) {
							$player->sendMessage(CustomTranslationFactory::npc_command_empty());
							return;
						} else {
							$this->sendNpcRemoveCommandForm($player, $entity);
						}
						break;
					case 6:
						$entity->flagForDespawn();
						$player->sendMessage(CustomTranslationFactory::npc_delete_success());
						break;
				}
			}
		);
		$player->sendForm($form);
	}

	private function sendNpcChangeName(CustomPlayer $player, SimpleEntity|CustomEntity $entity) : void {
		$form = new CustomMenuForm(
			'§c- §fNPC §c-',
			[
				new Input('name', 'Nom du NPC')
			],
			function (CustomPlayer $player, CustomFormResponse $response) use ($entity) : void {
				$name = $response->getString('name');
				$entity->setNameTag($name);
				$entity->setName($name);
				$player->sendMessage(CustomTranslationFactory::npc_name_changed($name));
			}
		);
		$player->sendForm($form);
	}

	public function sendNpcChangeSize(CustomPlayer $player, SimpleEntity|CustomEntity $entity) : void {
		$form = new CustomMenuForm(
			'§c- §fNPC §c-',
			[
				new Input('size', 'Taille du NPC', '1.0')
			],
			function (CustomPlayer $player, CustomFormResponse $response) use ($entity) : void {
				$size = (float) $response->getString('size');
				if($size < 0.1 || $size > 10) {
					$player->sendMessage(CustomTranslationFactory::npc_size_invalid());
					return;
				}
				$entity->setScale($size);
				$entity->setTaille($size);
				$player->sendMessage(CustomTranslationFactory::npc_size_changed((string) $size));
			}
		);
		$player->sendForm($form);
	}

	private function sendNpcChangSkinForm(CustomPlayer $player, SimpleEntity|CustomEntity $entity) : void {
		$form = new MenuForm(
			'§c- §fNPC §c-',
			'',
			[
				new BaseOption('§7» §rSkin de Personnel'),
				new BaseOption('§7» §rSkin Joueur')
			],
			function (CustomPlayer $player, int $data) use ($entity) : void {
				switch ($data) {
					case 0:
						$entity->setSkin($player->getSkin());
						$entity->sendSkin();
						$player->sendMessage(CustomTranslationFactory::npc_skin_changed());
						break;
					case 1:
						$this->sendNpcChangeSkinByNameForm($player, $entity);
						break;
				}
			}
		);
		$player->sendForm($form);
	}

	private function sendNpcChangeSkinByNameForm(CustomPlayer $player, SimpleEntity|CustomEntity $entity) : void {
		$list = [];
		foreach ($player->getServer()->getOnlinePlayers() as $onlinePlayer) {
			$list[] = $onlinePlayer->getName();
		}
		$form = new CustomMenuForm(
			'§c- §fNPC §c-',
			[
				new Dropdown('name', 'Nom du joueur', $list)
			],
			function (CustomPlayer $player, CustomFormResponse $response) use ($list, $entity) {
				$result = $list[$response->getInt('name')];
				$playerName = $player->getServer()->getPlayerExact($result);
				if($playerName instanceof CustomPlayer) {
					$entity->setSkin($playerName->getSkin());
					$entity->sendSkin();
					$player->sendMessage(CustomTranslationFactory::npc_skin_changed());
				} else {
					$player->sendMessage(CustomTranslationFactory::npc_skin_not_online($playerName->getName()));
				}
			}
		);
		$player->sendForm($form);
	}

	private function sendNpcChangeInventory(CustomPlayer $player, SimpleEntity|CustomEntity $entity) : void {
		$form = new MenuForm(
			'§c- §fNPC §c-',
			'',
			[
				new BaseOption('§7» §rChanger l\'object en main'),
				new BaseOption('§7» §rChanger l\'armure'),
				new BaseOption('§7» §rClear')
			],
			function (CustomPlayer $player, int $data) use ($entity) : void {
				switch ($data) {
					case 0:
						$item = $player->getInventory()->getItemInHand();
						$entity->getInventory()->setItemInHand($item);
						$player->sendMessage(CustomTranslationFactory::npc_inventory_item_changed());
						break;
					case 1:
						$this->sendNpcChangeInventoryArmorForm($player, $entity);
						break;
					case 2:
						$entity->getInventory()->clearAll();
						$entity->getArmorInventory()->clearAll();
						$player->sendMessage(CustomTranslationFactory::npc_inventory_clear());
						break;
				}
			}
		);
		$player->sendForm($form);
	}

	public function sendNpcChangeInventoryArmorForm(CustomPlayer $player, SimpleEntity|CustomEntity $entity) : void {
		$form = new MenuForm(
			'§c- §fNPC §c-',
			'',
			[
				new BaseOption('§7» §rChanger le casque'),
				new BaseOption('§7» §rChanger la plastron'),
				new BaseOption('§7» §rChanger le pantalon'),
				new BaseOption('§7» §rChanger les bottes')
			],
			function (CustomPlayer $player, int $data) use ($entity) : void {
				switch ($data) {
					case 0:
						$item = $player->getArmorInventory()->getHelmet();
						$entity->getArmorInventory()->setHelmet($item);
						$player->sendMessage(CustomTranslationFactory::npc_inventory_armor_helmet_changed());
						break;
					case 1:
						$item = $player->getArmorInventory()->getChestplate();
						$entity->getArmorInventory()->setChestplate($item);
						$player->sendMessage(CustomTranslationFactory::npc_inventory_armor_chestplate_changed());
						break;
					case 2:
						$item = $player->getArmorInventory()->getLeggings();
						$entity->getArmorInventory()->setLeggings($item);
						$player->sendMessage(CustomTranslationFactory::npc_inventory_armor_leggings_changed());
						break;
					case 3:
						$item = $player->getArmorInventory()->getBoots();
						$entity->getArmorInventory()->setBoots($item);
						$player->sendMessage(CustomTranslationFactory::npc_inventory_armor_boots_changed());
						break;
				}
			}
		);
		$player->sendForm($form);
	}

	public function sendNpcChangePositionForm(CustomPlayer $player, SimpleEntity|CustomEntity $entity) : void {
		$form = new CustomMenuForm(
			'§c- §fNPC §c-',
			[
				new Input('yaw', 'YAW du NPC', '0'),
				new Input('pitch', 'PITCH du NPC', '0')
			],
			function (CustomPlayer $player, CustomFormResponse $response) use ($entity) : void {
				$yaw = (float) $response->getString('yaw');
				$pitch = (float) $response->getString('pitch');
				if($yaw < -180 || $yaw > 180) {
					$player->sendMessage(CustomTranslationFactory::npc_yaw_invalid());
					return;
				}
				if($pitch < -90 || $pitch > 90) {
					$player->sendMessage(CustomTranslationFactory::npc_pitch_invalid());
					return;
				}
				$entity->setRotation($yaw, $pitch);
				$entity->setYaw($yaw);
				$entity->setPitch($pitch);
				$player->sendMessage(CustomTranslationFactory::npc_position_changed());
			}
		);
		$player->sendForm($form);
	}

	private function sendNpcAddCommandForm(CustomPlayer $player, SimpleEntity|CustomEntity $entity) : void {
		$form = new CustomMenuForm(
			'§c- §fNPC §c-',
			[
				new Dropdown('type', 'Type d\'exécution', ['Joueur', 'Console']),
				new Input('command', 'Commande à ajouter')
			],
			function (CustomPlayer $player, CustomFormResponse $response) use ($entity) : void {
			  $type = $response->getInt('type');
			  $command = $response->getString('command');
			  $entity->addCommand($type, $command);
			  $player->sendMessage(CustomTranslationFactory::npc_command_added());
		  }
		);
		$player->sendForm($form);
	}

	private function sendNpcRemoveCommandForm(CustomPlayer $player, SimpleEntity|CustomEntity $entity) : void {
		$form = new CustomMenuForm(
			'§c- §fNPC §c-',
			[
				new Dropdown('command', 'Commande à supprimer', $entity->getCommands())
			],
			function (CustomPlayer $player, CustomFormResponse $response) use ($entity) : void {
				$command = $response->getInt('command');
				$commandName = $entity->getCommands()[$command];
				if(!$entity->hasCommands($commandName)) {
					$player->sendMessage(CustomTranslationFactory::npc_command_notfound($commandName));
					return;
				}
				$entity->removeCommand($commandName);
				$player->sendMessage(CustomTranslationFactory::npc_command_removed());
			}
		);
		$player->sendForm($form);
	}

	public function sendNpcCreationForm(CustomPlayer $player) : void {
		$form = new MenuForm(
			'§c- §fNPC §c-',
			'',
			[
				new BaseOption('Simple Entity'),
				new BaseOption('Custom Entity')
			],
			function (CustomPlayer $player, int $data) : void {
				switch ($data) {
					case 0:
						$this->sendCreationChooseSimpleNpcForm($player);
						break;
					case 1:
						$this->sendCreationCustomNpcForm($player);
						break;
				}
			}
		);
		$player->sendForm($form);
	}

	private function sendCreationChooseSimpleNpcForm(Player $player) : void {
		$form = new MenuForm(
			'§c- §fNPC §c-',
			'',
			[
				new BaseOption('Passif'),
				new BaseOption('Agressif'),
				new BaseOption('Neutre')
			],
			function (CustomPlayer $player, int $data) : void {
				switch ($data) {
					case 0:
						$this->sendCreationPassifsNpcForm($player);
						break;
					case 1:
						$this->sendCreationAggresifsNpcForm($player);
						break;
					case 2:
						$this->sendCreationNeutralsNpcForm($player);
						break;
				}
			}
		);
		$player->sendForm($form);
	}

	private function sendCreationPassifsNpcForm(CustomPlayer $player) : void {
		$form = new MenuForm(
			'§c- §fNPC §c-',
			'',
			[
				new BaseOption('AXOLOTL'),
				new BaseOption('CHAT'),
				new BaseOption('POULET'),
				new BaseOption('COD'),
				new BaseOption('VACHE'),
				new BaseOption('ÂNE'),
				new BaseOption('GRENOUILLE'),
				new BaseOption('POULPE L.'),
				new BaseOption('CHEVAL'),
				new BaseOption('MULE'),
				new BaseOption('OCELOT'),
				new BaseOption('PANDA'),
				new BaseOption('PEROQUET'),
				new BaseOption('COCHON'),
				new BaseOption('LAPIN'),
				new BaseOption('SAUMONT'),
				new BaseOption('MOUTON'),
				new BaseOption('POULPE'),
				new BaseOption('STRIDER'),
				new BaseOption('TETARD'),
				new BaseOption('POISSON TROP'),
				new BaseOption('TORTUE'),
				new BaseOption('VILLAGEOIS'),
				new BaseOption('MARCHAND')
			],
			function (CustomPlayer $player, int $data) : void {
				$mobId = match ($data) {
					0 => 'axolotl',
					1 => 'cat',
					2 => 'chicken',
					3 => 'cod',
					4 => 'cow',
					5 => 'donkey',
					6 => 'frog',
					7 => 'glowsquid',
					8 => 'horse',
					9 => 'mule',
					10 => 'ocelot',
					11 => 'panda',
					12 => 'parrot',
					13 => 'pig',
					14 => 'rabbit',
					15 => 'salmon',
					16 => 'sheep',
					17 => 'squid',
					18 => 'strider',
					19 => 'tadpole',
					20 => 'tropicalfish',
					21 => 'turtle',
					22 => 'villager',
					23 => 'wanderingtrader'
				};
				$this->sendCreateNpcByTypeForm($player, $mobId);
			}
		);
		$player->sendForm($form);
	}

	private function sendCreationAggresifsNpcForm(CustomPlayer $player) : void {
		$form = new MenuForm(
			'§c- §fNPC §c-',
			'',
			[
				new BaseOption('BLAZE'),
				new BaseOption('ARAIGNEE C.'),
				new BaseOption('CREEPER'),
				new BaseOption('NOYE'),
				new BaseOption('GARDIEN A.'),
				new BaseOption('DRAGON'),
				new BaseOption('ENDERMAN'),
				new BaseOption('ENDERMITE'),
				new BaseOption('GHAST'),
				new BaseOption('GARDIEN'),
				new BaseOption('HOGLIN'),
				new BaseOption('HUSK'),
				new BaseOption('MAGMA C.'),
				new BaseOption('PHANTOM'),
				new BaseOption('PIGLIN'),
				new BaseOption('PILLARD'),
				new BaseOption('RAVAGEUR'),
				new BaseOption('SHULKER'),
				new BaseOption('SILVER F.'),
				new BaseOption('SQUELETTE'),
				new BaseOption('SLIME'),
				new BaseOption('ARAIGNEE'),
				new BaseOption('ERRANT'),
				new BaseOption('VEX'),
				new BaseOption('VINDICATEUR'),
				new BaseOption('GARDIEN'),
				new BaseOption('SORCIERE'),
				new BaseOption('WITHER'),
				new BaseOption('SQUELETTE W.'),
				new BaseOption('ZOGLIN'),
				new BaseOption('ZOMBIE'),
				new BaseOption('ZOMBIE V.')
			],
			function (CustomPlayer $player, int $data) : void {
				$mobId = match ($data) {
					0 => 'blaze',
					1 => 'cavespider',
					2 => 'creeper',
					3 => 'drowned',
					4 => 'elderguardian',
					5 => 'enderdragon',
					6 => 'enderman',
					7 => 'endermite',
					8 => 'ghast',
					9 => 'guardian',
					10 => 'hoglin',
					11 => 'husk',
					12 => 'magmacube',
					13 => 'phantom',
					14 => 'piglin',
					15 => 'pillager',
					16 => 'ravager',
					17 => 'shulker',
					18 => 'silverfish',
					19 => 'skeleton',
					20 => 'slime',
					21 => 'spider',
					22 => 'stray',
					23 => 'vex',
					24 => 'vindicator',
					25 => 'warden',
					26 => 'witch',
					27 => 'wither',
					28 => 'wither_skeleton',
					29 => 'zoglin',
					30 => 'zombie',
					31 => 'zombie_villager'
				};
				$this->sendCreateNpcByTypeForm($player, $mobId);
			}
		);
		$player->sendForm($form);
	}

	private function sendCreationNeutralsNpcForm(CustomPlayer $player) : void {
		$form = new MenuForm(
			'§c- §fNPC §c-',
			'',
			[
				new BaseOption('CHAUVE.S'),
				new BaseOption('BEE'),
				new BaseOption('DAUPHIN'),
				new BaseOption('FOW'),
				new BaseOption('CHEVRE'),
				new BaseOption('IRON GOLEM'),
				new BaseOption('LAMA'),
				new BaseOption('POLAR BEAR'),
				new BaseOption('SQUELET HORSE'),
				new BaseOption('SNOW GOLEM'),
				new BaseOption('WOLF'),
				new BaseOption('ZOMBIE HORSE'),
				new BaseOption('ZOMBIE PORCIN')
			],
			function (CustomPlayer $player, int $data) : void {
				$mobId = match ($data) {
					0 => 'bat',
					1 => 'bee',
					2 => 'dolphin',
					3 => 'fox',
					4 => 'goat',
					5 => 'irongolem',
					6 => 'llama',
					7 => 'polarbear',
					8 => 'skeletonhorse',
					9 => 'snowgolem',
					10 => 'wolf',
					11 => 'zombiehorse',
					12 => 'zombifiedpiglin'
				};
				$this->sendCreateNpcByTypeForm($player, $mobId);
			}
		);
		$player->sendForm($form);
	}

	private function sendCreateNpcByTypeForm(CustomPlayer $player, string $type) : void {
		$form = new CustomMenuForm(
			'§c- §fNPC §c-',
			[
				new Input('name', 'Nom du NPC', 'aucun')
			],
			function (CustomPlayer $player, CustomFormResponse $response) use ($type) : void {
				$name = $response->getString('name');
				if($type === 'human') {
					$entity = new HumanEntity(
						$player->getLocation(),
						$player->getSkin()
					);
					$entity->setNpc();
					$entity->setNameTagAlwaysVisible();
					if($name === '') {
						$entity->setName($player->getName());
						$entity->setNameTag($player->getName());
					} else {
						$entity->setName($name);
						$entity->setNameTag($name);
					}
					$player->sendMessage(CustomTranslationFactory::npc_create((string) $name, 'humain'));
					$entity->spawnToAll();
					return;
				}
				if($type === 'floatingtext') {
					$entity = new FloatingText($player->getLocation());
					$entity->setName($name);
					$entity->spawnToAll();
					$player->sendMessage(CustomTranslationFactory::npc_create($name, 'floatingtext'));
					return;
				}
				$entity = Utils::getEntityById($player->getLocation(), $type);
				$entity->setNpc();
				$entity->setName($name);
				$entity->setNameTag($name);
				$entity->setNameTagAlwaysVisible();
				$entity->spawnToAll();
				$player->sendMessage(CustomTranslationFactory::npc_create($name, $type));
			}
		);
		$player->sendForm($form);
	}

	private function sendCreationCustomNpcForm(CustomPlayer $player) : void {
		$form = new MenuForm(
			'§c- §fNPC §c-',
			'',
			[
				new BaseOption('Ballon'),
				new BaseOption('Humain'),
				new BaseOption('FloatingText')
			],
			function (CustomPlayer $player, int $data) : void {
				switch ($data) {
					case 0:
						$this->sendCreateNpcByTypeForm($player, 'ballon');
						break;
					case 1:
						$this->sendCreateNpcByTypeForm($player, 'human');
						break;
					case 2:
						$this->sendCreateNpcByTypeForm($player, 'floatingtext');
						break;
				}
			}
		);
		$player->sendForm($form);
	}

    /**
     * @param CustomPlayer $player
     * @return void
     */
    public function sendAreaParamForm(CustomPlayer $player) : void {
        $allArea = [];
        $allAreaName = [];
        foreach (AreaManager::getInstance()->getAllArea() as $area) {
            $allArea[] = new BaseOption($area);
            $allAreaName[] = $area;
        }
        $form = new MenuForm(
            '§9- §fArea §9-',
            'Permet de modifier les paramètres d\'une zone',
            $allArea,
            function (CustomPlayer $player, int $data) use ($allAreaName): void {
                $this->sendModifyParamForm($player, $allAreaName[$data]);
            }
        );
        $player->sendForm($form);
    }

    /**
     * @param CustomPlayer $player
     * @param string $area
     * @return void
     */
    private function sendModifyParamForm(CustomPlayer $player, string $area) : void {
        $form = new CustomMenuForm(
            '§c- §fArea §c-',
            [
                new Toggle('block_place', 'Place des blocs', AreaManager::getInstance()->getArea($area)->canPlace()),
                new Toggle('block_break', 'Casse des blocs', AreaManager::getInstance()->getArea($area)->canBreak()),
                new Toggle('pvp', 'PVP', AreaManager::getInstance()->getArea($area)->canPvp()),
                new Toggle('can_use_command', 'Utilise des commandes', AreaManager::getInstance()->getArea($area)->canUseCommand()),
                new Toggle('drop_item', 'Drop des items', AreaManager::getInstance()->getArea($area)->canDropItem()),
                new Toggle('claim', 'Claim', AreaManager::getInstance()->getArea($area)->canClaim())
            ],
            function (CustomPlayer $player, CustomFormResponse $response) use ($area) : void {
                $blockPlace = $response->getBool('block_place');
                $blockBreak = $response->getBool('block_break');
                $pvp = $response->getBool('pvp');
                $canUseCommand = $response->getBool('can_use_command');
                $dropItem = $response->getBool('drop_item');
                $claim = $response->getBool('claim');
                AreaManager::getInstance()->getArea($area)->setCanPlace($blockPlace);
                AreaManager::getInstance()->getArea($area)->setCanBreak($blockBreak);
                AreaManager::getInstance()->getArea($area)->setCanPvp($pvp);
                AreaManager::getInstance()->getArea($area)->setCanUseCommand($canUseCommand);
                AreaManager::getInstance()->getArea($area)->setCanDropItem($dropItem);
                AreaManager::getInstance()->getArea($area)->setCanClaim($claim);
                $player->sendMessage(CustomTranslationFactory::arkania_area_param_changed($area));
            }
        );
        $player->sendForm($form);
    }

}
