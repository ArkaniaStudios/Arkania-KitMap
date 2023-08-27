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

namespace arkania\factions\commands;

use arkania\api\commands\BaseCommand;
use arkania\factions\commands\subs\AcceptCommand;
use arkania\factions\commands\subs\AdminCommand;
use arkania\factions\commands\subs\AllyBreakCommand;
use arkania\factions\commands\subs\AllyCommand;
use arkania\factions\commands\subs\AllyDenyCommand;
use arkania\factions\commands\subs\AllyOkCommand;
use arkania\factions\commands\subs\BankCommand;
use arkania\factions\commands\subs\ChunkCommand;
use arkania\factions\commands\subs\ClaimCommand;
use arkania\factions\commands\subs\CreateCommand;
use arkania\factions\commands\subs\DemoteCommand;
use arkania\factions\commands\subs\DenyCommand;
use arkania\factions\commands\subs\DescriptionCommand;
use arkania\factions\commands\subs\DisbandCommand;
use arkania\factions\commands\subs\ForceDisbandCommand;
use arkania\factions\commands\subs\ForceUnclaimCommand;
use arkania\factions\commands\subs\HelpCommand;
use arkania\factions\commands\subs\HomeCommand;
use arkania\factions\commands\subs\InfoCommand;
use arkania\factions\commands\subs\InviteCommand;
use arkania\factions\commands\subs\KickCommand;
use arkania\factions\commands\subs\LeaveCommand;
use arkania\factions\commands\subs\PromoteCommand;
use arkania\factions\commands\subs\SetHomeCommand;
use arkania\factions\commands\subs\UnClaimCommand;
use arkania\language\CustomTranslationFactory;
use pocketmine\command\CommandSender;

class FactionCommand extends BaseCommand {

	public function __construct() {
		parent::__construct(
			'faction',
			CustomTranslationFactory::arkania_faction_description(),
			'/faction',
			[
				new CreateCommand(),
				new DisbandCommand(),
				new BankCommand(),
				new PromoteCommand(),
				new DemoteCommand(),
				new ForceDisbandCommand(),
				new AdminCommand(),
				new HelpCommand(),
				new ChunkCommand(),
				new ClaimCommand(),
				new UnClaimCommand(),
				new InfoCommand(),
				new DescriptionCommand(),
				new ForceUnclaimCommand(),
				new InviteCommand(),
				new AcceptCommand(),
				new DenyCommand(),
				new KickCommand(),
				new LeaveCommand(),
				new SetHomeCommand(),
				new HomeCommand(),
				new AllyCommand(),
				new AllyOkCommand(),
				new AllyDenyCommand(),
				new AllyBreakCommand(),
			],
			['f', 'fac']
		);
	}

	protected function registerArguments() : array {
		return [];
	}

	public function onRun(CommandSender $player, array $parameters) : void {
		$player->sendMessage(CustomTranslationFactory::arkania_faction_use_help());
	}

}
