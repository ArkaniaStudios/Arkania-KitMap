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

namespace arkania\factions;

use arkania\Main;
use arkania\player\CustomPlayer;
use arkania\player\PlayerManager;
use arkania\ranks\RanksManager;
use arkania\utils\trait\Date;
use pocketmine\item\Item;
use pocketmine\lang\Translatable;
use Symfony\Component\Filesystem\Path;

class Faction {

	const MESSAGE_TYPE_NORMAL = 0;
	const MESSAGE_TYPE_TOAST = 1;
	const MESSAGE_TYPE_ALLY = 2;
	const MESSAGE_TYPE_FACTION = 3;

	private string $factionName;
	private string $owner;
	private array $members = [];
	private string $description;
	private string $creationDate;
	private int $power = 0;
	private int $money = 0;
	private array $chestContent = [];
	private array $officiers = [];
	public array $ally = [];

	/**
	 * @throws FactionArgumentInvalidException
	 */
	public function __construct(
		string $factionName,
		?string $owner = null
	) {
		$this->factionName = $factionName;

		if (!file_exists(Main::getInstance()->getDataFolder() . 'factions/' . $factionName . '.json') && $owner !== null) {
			$this->createFaction($owner);
		}elseif(file_exists(Main::getInstance()->getDataFolder() . 'factions/' . $factionName . '.json') && $owner !== null){
			throw new FactionArgumentInvalidException($factionName);
		}
		$this->loadFactionInfos();

	}

	/**
	 * @throws FactionArgumentInvalidException
	 */
	public static function getFaction(string $factionName) : Faction {
		return new self($factionName);
	}

	public function createFaction(string $owner) : void {
		$data = [
			'owner' => $owner,
			'members' => [
				$owner
			],
			'officiers' => [],
			'description' => '',
			'creationDate' => Date::create()->toString(),
			'power' => 0,
			'money' => 0,
			'chestContent' => [],
			'claims' => [],
			'homes' => [],
			'allies' => []
		];
		file_put_contents($this->getConfig(), json_encode($data, JSON_PRETTY_PRINT | JSON_BIGINT_AS_STRING));
	}

	public function getConfig() : string {
		return Path::join(Main::getInstance()->getDataFolder(), 'factions', $this->getName() . '.json');
	}

	public function getFactionData() : array {
		$config = $this->getConfig();
		return json_decode(file_get_contents($config), true);
	}

	public function loadFactionInfos() : void {
		$config = $this->getConfig();
		if (!file_exists($config)) return;
		$data = json_decode(file_get_contents($config), true);
		$this->setOwner($data['owner']);
		$this->setMembers($data['members']);
		$this->setDescription($data['description']);
		$this->setCreationDate($data['creationDate']);
		$this->setPower($data['power']);
		$this->setMoney($data['money']);
		$this->setChestContent($data['chestContent']);
		$this->setOfficiers($data['officiers']);
		ClaimManager::registerFactionClaim($data['claims'], $this->getName());
		$this->ally = $data['allies'];
	}

	public function addValueInData(string $key, $value) : void {
		$data = $this->getFactionData();
		$data[$key] = $value;
		file_put_contents($this->getConfig(), json_encode($data, JSON_PRETTY_PRINT | JSON_BIGINT_AS_STRING));
	}

	/**
	 * @throws FactionArgumentInvalidException
	 * @throws \JsonException
	 */
	public function disband() : void {
		$data = $this->getFactionData();
		$members = $data['members'];

		foreach ($members as $member) {
			$playerData = PlayerManager::getInstance()->getPlayerData($member);
			$playerData->remove('faction');
			$playerData->save();

			$member = PlayerManager::getInstance()->getPlayerInstance($member);
			if ($member !== null){
				RanksManager::getInstance()->updateNametag($member->getRank(), $member);
			}
		}
		$this->getClaimManager()->removeAllFactionClaim();
		unlink($this->getConfig());
	}

	public function getName() : string {
		return $this->factionName;
	}

	public function setOwner(string $owner) : void {
		$this->owner = $owner;
	}

	public function getOwner() : string {
		return $this->owner;
	}

	public function setMembers(array $members) : void {
		$this->members = $members;
	}

	public function getMembers() : array {
		return $this->members;
	}

