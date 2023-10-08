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

namespace arkania\npc\type\customs;

use arkania\game\PiniataManager;
use arkania\language\CustomTranslationFactory;
use arkania\player\CustomPlayer;
use pocketmine\entity\EntitySizeInfo;
use pocketmine\entity\Living;
use pocketmine\entity\Location;
use pocketmine\entity\object\ItemEntity;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\item\VanillaItems;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\types\entity\EntityIds;
use pocketmine\Server;
use pocketmine\world\particle\FlameParticle;
use pocketmine\world\particle\HugeExplodeParticle;

class Piniata extends Living {

	protected function getInitialSizeInfo() : EntitySizeInfo {
		return new EntitySizeInfo(1.87, 0.9);
	}

	public static function getNetworkTypeId() : string {
		return EntityIds::LLAMA;
	}

	public function getName() : string {
		return 'lama';
	}

	public function attack(EntityDamageEvent $source) : void {
		parent::attack($source);
		$calcule = (int) round(10 * $this->getHealth() / $this->getMaxHealth());
		$this->setNameTag('§c§lPiniata' . "\n\n" . str_repeat('§a|', $calcule) . str_repeat('§7|', 10 - $calcule));
	}

	protected function onDeath() : void {
		parent::onDeath();
		Server::getInstance()->broadcastMessage(CustomTranslationFactory::arkania_piniata_end());
        PiniataManager::getInstance()->setStatus(false);

		$world = $this->getWorld();
		$position = $this->getPosition();
		for ($i = 0; $i < 50; $i++) {
			$world->addParticle(
				$position->add(mt_rand(-10, 10), mt_rand(0, 10), mt_rand(-10, 10)),
				new FlameParticle()
			);
		}
		$world->addParticle(
			$position,
			new HugeExplodeParticle()
		);

		$count = 10;
		$startPos = $this->getPosition()->add(0, 5, 0);
		$boundingBox = $this->getBoundingBox();
		foreach ($this->getWorld()->getNearbyEntities($boundingBox->expandedCopy(10, 10, 10), $this) as $entity) {
			if ($entity instanceof CustomPlayer)
				$entity->knockBack($entity->getPosition()->getX() - $position->getX(), $entity->getPosition()->getZ() - $position->getZ(), 1.7);
		}
		for($n = 0, $yaw = 0; $n < $count; $yaw += (M_PI * 2) / $count, $n++) {
			$endPos = new Vector3(-sin($yaw) + $startPos->x, $startPos->getY() + 1, cos($yaw) + $startPos->z);
			$item = new ItemEntity(Location::fromObject($startPos, $this->getWorld()), VanillaItems::STEAK()->setCount(64));
			$item->spawnToAll();
			$item->setMotion($endPos->subtract($startPos->getX(), $startPos->getY(), $startPos->getZ())->multiply(0.4));
		}
	}

}
