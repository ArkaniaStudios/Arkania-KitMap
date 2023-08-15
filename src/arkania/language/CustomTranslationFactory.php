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

namespace arkania\language;


use pocketmine\lang\Translatable;

/**
 * This class contains constants for all the translations known to PocketMine-MP as per the used version of pmmp/Language.
 * This class is generated automatically, do NOT modify it by hand.
 */
final class CustomTranslationFactory{
	public static function arkania_broadcast_description() : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_BROADCAST_DESCRIPTION, []);
	}

	public static function arkania_chat_type_admin(Translatable|string $param0, Translatable|string $param1) : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_CHAT_TYPE_ADMIN, [
			0 => $param0,
			1 => $param1,
		]);
	}

	public static function arkania_command_not_permission(Translatable|string $param0) : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_COMMAND_NOT_PERMISSION, [
			0 => $param0,
		]);
	}

	public static function arkania_craft_can_not() : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_CRAFT_CAN_NOT, []);
	}

	public static function arkania_craft_description() : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_CRAFT_DESCRIPTION, []);
	}

	public static function arkania_deleteuser_description() : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_DELETEUSER_DESCRIPTION, []);
	}

	public static function arkania_deop_already(Translatable|string $param0) : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_DEOP_ALREADY, [
			0 => $param0,
		]);
	}

	public static function arkania_deop_description() : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_DEOP_DESCRIPTION, []);
	}

	public static function arkania_deop_success(Translatable|string $param0) : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_DEOP_SUCCESS, [
			0 => $param0,
		]);
	}

	public static function arkania_economy_addmoney_description() : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_ECONOMY_ADDMONEY_DESCRIPTION, []);
	}

	public static function arkania_economy_addmoney_success(Translatable|string $param0, Translatable|string $param1) : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_ECONOMY_ADDMONEY_SUCCESS, [
			0 => $param0,
			1 => $param1,
		]);
	}

	public static function arkania_economy_delmoney_description() : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_ECONOMY_DELMONEY_DESCRIPTION, []);
	}

	public static function arkania_economy_delmoney_success(Translatable|string $param0, Translatable|string $param1) : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_ECONOMY_DELMONEY_SUCCESS, [
			0 => $param0,
			1 => $param1,
		]);
	}

	public static function arkania_economy_invalid_amount(Translatable|string $param0) : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_ECONOMY_INVALID_AMOUNT, [
			0 => $param0,
		]);
	}

	public static function arkania_economy_money_description() : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_ECONOMY_MONEY_DESCRIPTION, []);
	}

	public static function arkania_economy_money_not_found(Translatable|string $param0) : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_ECONOMY_MONEY_NOT_FOUND, [
			0 => $param0,
		]);
	}

	public static function arkania_economy_money_self(Translatable|string $param0) : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_ECONOMY_MONEY_SELF, [
			0 => $param0,
		]);
	}

	public static function arkania_economy_money_target(Translatable|string $param0, Translatable|string $param1) : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_ECONOMY_MONEY_TARGET, [
			0 => $param0,
			1 => $param1,
		]);
	}

	public static function arkania_enderchest_can_not() : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_ENDERCHEST_CAN_NOT, []);
	}

	public static function arkania_enderchest_description() : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_ENDERCHEST_DESCRIPTION, []);
	}

	public static function arkania_language_changed(Translatable|string $param0) : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_LANGUAGE_CHANGED, [
			0 => $param0,
		]);
	}

	public static function arkania_language_description() : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_LANGUAGE_DESCRIPTION, []);
	}

	public static function arkania_language_name() : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_LANGUAGE_NAME, []);
	}

	public static function arkania_language_not_found(Translatable|string $param0) : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_LANGUAGE_NOT_FOUND, [
			0 => $param0,
		]);
	}

	public static function arkania_language_usage() : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_LANGUAGE_USAGE, []);
	}

	public static function arkania_logs_already(Translatable|string $param0) : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_LOGS_ALREADY, [
			0 => $param0,
		]);
	}

	public static function arkania_logs_description() : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_LOGS_DESCRIPTION, []);
	}

	public static function arkania_logs_off() : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_LOGS_OFF, []);
	}

	public static function arkania_logs_on() : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_LOGS_ON, []);
	}

	public static function arkania_logs_usage() : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_LOGS_USAGE, []);
	}

	public static function arkania_maintenance_already(Translatable|string $param0) : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_MAINTENANCE_ALREADY, [
			0 => $param0,
		]);
	}

	public static function arkania_maintenance_description() : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_MAINTENANCE_DESCRIPTION, []);
	}

	public static function arkania_maintenance_disabled(Translatable|string $param0) : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_MAINTENANCE_DISABLED, [
			0 => $param0,
		]);
	}

	public static function arkania_maintenance_enabled(Translatable|string $param0) : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_MAINTENANCE_ENABLED, [
			0 => $param0,
		]);
	}

	public static function arkania_maintenance_kick(Translatable|string $param0) : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_MAINTENANCE_KICK, [
			0 => $param0,
		]);
	}

	public static function arkania_maintenance_kick2(Translatable|string $param0, Translatable|string $param1) : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_MAINTENANCE_KICK2, [
			0 => $param0,
			1 => $param1,
		]);
	}

	public static function arkania_maintenance_time(Translatable|string $param0) : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_MAINTENANCE_TIME, [
			0 => $param0,
		]);
	}

	public static function arkania_moneyzone_description() : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_MONEYZONE_DESCRIPTION, []);
	}

	public static function arkania_op_already(Translatable|string $param0) : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_OP_ALREADY, [
			0 => $param0,
		]);
	}

	public static function arkania_op_description() : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_OP_DESCRIPTION, []);
	}

	public static function arkania_op_success(Translatable|string $param0) : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_OP_SUCCESS, [
			0 => $param0,
		]);
	}

	public static function arkania_player_no_exist(Translatable|string $param0) : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_PLAYER_NO_EXIST, [
			0 => $param0,
		]);
	}

	public static function arkania_player_not_found(Translatable|string $param0) : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_PLAYER_NOT_FOUND, [
			0 => $param0,
		]);
	}

	public static function arkania_ranks_addrank_description() : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_RANKS_ADDRANK_DESCRIPTION, []);
	}

	public static function arkania_ranks_addrank_exist(Translatable|string $param0) : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_RANKS_ADDRANK_EXIST, [
			0 => $param0,
		]);
	}

	public static function arkania_ranks_create_failure(Translatable|string $param0) : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_RANKS_CREATE_FAILURE, [
			0 => $param0,
		]);
	}

	public static function arkania_ranks_create_success(Translatable|string $param0) : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_RANKS_CREATE_SUCCESS, [
			0 => $param0,
		]);
	}

	public static function arkania_ranks_delete_description() : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_RANKS_DELETE_DESCRIPTION, []);
	}

	public static function arkania_ranks_delete_failure(Translatable|string $param0) : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_RANKS_DELETE_FAILURE, [
			0 => $param0,
		]);
	}

	public static function arkania_ranks_delete_success(Translatable|string $param0) : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_RANKS_DELETE_SUCCESS, [
			0 => $param0,
		]);
	}

	public static function arkania_ranks_no_exist(Translatable|string $param0) : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_RANKS_NO_EXIST, [
			0 => $param0,
		]);
	}

	public static function arkania_ranks_setrank_description() : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_RANKS_SETRANK_DESCRIPTION, []);
	}

	public static function arkania_ranks_setrank_success(Translatable|string $param0, Translatable|string $param1) : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_RANKS_SETRANK_SUCCESS, [
			0 => $param0,
			1 => $param1,
		]);
	}

	public static function arkania_redem_description() : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_REDEM_DESCRIPTION, []);
	}

	public static function arkania_redem_success() : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_REDEM_SUCCESS, []);
	}

	public static function arkania_redem_timing(Translatable|string $param0) : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_REDEM_TIMING, [
			0 => $param0,
		]);
	}

	public static function arkania_scoreboard_already(Translatable|string $param0) : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_SCOREBOARD_ALREADY, [
			0 => $param0,
		]);
	}

	public static function arkania_scoreboard_description() : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_SCOREBOARD_DESCRIPTION, []);
	}

	public static function arkania_scoreboard_off() : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_SCOREBOARD_OFF, []);
	}

	public static function arkania_scoreboard_on() : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_SCOREBOARD_ON, []);
	}

	public static function arkania_teleport_description() : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_TELEPORT_DESCRIPTION, []);
	}

	public static function arkania_teleport_success(Translatable|string $param0) : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_TELEPORT_SUCCESS, [
			0 => $param0,
		]);
	}

	public static function arkania_teleport_success_self(Translatable|string $param0) : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_TELEPORT_SUCCESS_SELF, [
			0 => $param0,
		]);
	}

	public static function arkania_teleportation_accepted_self(Translatable|string $param0) : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_TELEPORTATION_ACCEPTED_SELF, [
			0 => $param0,
		]);
	}

	public static function arkania_teleportation_accepted_target(Translatable|string $param0) : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_TELEPORTATION_ACCEPTED_TARGET, [
			0 => $param0,
		]);
	}

	public static function arkania_teleportation_denied_self(Translatable|string $param0) : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_TELEPORTATION_DENIED_SELF, [
			0 => $param0,
		]);
	}

	public static function arkania_teleportation_denied_target(Translatable|string $param0) : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_TELEPORTATION_DENIED_TARGET, [
			0 => $param0,
		]);
	}

	public static function arkania_teleportation_expired() : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_TELEPORTATION_EXPIRED, []);
	}

	public static function arkania_teleportation_no_request() : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_TELEPORTATION_NO_REQUEST, []);
	}

	public static function arkania_teleportation_not_found(Translatable|string $param0) : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_TELEPORTATION_NOT_FOUND, [
			0 => $param0,
		]);
	}

	public static function arkania_teleportation_receive(Translatable|string $param0) : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_TELEPORTATION_RECEIVE, [
			0 => $param0,
		]);
	}

	public static function arkania_teleportation_send(Translatable|string $param0) : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_TELEPORTATION_SEND, [
			0 => $param0,
		]);
	}

	public static function arkania_teleportation_tpa_description() : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_TELEPORTATION_TPA_DESCRIPTION, []);
	}

	public static function arkania_teleportation_tpaaccept_description() : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_TELEPORTATION_TPAACCEPT_DESCRIPTION, []);
	}

	public static function arkania_teleportation_tpadeny_description() : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_TELEPORTATION_TPADENY_DESCRIPTION, []);
	}

	public static function arkania_usage_message(Translatable|string $param0) : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_USAGE_MESSAGE, [
			0 => $param0,
		]);
	}

	public static function arkania_vote_already_claimed() : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_VOTE_ALREADY_CLAIMED, []);
	}

	public static function arkania_vote_claimed() : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_VOTE_CLAIMED, []);
	}

	public static function arkania_vote_description() : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_VOTE_DESCRIPTION, []);
	}

	public static function arkania_vote_must_vote() : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_VOTE_MUST_VOTE, []);
	}

}
