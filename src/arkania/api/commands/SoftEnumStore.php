<?php
declare(strict_types=1);

namespace arkania\api\commands;

use arkania\Main;
use arkania\MainException;
use pocketmine\network\mcpe\protocol\types\command\CommandEnum;
use pocketmine\network\mcpe\protocol\UpdateSoftEnumPacket;

class SoftEnumStore {

    /** @var CommandEnum[] */
    public static array $enums = [];

    public static function getEnumByName(string $name):?CommandEnum {
        return static::$enums[$name] ?? null;
    }

    /**
     * @return CommandEnum[]
     */
    public static function getEnums(): array {
        return static::$enums;
    }

    public static function addEnum(CommandEnum $enum) : void {
        static::$enums[$enum->getName()] = $enum;
        self::broadcastSoftEnum($enum, UpdateSoftEnumPacket::TYPE_ADD);
    }

    /**
     * @param string $enumName
     * @param (string|mixed)[] $values
     * @return void
     * @throws MainException
     */
    public static function updateEnum(string $enumName, array $values):void {
        if(self::getEnumByName($enumName) === null){
            throw new MainException("Unknown enum named " . $enumName);
        }
        $enum = self::$enums[$enumName] = new CommandEnum($enumName, $values);
        self::broadcastSoftEnum($enum, UpdateSoftEnumPacket::TYPE_SET);
    }

    /**
     * @throws MainException
     */
    public static function removeEnum(string $enumName):void {
        if(($enum = self::getEnumByName($enumName)) === null){
            throw new MainException("Unknown enum named " . $enumName);
        }
        unset(static::$enums[$enumName]);
        self::broadcastSoftEnum($enum, UpdateSoftEnumPacket::TYPE_REMOVE);
    }

    public static function broadcastSoftEnum(CommandEnum $enum, int $type):void {
        $pk = new UpdateSoftEnumPacket();
        $pk->enumName = $enum->getName();
        $pk->values = $enum->getValues();
        $pk->type = $type;
        foreach (Main::getInstance()->getServer()->getOnlinePlayers() as $player) {
            $player->getNetworkSession()->sendDataPacket($pk);
        }
    }

}