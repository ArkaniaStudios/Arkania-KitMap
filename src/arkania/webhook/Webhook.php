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

namespace arkania\webhook;

use arkania\Main;
use arkania\webhook\async\WebhookAsyncTask;

class Webhook {
	private ?string $url;

	public function __construct(
		string $url
	) {
		try {
			if (\preg_match('/^https:\/\/discord\.com\/api\/webhooks\/\d+\/[a-zA-Z0-9_\-]+$/i', $url)) {
				$this->url = $url;
			} else {
				throw new InvalidWebhookException('Invalid Discord webhook URL');
			}
		} catch (InvalidWebhookException $e) {
			Main::getInstance()->getLogger()->warning($e->getMessage() . ' (' . $url . ')');
			$this->url = null;
		}
	}

	public function getUrl() : string {
		return $this->url;
	}

	public function send(Message $message) : void {
		if ($this->url !== null) {
			Main::getInstance()->getServer()->getAsyncPool()->submitTask(new WebhookAsyncTask($this->url, \json_encode($message->jsonSerialize())));
		}
	}
}
