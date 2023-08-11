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

namespace arkania;

use arkania\broadcast\BroadCastManager;
use arkania\economy\EconomyManager;
use arkania\items\CustomItemManager;
use arkania\items\CustomItemTypeNames;
use arkania\items\ExtraCustomItems;
use arkania\language\Language;
use arkania\language\LanguageManager;
use arkania\pack\ResourcesPack;
use arkania\permissions\Permissions;
use arkania\permissions\PermissionsManager;
use arkania\query\Query;
use arkania\server\MaintenanceManager;
use arkania\utils\Loader;
use arkania\utils\Utils;
use JsonException;
use mysqli;
use pocketmine\lang\LanguageNotFoundException;
use pocketmine\plugin\PluginBase;
use pocketmine\scheduler\AsyncTask;
use pocketmine\utils\SingletonTrait;
use ReflectionException;

class Main extends PluginBase {
	use SingletonTrait;

    const DISCORD = 'https://discord.gg/Nsnq23eTrV';
    const ADMIN_URL = 'https://discord.com/api/webhooks/1138171605663617024/zxhP0TDkMCTvoDnlOXez37XGIuGdF-UumWEavOI4MDRWqeLa3lqx2BWH7IRgFkZpPY5k';

	protected function onLoad() : void {
		self::setInstance($this);

		$server = $this->getServer()->getWorldManager();
		/** @phpstan-ignore-next-line */
		foreach (\glob($this->getServer()->getDataPath() . 'worlds/*') as $world) {
			if (\is_dir($world)) {
				$server->loadWorld(\basename($world));
			}
		}

		$this->saveResource('config.yml', true);
		$this->saveResource('languages/fr_FR.lang', true);
		$this->saveResource('languages/en_EN.lang', true);

		$this->getLogger()->info('Chargement du §eArkania-KitMap§f...');
	}

	/**
	 * @throws ReflectionException
	 * @throws JsonException
	 */
	protected function onEnable() : void {
		Utils::setPrefix($this->getConfig()->get('prefix', '[§cKitMap§f] '));

		$asyncPool = $this->getServer()->getAsyncPool();
		$asyncPool->addWorkerStartHook(
			function (int $worker) use ($asyncPool) : void {
				$class = new class() extends AsyncTask {
					public function onRun() : void {
                        Query::$mysqli = new mysqli(
                            'localhost',
                            'root',
                            '',
                            'arkania'
                        );
					}
				};
				$asyncPool->submitTaskToWorker($class, $worker);
			}
		);
        Query::$mysqli = new mysqli(
            'localhost',
            'root',
            '',
            'arkania'
        );

		PermissionsManager::getInstance()->registerPermissionClass(Permissions::class);

        $broadcast = new BroadCastManager($this);
        $broadcast->registerMessage('Nous recrutons du staff, pour postuler rejoignez notre discord : §ehttps://discord.gg/arkania§f.');
        $broadcast->setUp();

		new EconomyManager();
        new MaintenanceManager($this);
		new ResourcesPack($this, 'Arkania-KitMap');
		new LanguageManager();
		new Loader($this);

		$this->getLogger()->info('Activation de §eArkania-KitMap§f...');

        $this->registerItems();
	}

	public function getDefaultLanguage() : ?Language {
		try {
			return LanguageManager::getInstance()->getLanguage('fra');
		} catch (LanguageNotFoundException $e) {
			$this->getLogger()->warning('Impossible de charger la langue par défaut: ' . $e->getMessage() . ', veuillez vérifier que la langue existe bien.');
		}

		return null;
	}

    public function registerItems() : void {
        CustomItemManager::getInstance()->registerCustomItem(CustomItemTypeNames::ITEM_TEST, ExtraCustomItems::ITEM_TEST(), [CustomItemTypeNames::ITEM_TEST, "item_test"]);
    }

}
