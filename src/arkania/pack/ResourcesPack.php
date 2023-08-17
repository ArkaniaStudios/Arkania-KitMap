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

    private function loadPack(string $packName) : void {
        $path = Path::join(Server::getInstance()->getPluginPath(), Main::getInstance()->getName(), 'resources');
        $this->savePackInData(Path::join($path, 'pack', $packName));
        $this->zipPack(Path::join($path, 'pack', $packName), Path::join(Main::getInstance()->getDataFolder(), 'pack'), $packName);
        $this->registerPack($packName);
    }

    private function registerPack(string $packName) : void {
        $resourcesPack = Server::getInstance()->getResourcePackManager();
        $resourcesPack->setResourceStack(array_merge($resourcesPack->getResourceStack(), [
            new ZippedResourcePack(Main::getInstance()->getDataFolder() . 'pack/' . $packName . '.zip')
        ]));
    }

    private function zipPack(string $path, string $zipPath, string $type) : void {
        $archive = new ZipArchive();
        $archive->open(Path::join($zipPath, $type . ".zip"), ZipArchive::CREATE);
        $this->addToArchive($path, $type, $archive);
        $archive->close();
    }

    private function addToArchive(string $path, string $type, ZipArchive $archive, string $dataPath = "") : void {
        foreach (array_diff(scandir($dirPath = Path::join($path, $dataPath)), ['.', '..']) as $file) {
            if (is_file($filePath = Path::join($dirPath, $file))) {
                $archive->addFile($filePath, $dataPath !== "" ? Path::join($dataPath, $file) : $file);
            } else {
                $this->addToArchive($path, $type, $archive, Path::join($dataPath, $file));
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
}
