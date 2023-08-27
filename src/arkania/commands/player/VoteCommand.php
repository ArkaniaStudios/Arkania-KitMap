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

namespace arkania\commands\player;

use arkania\api\commands\BaseCommand;
use arkania\language\CustomTranslationFactory;
use arkania\Main;
use arkania\path\Path;
use arkania\path\PathTypeIds;
use arkania\player\CustomPlayer;
use arkania\vote\async\VoteAsyncTask;
use arkania\vote\VoteManager;
use pocketmine\command\CommandSender;
use pocketmine\utils\Internet;

class VoteCommand extends BaseCommand {

	public const LINK_CLAIM = 'https://minecraftpocket-servers.com/api/?object=votes&element=claim&key={key}&username={player}';
	public const LINK_POST = 'https://minecraftpocket-servers.com/api/?action=post&object=votes&element=claim&key={key}&username={player}';

	public function __construct() {
		parent::__construct(
			'vote',
			CustomTranslationFactory::arkania_vote_description(),
			'/vote'
		);
	}

	protected function registerArguments() : array {
		return [];
	}

	public function onRun(CommandSender $player, array $parameters) : void {
		if (!$player instanceof CustomPlayer) return;

		$this->voteTask($player, self::LINK_CLAIM, function (VoteAsyncTask $task) use ($player) : void {
			switch ($task->getResult()) {
				case '0':
					$player->sendMessage(CustomTranslationFactory::arkania_vote_must_vote());
					break;
				case '1':
					$this->voteTask($player, self::LINK_POST, function (VoteAsyncTask $task) use ($player) : void {
						VoteManager::getInstance()->rewards()->giveRewards($player);
						VoteManager::getInstance()->addVoteParty();
						VoteManager::getInstance()->check();
						$player->sendMessage(CustomTranslationFactory::arkania_vote_claimed());
					});
					break;
				case '2':
					$player->sendMessage(CustomTranslationFactory::arkania_vote_already_claimed());
					break;
			}
		});
	}

	private function voteTask(CustomPlayer $player, string $voteLink, callable $callable) : void {
		$key = Path::config('config', PathTypeIds::YAML())->get('vote-key');
		$playerName = $player->getName();
		Main::getInstance()->getServer()->getAsyncPool()->submitTask(new VoteAsyncTask(
			static function (VoteAsyncTask $task) use ($key, $playerName, $voteLink) : void {
				$get = Internet::getURL((str_replace(['{key}', '{player}'], [$key, $playerName], $voteLink)));
				$task->setResult($get->getBody());
			},
			static function (VoteAsyncTask $task) use ($playerName, $callable) : void {
				if ($player = Main::getInstance()->getServer()->getPlayerExact($playerName)){
					if (!$player instanceof CustomPlayer) return;
					$callable($task);
				}
			}
		));
	}

}
