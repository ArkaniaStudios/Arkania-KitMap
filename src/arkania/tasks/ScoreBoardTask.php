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

namespace arkania\tasks;

use arkania\commands\player\ScoreBoardCommand;
use arkania\economy\EconomyManager;
use arkania\Main;
use arkania\player\CustomPlayer;
use arkania\vote\VoteManager;
use JsonException;
use pocketmine\network\mcpe\protocol\SetDisplayObjectivePacket;
use pocketmine\network\mcpe\protocol\SetScorePacket;
use pocketmine\network\mcpe\protocol\types\ScorePacketEntry;
use pocketmine\scheduler\Task;
use pocketmine\Server;
use Symfony\Component\Filesystem\Path;

class ScoreBoardTask extends Task {
	private array $lines;

	public function __construct(private readonly CustomPlayer $player) {
	}

	/**
	 * @throws JsonException
	 */
	public function onRun() : void {
		if (!$this->player->isConnected() || !isset(ScoreBoardCommand::$scoreboard[$this->player->getName()])) {
			$this->getHandler()->cancel();

			return;
		}

		$pk = SetDisplayObjectivePacket::create(
			SetDisplayObjectivePacket::DISPLAY_SLOT_SIDEBAR,
			$this->player->getName(),
			' ',
			"dummy",
			SetDisplayObjectivePacket::SORT_ORDER_ASCENDING
		);
		$this->player->getNetworkSession()->sendDataPacket($pk);
		$plugin = Main::getInstance();
		$lines = $plugin->getConfig()->getNested("scoreboard.lines");
		$ranks = Path::join(Main::getInstance()->getDataFolder(), 'ranks', $this->player->getRank() . '.json');
		$ranks = json_decode(file_get_contents($ranks), true, 512, JSON_THROW_ON_ERROR);
		$lines = str_replace([
			'{NAME}',
			'{RANK}',
			'{MONEY}',
			'{FACTION}',
            '{VOTE}',
            '{PLAYERS}'
		], [
			$this->player->getName(),
			$ranks['color'] . $this->player->getRank(),
			EconomyManager::getInstance()->getMoney($this->player->getName()),
			$this->player->getFaction()?->getName() ?? 'Aucune',
            VoteManager::getInstance()->getVoteParty(),
            count(Server::getInstance()->getOnlinePlayers())
		], $lines);
		foreach ($lines as $number => $text) {
			$this->addLine($number, $text);
		}
	}

	public function addLine(int $id, string $line) : void {
		$entry = new ScorePacketEntry();
		$entry->type = ScorePacketEntry::TYPE_FAKE_PLAYER;
		if (isset($this->lines[$id])) {
			$pkt = new SetScorePacket();
			$pkt->entries[] = $this->lines[$id];
			$pkt->type = SetScorePacket::TYPE_REMOVE;
			$this->player->getNetworkSession()->sendDataPacket($pkt);
			unset($this->lines[$id]);
		}
		$entry->score = $id;
		$entry->scoreboardId = $id;
		$entry->actorUniqueId = $this->player->getId();
		$entry->objectiveName = $this->player->getName();
		$entry->customName = $line;
		$this->lines[$id] = $entry;
		$pkt = new SetScorePacket();
		$pkt->entries[] = $entry;
		$pkt->type = SetScorePacket::TYPE_CHANGE;
		$this->player->getNetworkSession()->sendDataPacket($pkt);
	}
}