	public function addMember(string $member) : void {
		$this->members[] = $member;
		$this->addValueInData('members', $this->members);
	}

	public function removeMember(string $member) : void {
		unset($this->members[array_search($member, $this->members)]);
		$this->addValueInData('members', $this->members);
	}

	public function isMember(string $member) : bool {
		return in_array($member, $this->members);
	}

	public function isOwner(string $owner) : bool {
		return $this->owner === $owner;
	}

	public function setDescription(string $description) : void {
		$this->description = $description;
		$this->addValueInData('description', $this->description);
	}

	public function getDescription() : string {
		return $this->description;
	}

	public function getClaimManager() : ClaimManager {
		return new ClaimManager($this);
	}

	public function setCreationDate(string $date) : void {
		$this->creationDate = $date;
	}

	public function getCreationDate() : string {
		return $this->creationDate;
	}

	public function getHomeManager() : HomeManager {
		return new HomeManager($this);
	}

	public function setPower(int $power) : void {
		$this->power = $power;
	}

	public function getPower() : int {
		return $this->power;
	}

	public function addPower(int $power) : void {
		$this->power += $power;
		$this->addValueInData('power', $this->power);
	}

	public function removePower(int $power) : void {
		$this->power -= $power;
		$this->addValueInData('power', $this->power);
	}

	public function setMoney(int $money) : void {
		$this->money = $money;
	}

	public function getMoney() : int {
		return $this->money;
	}

	public function addMoney(int $money) : void {
		$this->money += $money;
		$this->addValueInData('money', $this->money);
	}

	public function removeMoney(int $money) : void {
		$this->money -= $money;
		$this->addValueInData('money', $this->money);
	}

	public function getAllyManager() : AllyManager {
		return new AllyManager($this);
	}

	public function setChestContent(array $content) : void {
		$this->chestContent = $content;
	}

	public function getChestContent() : array {
		return $this->chestContent;
	}

	public function addChestContent(int $slot, Item $item) : void {
		$this->chestContent[] = ['slot' => $slot, $item];
		$this->addValueInData('chestContent', $this->chestContent);
	}

	public function removeChestContent(Item $item) : void {
		unset($this->chestContent[array_search($item, $this->chestContent)]);
		$this->addValueInData('chestContent', $this->chestContent);
	}

	public function setOfficiers(array $officiers) : void {
		$this->officiers = $officiers;
	}

	public function getOfficiers() : array {
		return $this->officiers;
	}

	public function addOfficier(string $officier) : void {
		$this->officiers[] = $officier;
		$this->addValueInData('officiers', $this->officiers);
	}

	public function removeOfficier(string $officier) : void {
		unset($this->officiers[array_search($officier, $this->officiers)]);
		$this->addValueInData('officiers', $this->officiers);
	}

	public function isOfficier(string $officier) : bool {
		return in_array($officier, $this->officiers);
	}

	/**
	 * @throws FactionArgumentInvalidException
	 */
	public function broadCastFactionMessage(int $type, string|Translatable $message) : void {
		foreach ($this->members as $member) {
			$player = PlayerManager::getInstance()->getPlayerInstance($member);
			if ($player !== null) {
				if ($message instanceof Translatable) {
					$message = $player->getLanguage()->translate($message);
				}
				if ($type === 0) {
					$player->sendMessage($message);
				}elseif($type === 1){
					$player->sendToastNotification('FACTION - NOTIFICATION', $message);
				}elseif($type === 2) {
					foreach ($this->getAllyManager()->getAllies() as $factionAlly) {
						$faction = Faction::getFaction($factionAlly);
						foreach ($faction->getMembers() as $memberAlly) {
							$playerAlly = PlayerManager::getInstance()->getPlayerInstance($memberAlly);
							$playerAlly?->sendMessage('§7[§eALLY§7] §f' . $message);
						}
					}
				}elseif($type === 3){
					$player->sendMessage('§7[§eFACTION§7] §f' . $message);
				}
			}
		}
	}

	public function getRankPlayer(CustomPlayer $player) : string {
		if ($this->isOwner($player->getName())) {
			return '**';
		}
		if($this->isOfficier($player->getName())){
			return '*';
		}else{
			return '';
		}
	}

}
