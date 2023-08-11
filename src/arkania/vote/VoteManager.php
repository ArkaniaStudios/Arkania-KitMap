<?php
declare(strict_types=1);

namespace arkania\vote;

use arkania\Main;
use arkania\path\Path;
use arkania\path\PathTypeIds;
use arkania\player\CustomPlayer;
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
     * @return void
     * @throws JsonException
     */
    public function addVoteParty() : void {
        $config = Path::config('config', PathTypeIds::YAML());
        $config->set('vote-party', $config->get('vote-party') + 1);
        $config->save();
    }

    /**
     * @return void
     * @throws JsonException
     */
    private function reset() : void {
        $config = Path::config('config', PathTypeIds::YAML());
        $config->set('vote-party', 0);
        $config->save();
    }

    /**
     * @return void
     * @throws JsonException
     */
    public function check(): void {
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

    public function rewards(): VoteRewards {
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