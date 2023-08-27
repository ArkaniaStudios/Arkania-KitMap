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

namespace arkania\form;

use arkania\form\base\BaseForm;
use arkania\form\base\BaseOption;
use arkania\Main;
use arkania\player\CustomPlayer;
use Closure;
use DaveRandom\CallbackValidator\InvalidCallbackException;
use pocketmine\player\Player;
use pocketmine\utils\Utils;
use TypeError;

class MenuForm extends BaseForm {

	private string $content;

	/** @var BaseOption[]  */
	private array $buttons;

	private Closure $onSubmit;

	private ?Closure $onClose = null;

	/**
	 * @param BaseOption[] $buttons
	 */
	public function __construct(
		string $title,
		string $content,
		array $buttons,
		Closure $onSubmit,
		?Closure $onClose = null
	) {
		parent::__construct($title);
		$this->content = $content;
		$this->buttons = array_values($buttons);
		try {
			Utils::validateCallableSignature(function (CustomPlayer $player, int $data) : void {}, $onSubmit);
		}catch (TypeError|InvalidCallbackException $e) {
			Main::getInstance()->getLogger()->info('Invalid callback passed to MenuForm: ' . $e->getMessage());
		}
		$this->onSubmit = $onSubmit;
		if ($onClose !== null) {
			try {
				Utils::validateCallableSignature(function (CustomPlayer $player) : void {}, $onClose);
			}catch (TypeError|InvalidCallbackException $e) {
				Main::getInstance()->getLogger()->info('Invalid callback passed to MenuForm: ' . $e->getMessage());
			}
			$this->onClose = $onClose;
		}
	}

	public function getOption(int $position) : ?BaseOption {
		return $this->buttons[$position] ?? null;
	}

	public function getType() : string {
		return 'form';
	}

	/**
	 * @return (string|mixed)[]
	 */
	public function serializeFormData() : array {
		return [
			'content' => $this->content,
			'buttons' => $this->buttons
		];
	}

	public function handleResponse(Player $player, $data) : void {
		if ($data === null) {
			if ($this->onClose !== null) {
				($this->onClose)($player);
			}
			return;
		}
		($this->onSubmit)($player, $data);
	}
}
