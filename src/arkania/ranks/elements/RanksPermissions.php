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

namespace arkania\ranks\elements;

use arkania\Main;
use arkania\path\Path;
use arkania\path\PathTypeIds;
use arkania\player\CustomPlayer;
use arkania\ranks\PermissionNullableException;
use arkania\ranks\RankNotExistException;
use JsonException;
use pocketmine\utils\Config;

class RanksPermissions {

	/** @var string|string[]|null  */
	private null|string|array $permissions;

	private Config $config;

	/**
	 * @param string|array|null $permissions
	 * @throws RankNotExistException
	 */
	public function __construct(
		string $rankName,
		null|string|array $permissions = null
	) {

		$this->permissions = $permissions;
		if (!file_exists(Main::getInstance()->getDataFolder() . 'ranks/')){
			mkdir(Main::getInstance()->getDataFolder() . 'ranks/');
		}

		if (!file_exists(Main::getInstance()->getDataFolder() . 'ranks/' . $rankName)){
			throw new RankNotExistException('Le rank ' . $rankName . ' n\'existe pas');
		}

		if (!file_exists(Main::getInstance()->getDataFolder() . 'players/')){
			mkdir(Main::getInstance()->getDataFolder() . 'players/');
		}

		$this->config = Path::config('ranks/' . $rankName, PathTypeIds::JSON());

		if (empty($this->config->getAll())){
			throw new RankNotExistException('Le rank ' . $rankName . ' n\'existe pas');
		}
	}

	/**
	 * @throws RankNotExistException
	 */
	public static function create(string $rankName) : self {
		return new self($rankName);
	}

	/**
	 * @param string[]|string|null $permission
	 * @throws PermissionNullableException|JsonException
	 * @return $this
	 */
	public function addPermission(array|string|null $permission = null) : self {
		if(is_null($permission) && is_null($this->permissions)){
			throw new PermissionNullableException('La permission ne peut pas être null');
		}
		if (is_null($this->permissions)) {
			$permissions = $permission;
		}else{
			if (!$permission !== null){
				$permissions = $permission;
			}else{
				$permissions = $this->permissions;
			}
		}

		if(is_array($permissions)){
			$this->config->set('permissions', array_merge($this->config->get('permissions'), $permissions));
			$this->config->save();
		}elseif(is_string($permissions)){
			$this->config->set('permissions', array_merge($this->config->get('permissions'), [$permissions]));
			$this->config->save();
		}else{
			throw new PermissionNullableException('Le type de la permission doit être un tableau ou une chaîne de caractères. Le type actuel est ' . gettype($permissions));
		}
		return $this;
	}

	/**
	 * @param string[]|string|null $permission
	 * @throws PermissionNullableException|JsonException
	 * @return $this
	 */
	public function removePermission(string|array|null $permission = null) : self {
		if(is_null($permission) && is_null($this->permissions)){
			throw new PermissionNullableException('La permission ne peut pas être null');
		}
		if (is_null($this->permissions)) {
			$permissions = $permission;
		}else{
			if (!$permission !== null){
				$permissions = $permission;
			}else{
				$permissions = $this->permissions;
			}
		}

		if(is_array($permissions)){
			$this->config->set('permissions', array_diff($this->config->get('permissions'), $permissions));
			$this->config->save();
		}elseif(is_string($permissions)){
			$this->config->set('permissions', array_diff($this->config->get('permissions'), [$permissions]));
			$this->config->save();
		}else{
			throw new PermissionNullableException('Le type de la permission doit être un tableau ou une chaîne de caractères. Le type actuel est ' . gettype($permissions));
		}
		return $this;
	}

	/**
	 * @param string[]|string|null $permission
	 * @throws PermissionNullableException|JsonException|RankNotExistException
	 * @return $this
	 */
	public function addPermissionToPlayer(string $playerName, string|array|null $permission = null) : self {
		if(is_null($permission) && is_null($this->permissions)){
			throw new PermissionNullableException('La permission ne peut pas être null');
		}
		if (is_null($this->permissions)) {
			$permissions = $permission;
		}else{
			if (!$permission !== null){
				$permissions = $permission;
			}else{
				$permissions = $this->permissions;
			}
		}

		$config = Path::config('players/' . $playerName, PathTypeIds::JSON());

		if (empty($config->getAll())){
			throw new RankNotExistException('Le joueur ' . $playerName . ' n\'existe pas');
		}

		return $this->addPermissionsToConfig($permissions, $config);
	}

	/**
	 * @param string[]|string|null $permission
	 * @throws PermissionNullableException|JsonException|RankNotExistException
	 * @return $this
	 */
	public function removePermissionToPlayer(CustomPlayer $player, string|array|null $permission = null) : self {
		if(is_null($permission) && is_null($this->permissions)){
			throw new PermissionNullableException('La permission ne peut pas être null');
		}
		if (is_null($this->permissions)) {
			$permissions = $permission;
		}else{
			if (!$permission !== null){
				$permissions = $permission;
			}else{
				$permissions = $this->permissions;
			}
		}

		$config = Path::config('players/' . $player->getName(), PathTypeIds::JSON());

		if (empty($config->getAll())){
			throw new RankNotExistException('Le joueur ' . $player->getName() . ' n\'existe pas');
		}

		return $this->addPermissionsToConfig($permissions, $config);
	}

	/**
	 * @throws JsonException
	 * @throws PermissionNullableException
	 * @return $this
	 */
	private function addPermissionsToConfig(array|string|null $permissions, Config $config) : RanksPermissions {
		if (is_array($permissions)) {
			$config->set('permissions', array_diff($config->get('permissions'), $permissions));
			$config->save();
		} elseif (is_string($permissions)) {
			$config->set('permissions', array_diff($config->get('permissions'), [$permissions]));
			$config->save();
		} else {
			throw new PermissionNullableException('Le type de la permission doit être un tableau ou une chaîne de caractères. Le type actuel est ' . gettype($permissions));
		}
		return $this;
	}

	public function getPermissions(CustomPlayer $player) : array {
		$config = Path::config('players/' . $player->getName(), PathTypeIds::JSON());
		$playerPermissions = [];
		$rankPermissions = [];
		if ($config->exists('permissions')) {
			$playerPermissions = $config->get('permissions');
		}
		if ($this->config->exists('permissions')) {
			$rankPermissions = $this->config->get('permissions');
		}
		return array_merge($playerPermissions, $rankPermissions);
	}
}
