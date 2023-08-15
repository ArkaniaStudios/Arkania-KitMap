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

namespace arkania\pack;

use arkania\Main;
use arkania\path\FileSystem;
use pocketmine\resourcepacks\ZippedResourcePack;
use pocketmine\Server;
use pocketmine\utils\Config;
use ReflectionClass;
use ReflectionException;
use Symfony\Component\Filesystem\Path;
use ZipArchive;
use function file_exists;
use function is_dir;
use function md5_file;
use function scandir;

class ResourcesPack {

    protected static bool $isDev;
    private static bool $isEnable = false;

    /**
     * @throws InvalidResourcesPackException
     * @throws ReflectionException
     * @throws \JsonException
     */
    public function __construct(
        string $packName
    ) {
        if (!preg_match('/^[A-Za-z0-9_\-]/', $packName)) {
            throw new InvalidResourcesPackException("Invalid pack name");
        }


        if ($this->packExist($packName)){
            unlink($this->getPackPath($packName));
        }

        if (self::$isEnable){
            $this->loadPack($packName);
        }
    }

    public static function disableResourcePack() : void {
        self::$isEnable = false;
    }

    private function getPackPath(string $packName) : string {
        return Main::getInstance()->getDataFolder() . 'pack/' . $packName . '.zip';
    }

    public static function enableResourcePack(bool $dev = false) : void {
        self::$isEnable = true;
        self::$isDev = $dev;
    }

    private function packExist(string $packName) : bool {
        return file_exists($this->getPackPath($packName));
    }

    /**
     * @throws ReflectionException
     * @throws InvalidResourcesPackException
     * @throws \JsonException
     */
    private function loadPack(string $packName) : void {
        $this->savePackInData(Server::getInstance()->getPluginPath() . Main::getInstance()->getName() . '\\resources', 'pack\\' . $packName);
        $this->zipPack(Server::getInstance()->getPluginPath() . Main::getInstance()->getName() . '\\resources\\pack\\' . $packName, '', Main::getInstance()->getDataFolder() . 'pack/', $packName);
        if ($this->packExist($packName)) {
            FileSystem::deleteRecursiveDir(Main::getInstance()->getDataFolder() . 'pack/' . $packName);
        }
        $this->registerPack($packName);
    }

    /**
     * @throws ReflectionException
     */
    private function registerPack(string $packName) : void {
        $pack = new ZippedResourcePack(Main::getInstance()->getDataFolder() . 'pack/' . $packName . '.zip');
        $manager = Main::getInstance()->getServer()->getResourcePackManager();
        $reflection = new ReflectionClass($manager);
        $property = $reflection->getProperty('resourcePacks');
        $currentRessourcePacks = $property->getValue($manager);
        $currentRessourcePacks[] = $pack;
        $property->setValue($manager, $currentRessourcePacks);
        $property = $reflection->getProperty('uuidList');
        $currentUUIDPacks = $property->getValue($manager);
        $currentUUIDPacks[\strtolower($pack->getPackId())] = $pack;
        $property->setValue($manager, $currentUUIDPacks);
        $property = $reflection->getProperty("serverForceResources");
        $property->setValue($manager, true);
    }

    public function zipPack(string $path, string $dataPath, string $zipPath, string $type, ?ZipArchive $zip = null) : void {
        $close = false;
        $open = true;
        if ($zip === null) {
            $close = true;
            $open = false;
            $zip = new ZipArchive();
        }

        if (!$open || $zip->open(Path::join($zipPath, $type . ".zip"), ZipArchive::CREATE)) {
            foreach (array_diff(scandir($dirPath = Path::join($path, $dataPath)), ['.', '..']) as $file) {
                if (is_file($filePath = Path::join($dirPath, $file))) {
                    $zip->addFile($filePath, $dataPath !== "" ? Path::join($dataPath, $file) : $file);
                } else {
                    $this->zipPack($path, Path::join($dataPath, $file), $zipPath, $type, $zip);
                }
            }

            if ($close) {
                foreach (array_diff(scandir($dirPath = Path::join($path, $dataPath)), ['.', '..']) as $file) {
                    if (is_file($filePath = Path::join($dirPath, $file))) {
                        $zip->addFromString($file, file_get_contents($filePath));
                    }
                }

                $zip->close();
            }
        }
    }

    private function savePackInData(string $path, string $addPath = '') : void {
        $dir = opendir($path . '\\' . $addPath);
        if ($dir === false) {
            return;
        }
        while($file = readdir($dir)){
            if (is_file($path . '\\' . $addPath . '\\' . $file)){
                Main::getInstance()->saveResource($addPath . '\\' . $file, true);
            } else {
                if ($file != '.' && $file != '..'){
                    $this->savePackInData($path, $addPath . '\\' . $file);
                }
            }
        }
    }

    private function checkAllFile(string $basePath, string $path, string $secondBasePath, string $secondPath) : bool {
        $same = true;
        $scan = scandir($basePath . $path);
        if ($scan === false) {
            return false;
        }
        foreach ($scan as $value) {
            if ($value === "." || $value === "..") {
                continue;
            }
            if (is_dir($basePath . $path . "/" . $value)) {
                $same = self::checkAllFile($basePath, $path . "/" . $value, $secondBasePath, $path . "/" . $value);
            } else {
                if (file_exists($secondBasePath . $secondPath . "/" . $value)) {
                    $same = md5_file($basePath . $path . "/" . $value) === md5_file($secondBasePath . $secondPath . "/" . $value);
                } else {
                    $same = false;
                }
            }
        }
        return $same;
    }
}
