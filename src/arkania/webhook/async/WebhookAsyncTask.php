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

namespace arkania\webhook\async;

use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;

class WebhookAsyncTask extends AsyncTask {
	public function __construct(
		private readonly string $url,
		private readonly string $content
	) {
	}

	public function onRun() : void {
		$url = $this->url;
		$ch = \curl_init($url);
		if ($ch === false) {
			return;
		}
		\curl_setopt_array($ch, [
			\CURLOPT_POST => true,
			\CURLOPT_POSTFIELDS => $this->content,
			\CURLOPT_HTTPHEADER => [
				"Content-Type: application/json"
			],
			\CURLOPT_RETURNTRANSFER => true,
			\CURLOPT_SSL_VERIFYPEER => false,
			\CURLOPT_SSL_VERIFYHOST => false
		]);
		$response = \curl_exec($ch);
		\curl_close($ch);
		$this->setResult($response);
	}

	public function onCompletion() : void {
		if ($this->getResult() !== '') {
			Server::getInstance()->getLogger()->error('Erreur lors de l\'envoi du webhook: ' . $this->getResult());
		}
	}
}
