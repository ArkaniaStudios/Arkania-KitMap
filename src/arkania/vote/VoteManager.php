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

namespace arkania\vote;

use arkania\path\Path;
use arkania\path\PathTypeIds;
use JsonException;
use pocketmine\item\VanillaItems;
use pocketmine\utils\SingletonTrait;

class VoteManager {
	use SingletonTrait;

	private VoteRewards $rewards;

	private VotePartyReward $partyrewards;

	public function getVoteParty() : int {
		return Path::config('config', PathTypeIds::YAML())->get('vote-party');
	}

	/**
	 * @throws JsonException
	 */
	public function addVoteParty() : void {
		$config = Path::config('config', PathTypeIds::YAML());
		$config->set('vote-party', $config->get('vote-party') + 1);
		$config->save();
	}

	/**
	 * @throws JsonException
	 */
	private function reset() : void {
		$config = Path::config('config', PathTypeIds::YAML());
		$config->set('vote-party', 0);
		$config->save();
	}

	/**
	 * @throws JsonException
	 */
	public function check() : void {
		if ($this->getVoteParty() >= Path::config('config', PathTypeIds::YAML())->get('vote-party-require')) {
			$this->reset();
			$this->votePartyRewards()->giveReward();
		}
	}

	private function votePartyRewards() : VotePartyReward {
		if (isset($this->partyrewards)) {
			return $this->partyrewards;
		}
		return $this->partyrewards = VotePartyReward::create()
			->addItem(VanillaItems::CHEMICAL_BENZENE())
			->addCommand(1, 'bc important test')
			->addXp(1, true)
			->addMoney(10);
	}

	public function rewards() : VoteRewards {
		if (isset($this->rewards)) {
			return $this->rewards;
		}
		return $this->rewards = VoteRewards::create()
			->addItem(VanillaItems::CHEMICAL_BENZENE())
			->addCommand(1, 'bc important test')
			->addXp(1, true)
			->addMoney(10);
	}

}
