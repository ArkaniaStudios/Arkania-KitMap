<?php
declare(strict_types=1);

namespace arkania\permissions;

use pocketmine\permission\DefaultPermissions;
use pocketmine\permission\Permission;
use pocketmine\utils\SingletonTrait;
use ReflectionClass;
use ReflectionClassConstant;
use ReflectionException;

class PermissionsManager {
    use SingletonTrait;

    public function __construct() {
        self::setInstance($this);
    }

    /**
     * @throws ReflectionException
     */
    public function registerPermissionClass(string $class) : void {
        $reflection = new ReflectionClass($class);
        $constants = $reflection->getConstants(ReflectionClassConstant::IS_PUBLIC);
        $consoleRoot = DefaultPermissions::registerPermission(new Permission(DefaultPermissions::ROOT_CONSOLE));
        $operatorRoot = DefaultPermissions::registerPermission(new Permission(DefaultPermissions::ROOT_OPERATOR), [$consoleRoot]);
        $everyoneRoot = DefaultPermissions::registerPermission(new Permission(DefaultPermissions::ROOT_USER), [$operatorRoot]);
        foreach ($constants as $constantName => $value) {
            if (str_contains($value, 'base')) {
                DefaultPermissions::registerPermission(new Permission($value), [$everyoneRoot]);
            }else{
                DefaultPermissions::registerPermission(new Permission($value), [$operatorRoot]);
            }
        }
    }
}