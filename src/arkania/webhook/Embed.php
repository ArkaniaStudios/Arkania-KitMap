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

use arkania\utils\Utils;

class Embed {
	/** @var (string|mixed)[]|int[] */
	private array $data = [];

	public function asArray() : array {
		return $this->data;
	}

	public function setTitle(string $title) : self {
		$this->data['title'] = Utils::removeColor($title);

		return $this;
	}

	public function setContent(string $description) : self {
		$this->data['description'] = Utils::removeColor($description);

		return $this;
	}

	public function setColor(int $color) : self {
		$this->data['color'] = $color;

		return $this;
	}

	public function setTimestamp(string $timestamp) : self {
		$this->data['timestamp'] = $timestamp;

		return $this;
	}

	public function setFooter(string $footer, string $url = 'https://cdn.discordapp.com/attachments/1058448782481690714/1129935696497475737/logo_rond-modified.png') : self {
		$this->data['footer'] = [
			'text' => Utils::removeColor($footer),
			'icon_url' => $url
		];

		return $this;
	}

	public function setImage(string $url = 'https://cdn.discordapp.com/attachments/1058448782481690714/1129935696497475737/logo_rond-modified.png') : self {
		$this->data['thumbnail'] = [
			'url' => $url
		];

		return $this;
	}

	public function addField(string $name, string $value, bool $inline = false) : self {
		$this->data['fields'][] = [
			'name' => $name,
			'value' => $value,
			'inline' => $inline
		];

		return $this;
	}
}
