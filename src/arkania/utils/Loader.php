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

namespace arkania\utils;

use arkania\commands\player\CraftCommand;
use arkania\commands\player\EnderChestCommand;
use arkania\commands\player\LanguageCommand;
use arkania\commands\player\MoneyCommand;
use arkania\commands\player\RenameCommand;
use arkania\commands\player\RepairCommand;
use arkania\commands\player\ReplyCommand;
use arkania\commands\player\TellCommand;
use arkania\commands\player\TpaacceptCommand;
use arkania\commands\player\TpaCommand;
use arkania\commands\player\TpaDenyCommand;
use arkania\commands\player\TpaHereCommand;
use arkania\commands\player\VoteCommand;
use arkania\commands\staff\AddMoneyCommand;
use arkania\commands\staff\AddRankCommand;
use arkania\commands\staff\BroadCastCommand;
use arkania\commands\staff\DelMoneyCommand;
use arkania\commands\staff\DelRankCommand;
use arkania\commands\staff\DeopCommand;
use arkania\commands\staff\LogsCommand;
use arkania\commands\staff\MaintenanceCommand;
use arkania\commands\staff\OpCommand;
use arkania\commands\staff\RedemCommand;
use arkania\commands\staff\SelfTpCommand;
use arkania\commands\staff\SetMoneyZoneCommand;
use arkania\commands\staff\SetRankCommand;
use arkania\commands\staff\TeleportCommand;
use arkania\events\DataPacketSendEvent;
use arkania\events\inventory\InventoryCloseEvent;
use arkania\events\PacketHooker;
use arkania\events\player\PlayerChatEvent;
use arkania\events\player\PlayerCreateAccountEvent;
use arkania\events\player\PlayerCreationEvent;
use arkania\events\player\PlayerJoinEvent;
use arkania\events\player\PlayerLoginEvent;
use arkania\game\listeners\PlayerMoveListener;
use arkania\Main;

class Loader {
	public function __construct(
		private readonly Main $main
	) {
		$this->initEvents();
		$this->initCommands();
	}

	private function initEvents() : void {
		$events = [
			new PlayerCreationEvent(),
            new PlayerLoginEvent(),
			new PlayerJoinEvent(),
            new PlayerChatEvent(),
			new PlayerCreateAccountEvent(),
            new PlayerMoveListener(),
			new InventoryCloseEvent(),
            new DataPacketSendEvent(),
		];

		foreach ($events as $event) {
			$this->main->getServer()->getPluginManager()->registerEvents($event, $this->main);
		}
	}

	private function initCommands() : void {

        $commandMap = $this->main->getServer()->getCommandMap();

        $unloadCommand = [
            'say',
            'op',
            'deop',
            'whitelist',
            'tp',
            'tell'
        ];

        foreach ($unloadCommand as $command) {
            $commandMap->unregister($commandMap->getCommand($command));
        }

		$commands = [
			new LanguageCommand(),
			new RedemCommand(),
			new EnderChestCommand(),
			new CraftCommand(),
            new BroadCastCommand(),
            new MaintenanceCommand(),
            new LogsCommand(),
            new OpCommand(),
            new DeopCommand(),
            new VoteCommand(),
            new AddMoneyCommand(),
            new DelMoneyCommand(),
            new MoneyCommand(),
            new AddRankCommand(),
            new DelRankCommand(),
            new SetRankCommand(),
            new TeleportCommand(),
            new SelfTpCommand(),
            new TpaacceptCommand(),
            new TpaCommand(),
            new TpaDenyCommand(),
            new TpaHereCommand(),
            new SetMoneyZoneCommand(),
            new RenameCommand(),
            new RepairCommand(),
            new TellCommand(),
            new ReplyCommand(),
        ];

		foreach ($commands as $command) {
			$commandMap->register($command->getName(), $command);
		}
	}
}
