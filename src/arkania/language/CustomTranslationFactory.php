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

	public static function arkania_combatlogger_cant_use_command() : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_COMBATLOGGER_CANT_USE_COMMAND, []);
	}

	public static function arkania_combatlogger_you_are_not_in_combat() : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_COMBATLOGGER_YOU_ARE_NOT_IN_COMBAT, []);
	}

	public static function arkania_combatlogger_your_in_combat() : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_COMBATLOGGER_YOUR_IN_COMBAT, []);
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

	public static function arkania_faction_admin_disable() : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_FACTION_ADMIN_DISABLE, []);
	}

	public static function arkania_faction_admin_enable() : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_FACTION_ADMIN_ENABLE, []);
	}

	public static function arkania_faction_ally_already(Translatable|string $param0) : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_FACTION_ALLY_ALREADY, [
			0 => $param0,
		]);
	}

	public static function arkania_faction_ally_break(Translatable|string $param0) : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_FACTION_ALLY_BREAK, [
			0 => $param0,
		]);
	}

	public static function arkania_faction_ally_max() : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_FACTION_ALLY_MAX, []);
	}

	public static function arkania_faction_ally_request(Translatable|string $param0) : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_FACTION_ALLY_REQUEST, [
			0 => $param0,
		]);
	}

	public static function arkania_faction_ally_request_accepted(Translatable|string $param0) : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_FACTION_ALLY_REQUEST_ACCEPTED, [
			0 => $param0,
		]);
	}

	public static function arkania_faction_ally_request_accepted_2(Translatable|string $param0) : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_FACTION_ALLY_REQUEST_ACCEPTED_2, [
			0 => $param0,
		]);
	}

	public static function arkania_faction_ally_request_denied(Translatable|string $param0) : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_FACTION_ALLY_REQUEST_DENIED, [
			0 => $param0,
		]);
	}

	public static function arkania_faction_ally_request_denied_2(Translatable|string $param0) : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_FACTION_ALLY_REQUEST_DENIED_2, [
			0 => $param0,
		]);
	}

	public static function arkania_faction_ally_request_sent(Translatable|string $param0) : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_FACTION_ALLY_REQUEST_SENT, [
			0 => $param0,
		]);
	}

	public static function arkania_faction_already_exist(Translatable|string $param0) : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_FACTION_ALREADY_EXIST, [
			0 => $param0,
		]);
	}

	public static function arkania_faction_already_have() : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_FACTION_ALREADY_HAVE, []);
	}

	public static function arkania_faction_already_in_faction() : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_FACTION_ALREADY_IN_FACTION, []);
	}

	public static function arkania_faction_already_invited(Translatable|string $param0) : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_FACTION_ALREADY_INVITED, [
			0 => $param0,
		]);
	}

	public static function arkania_faction_already_member(Translatable|string $param0) : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_FACTION_ALREADY_MEMBER, [
			0 => $param0,
		]);
	}

	public static function arkania_faction_already_officer(Translatable|string $param0) : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_FACTION_ALREADY_OFFICER, [
			0 => $param0,
		]);
	}

	public static function arkania_faction_bank(Translatable|string $param0) : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_FACTION_BANK, [
			0 => $param0,
		]);
	}

	public static function arkania_faction_bank_add(Translatable|string $param0) : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_FACTION_BANK_ADD, [
			0 => $param0,
		]);
	}

	public static function arkania_faction_bank_no_money() : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_FACTION_BANK_NO_MONEY, []);
	}

	public static function arkania_faction_broadcast_message(Translatable|string $param0, Translatable|string $param1) : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_FACTION_BROADCAST_MESSAGE, [
			0 => $param0,
			1 => $param1,
		]);
	}

	public static function arkania_faction_broadcast_player_join(Translatable|string $param0) : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_FACTION_BROADCAST_PLAYER_JOIN, [
			0 => $param0,
		]);
	}

	public static function arkania_faction_cant_demote_self() : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_FACTION_CANT_DEMOTE_SELF, []);
	}

	public static function arkania_faction_cant_kick_owner() : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_FACTION_CANT_KICK_OWNER, []);
	}

	public static function arkania_faction_cant_kick_same_rank() : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_FACTION_CANT_KICK_SAME_RANK, []);
	}

	public static function arkania_faction_cant_promote_self() : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_FACTION_CANT_PROMOTE_SELF, []);
	}

	public static function arkania_faction_chunk_already_claimed(Translatable|string $param0) : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_FACTION_CHUNK_ALREADY_CLAIMED, [
			0 => $param0,
		]);
	}

	public static function arkania_faction_claim_max() : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_FACTION_CLAIM_MAX, []);
	}

	public static function arkania_faction_claim_success() : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_FACTION_CLAIM_SUCCESS, []);
	}

	public static function arkania_faction_created(Translatable|string $param0) : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_FACTION_CREATED, [
			0 => $param0,
		]);
	}

	public static function arkania_faction_created_broadcast(Translatable|string $param0, Translatable|string $param1) : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_FACTION_CREATED_BROADCAST, [
			0 => $param0,
			1 => $param1,
		]);
	}

	public static function arkania_faction_demoted(Translatable|string $param0) : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_FACTION_DEMOTED, [
			0 => $param0,
		]);
	}

	public static function arkania_faction_demoted_by(Translatable|string $param0) : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_FACTION_DEMOTED_BY, [
			0 => $param0,
		]);
	}

	public static function arkania_faction_description() : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_FACTION_DESCRIPTION, []);
	}

	public static function arkania_faction_description_invalid() : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_FACTION_DESCRIPTION_INVALID, []);
	}

	public static function arkania_faction_disbanded(Translatable|string $param0) : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_FACTION_DISBANDED, [
			0 => $param0,
		]);
	}

	public static function arkania_faction_disbanded_by(Translatable|string $param0, Translatable|string $param1) : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_FACTION_DISBANDED_BY, [
			0 => $param0,
			1 => $param1,
		]);
	}

	public static function arkania_faction_error() : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_FACTION_ERROR, []);
	}

	public static function arkania_faction_force_unclaim_success() : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_FACTION_FORCE_UNCLAIM_SUCCESS, []);
	}

	public static function arkania_faction_help() : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_FACTION_HELP, []);
	}

	public static function arkania_faction_home_not_set() : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_FACTION_HOME_NOT_SET, []);
	}

	public static function arkania_faction_home_set() : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_FACTION_HOME_SET, []);
	}

	public static function arkania_faction_home_teleport() : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_FACTION_HOME_TELEPORT, []);
	}

	public static function arkania_faction_info(Translatable|string $param0, Translatable|string $param1, Translatable|string $param2, Translatable|string $param3, Translatable|string $param4, Translatable|string $param5, Translatable|string $param6, Translatable|string $param7, Translatable|string $param8) : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_FACTION_INFO, [
			0 => $param0,
			1 => $param1,
			2 => $param2,
			3 => $param3,
			4 => $param4,
			5 => $param5,
			6 => $param6,
			7 => $param7,
			8 => $param8,
		]);
	}

	public static function arkania_faction_invitation_accepted(Translatable|string $param0) : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_FACTION_INVITATION_ACCEPTED, [
			0 => $param0,
		]);
	}

	public static function arkania_faction_invitation_denied(Translatable|string $param0) : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_FACTION_INVITATION_DENIED, [
			0 => $param0,
		]);
	}

	public static function arkania_faction_invitation_expired() : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_FACTION_INVITATION_EXPIRED, []);
	}

	public static function arkania_faction_invite_receive(Translatable|string $param0, Translatable|string $param1) : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_FACTION_INVITE_RECEIVE, [
			0 => $param0,
			1 => $param1,
		]);
	}

	public static function arkania_faction_invite_send(Translatable|string $param0) : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_FACTION_INVITE_SEND, [
			0 => $param0,
		]);
	}

	public static function arkania_faction_is_not_officer(Translatable|string $param0) : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_FACTION_IS_NOT_OFFICER, [
			0 => $param0,
		]);
	}

	public static function arkania_faction_kick_by(Translatable|string $param0, Translatable|string $param1) : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_FACTION_KICK_BY, [
			0 => $param0,
			1 => $param1,
		]);
	}

	public static function arkania_faction_kick_success(Translatable|string $param0) : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_FACTION_KICK_SUCCESS, [
			0 => $param0,
		]);
	}

	public static function arkania_faction_leave(Translatable|string $param0) : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_FACTION_LEAVE, [
			0 => $param0,
		]);
	}

	public static function arkania_faction_modify_description(Translatable|string $param0) : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_FACTION_MODIFY_DESCRIPTION, [
			0 => $param0,
		]);
	}

	public static function arkania_faction_name_format_invalid() : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_FACTION_NAME_FORMAT_INVALID, []);
	}

	public static function arkania_faction_name_invalid() : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_FACTION_NAME_INVALID, []);
	}

	public static function arkania_faction_no_ally(Translatable|string $param0) : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_FACTION_NO_ALLY, [
			0 => $param0,
		]);
	}

	public static function arkania_faction_no_ally_request() : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_FACTION_NO_ALLY_REQUEST, []);
	}

	public static function arkania_faction_no_description() : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_FACTION_NO_DESCRIPTION, []);
	}

	public static function arkania_faction_no_have() : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_FACTION_NO_HAVE, []);
	}

	public static function arkania_faction_no_invitation() : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_FACTION_NO_INVITATION, []);
	}

	public static function arkania_faction_no_officer() : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_FACTION_NO_OFFICER, []);
	}

	public static function arkania_faction_no_permission() : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_FACTION_NO_PERMISSION, []);
	}

	public static function arkania_faction_no_power() : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_FACTION_NO_POWER, []);
	}

	public static function arkania_faction_not_claimed() : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_FACTION_NOT_CLAIMED, []);
	}

	public static function arkania_faction_not_exists(Translatable|string $param0) : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_FACTION_NOT_EXISTS, [
			0 => $param0,
		]);
	}

	public static function arkania_faction_not_in_faction(Translatable|string $param0) : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_FACTION_NOT_IN_FACTION, [
			0 => $param0,
		]);
	}

	public static function arkania_faction_not_owner() : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_FACTION_NOT_OWNER, []);
	}

	public static function arkania_faction_owner_cant_leave() : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_FACTION_OWNER_CANT_LEAVE, []);
	}

	public static function arkania_faction_promoted(Translatable|string $param0) : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_FACTION_PROMOTED, [
			0 => $param0,
		]);
	}

	public static function arkania_faction_promoted_by(Translatable|string $param0) : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_FACTION_PROMOTED_BY, [
			0 => $param0,
		]);
	}

	public static function arkania_faction_unclaimed() : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_FACTION_UNCLAIMED, []);
	}

	public static function arkania_faction_use_help() : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_FACTION_USE_HELP, []);
	}

	public static function arkania_faction_view_chunk_disable() : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_FACTION_VIEW_CHUNK_DISABLE, []);
	}

	public static function arkania_faction_view_chunk_enable() : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_FACTION_VIEW_CHUNK_ENABLE, []);
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

	public static function arkania_moneyzone_created() : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_MONEYZONE_CREATED, []);
	}

	public static function arkania_moneyzone_description() : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_MONEYZONE_DESCRIPTION, []);
	}

	public static function arkania_moneyzone_position_description() : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_MONEYZONE_POSITION_DESCRIPTION, []);
	}

	public static function arkania_moneyzone_position_not_set() : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_MONEYZONE_POSITION_NOT_SET, []);
	}

	public static function arkania_moneyzone_position_set(Translatable|string $param0, Translatable|string $param1) : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_MONEYZONE_POSITION_SET, [
			0 => $param0,
			1 => $param1,
		]);
	}

	public static function arkania_npc_description() : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_NPC_DESCRIPTION, []);
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

	public static function arkania_piniata_create() : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_PINIATA_CREATE, []);
	}

	public static function arkania_piniata_description() : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_PINIATA_DESCRIPTION, []);
	}

	public static function arkania_piniata_end() : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_PINIATA_END, []);
	}

	public static function arkania_piniata_set_position() : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_PINIATA_SET_POSITION, []);
	}

	public static function arkania_piniata_start() : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_PINIATA_START, []);
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

	public static function arkania_rankup_you_have_ranked_up(Translatable|string $param0) : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_RANKUP_YOU_HAVE_RANKED_UP, [
			0 => $param0,
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

	public static function arkania_rename_description() : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_RENAME_DESCRIPTION, []);
	}

	public static function arkania_rename_error_format() : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_RENAME_ERROR_FORMAT, []);
	}

	public static function arkania_rename_error_length() : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_RENAME_ERROR_LENGTH, []);
	}

	public static function arkania_rename_success(Translatable|string $param0) : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_RENAME_SUCCESS, [
			0 => $param0,
		]);
	}

	public static function arkania_repair_cant_repair() : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_REPAIR_CANT_REPAIR, []);
	}

	public static function arkania_repair_description() : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_REPAIR_DESCRIPTION, []);
	}

	public static function arkania_repair_no_item() : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_REPAIR_NO_ITEM, []);
	}

	public static function arkania_repair_no_need() : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_REPAIR_NO_NEED, []);
	}

	public static function arkania_repair_success() : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_REPAIR_SUCCESS, []);
	}

	public static function arkania_report_description() : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_REPORT_DESCRIPTION, []);
	}

	public static function arkania_report_success(Translatable|string $param0, Translatable|string $param1) : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_REPORT_SUCCESS, [
			0 => $param0,
			1 => $param1,
		]);
	}

	public static function arkania_reportlogs_delete_success() : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_REPORTLOGS_DELETE_SUCCESS, []);
	}

	public static function arkania_reportlogs_description() : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_REPORTLOGS_DESCRIPTION, []);
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

	public static function arkania_tell_description() : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_TELL_DESCRIPTION, []);
	}

	public static function arkania_tell_message(Translatable|string $param0, Translatable|string $param1) : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_TELL_MESSAGE, [
			0 => $param0,
			1 => $param1,
		]);
	}

	public static function arkania_tell_reply_description() : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_TELL_REPLY_DESCRIPTION, []);
	}

	public static function arkania_tell_reply_no_target() : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_TELL_REPLY_NO_TARGET, []);
	}

	public static function arkania_tell_target_message(Translatable|string $param0, Translatable|string $param1) : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_TELL_TARGET_MESSAGE, [
			0 => $param0,
			1 => $param1,
		]);
	}

	public static function arkania_title_changed(Translatable|string $param0) : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_TITLE_CHANGED, [
			0 => $param0,
		]);
	}

	public static function arkania_title_description() : Translatable{
		return new Translatable(CustomTranslationKeys::ARKANIA_TITLE_DESCRIPTION, []);
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

	public static function npc_command_added() : Translatable{
		return new Translatable(CustomTranslationKeys::NPC_COMMAND_ADDED, []);
	}

	public static function npc_command_empty() : Translatable{
		return new Translatable(CustomTranslationKeys::NPC_COMMAND_EMPTY, []);
	}

	public static function npc_command_not_possible() : Translatable{
		return new Translatable(CustomTranslationKeys::NPC_COMMAND_NOT_POSSIBLE, []);
	}

	public static function npc_command_notfound(Translatable|string $param0) : Translatable{
		return new Translatable(CustomTranslationKeys::NPC_COMMAND_NOTFOUND, [
			0 => $param0,
		]);
	}

	public static function npc_command_removed() : Translatable{
		return new Translatable(CustomTranslationKeys::NPC_COMMAND_REMOVED, []);
	}

	public static function npc_create(Translatable|string $param0, Translatable|string $param1) : Translatable{
		return new Translatable(CustomTranslationKeys::NPC_CREATE, [
			0 => $param0,
			1 => $param1,
		]);
	}

	public static function npc_delete_success() : Translatable{
		return new Translatable(CustomTranslationKeys::NPC_DELETE_SUCCESS, []);
	}

	public static function npc_discord_link() : Translatable{
		return new Translatable(CustomTranslationKeys::NPC_DISCORD_LINK, []);
	}

	public static function npc_inventory_armor_boots_changed() : Translatable{
		return new Translatable(CustomTranslationKeys::NPC_INVENTORY_ARMOR_BOOTS_CHANGED, []);
	}

	public static function npc_inventory_armor_chestplate_changed() : Translatable{
		return new Translatable(CustomTranslationKeys::NPC_INVENTORY_ARMOR_CHESTPLATE_CHANGED, []);
	}

	public static function npc_inventory_armor_helmet_changed() : Translatable{
		return new Translatable(CustomTranslationKeys::NPC_INVENTORY_ARMOR_HELMET_CHANGED, []);
	}

	public static function npc_inventory_armor_leggings_changed() : Translatable{
		return new Translatable(CustomTranslationKeys::NPC_INVENTORY_ARMOR_LEGGINGS_CHANGED, []);
	}

	public static function npc_inventory_clear() : Translatable{
		return new Translatable(CustomTranslationKeys::NPC_INVENTORY_CLEAR, []);
	}

	public static function npc_inventory_item_changed() : Translatable{
		return new Translatable(CustomTranslationKeys::NPC_INVENTORY_ITEM_CHANGED, []);
	}

	public static function npc_inventory_not_human() : Translatable{
		return new Translatable(CustomTranslationKeys::NPC_INVENTORY_NOT_HUMAN, []);
	}

	public static function npc_name_changed(Translatable|string $param0) : Translatable{
		return new Translatable(CustomTranslationKeys::NPC_NAME_CHANGED, [
			0 => $param0,
		]);
	}

	public static function npc_pitch_invalid() : Translatable{
		return new Translatable(CustomTranslationKeys::NPC_PITCH_INVALID, []);
	}

	public static function npc_position_changed() : Translatable{
		return new Translatable(CustomTranslationKeys::NPC_POSITION_CHANGED, []);
	}

	public static function npc_size_changed(Translatable|string $param0) : Translatable{
		return new Translatable(CustomTranslationKeys::NPC_SIZE_CHANGED, [
			0 => $param0,
		]);
	}

	public static function npc_size_invalid() : Translatable{
		return new Translatable(CustomTranslationKeys::NPC_SIZE_INVALID, []);
	}

	public static function npc_skin_changed() : Translatable{
		return new Translatable(CustomTranslationKeys::NPC_SKIN_CHANGED, []);
	}

	public static function npc_skin_not_human() : Translatable{
		return new Translatable(CustomTranslationKeys::NPC_SKIN_NOT_HUMAN, []);
	}

	public static function npc_skin_not_online(Translatable|string $param0) : Translatable{
		return new Translatable(CustomTranslationKeys::NPC_SKIN_NOT_ONLINE, [
			0 => $param0,
		]);
	}

	public static function npc_tap_for_disband() : Translatable{
		return new Translatable(CustomTranslationKeys::NPC_TAP_FOR_DISBAND, []);
	}

	public static function npc_tap_for_edit() : Translatable{
		return new Translatable(CustomTranslationKeys::NPC_TAP_FOR_EDIT, []);
	}

	public static function npc_tap_for_rotate() : Translatable{
		return new Translatable(CustomTranslationKeys::NPC_TAP_FOR_ROTATE, []);
	}

	public static function npc_yaw_invalid() : Translatable{
		return new Translatable(CustomTranslationKeys::NPC_YAW_INVALID, []);
	}

}
