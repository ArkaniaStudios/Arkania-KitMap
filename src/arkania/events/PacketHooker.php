<?php
declare(strict_types=1);

namespace arkania\events;

use arkania\api\commands\BaseCommand;
use arkania\api\commands\interface\ArgumentableInterface;
use arkania\api\commands\SoftEnumStore;
use arkania\libs\muqsit\simplepackethandler\SimplePacketHandler;
use pocketmine\command\CommandSender;
use pocketmine\event\EventPriority;
use pocketmine\event\Listener;
use pocketmine\network\mcpe\NetworkSession;
use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;
use pocketmine\network\mcpe\protocol\types\command\CommandEnum;
use pocketmine\network\mcpe\protocol\types\command\CommandOverload;
use pocketmine\network\mcpe\protocol\types\command\CommandParameter;
use pocketmine\plugin\Plugin;
use pocketmine\Server;
use ReflectionClass;

class PacketHooker implements Listener {

    /** @var bool */
    private static bool $isRegistered = false;
    /** @var bool */
    private static bool $isIntercepting = false;

    public static function isRegistered(): bool {
        return self::$isRegistered;
    }

    public static function register(Plugin $registrant): void {
        if(self::$isRegistered) {
            throw new \InvalidArgumentException("Event listener is already registered by another plugin.");
        }

        $interceptor = SimplePacketHandler::createInterceptor($registrant, EventPriority::NORMAL, false);
        $interceptor->interceptOutgoing(function(AvailableCommandsPacket $pk, NetworkSession $target) : bool{
            if(self::$isIntercepting)return true;
            $p = $target->getPlayer();
            foreach($pk->commandData as $commandName => $commandData) {
                $cmd = Server::getInstance()->getCommandMap()->getCommand($commandName);
                if($cmd instanceof BaseCommand) {
                    $pk->commandData[$commandName]->overloads = self::generateOverloads($p, $cmd);
                }
            }
            $pk->softEnums = SoftEnumStore::getEnums();
            self::$isIntercepting = true;
            $target->sendDataPacket($pk);
            self::$isIntercepting = false;
            return false;
        });

        self::$isRegistered = true;
    }

    /**
     * @param CommandSender $cs
     * @param BaseCommand $command
     *
     * @return CommandOverload[][]
     */
    private static function generateOverloads(CommandSender $cs, BaseCommand $command): array {
        $overloads = [];
        foreach(self::generateOverloadList($command) as $overload) {
            $overloads[] = $overload;
        }

        return $overloads;
    }

    /**
     * @param ArgumentableInterface $argumentable
     *
     * @return CommandOverload[][]
     */
    private static function generateOverloadList(ArgumentableInterface $argumentable): array {
        $input = $argumentable->getArgumentList();
        $combinations = [];
        $outputLength = array_product(array_map("count", $input));
        $indexes = [];
        foreach($input as $k => $charList){
            $indexes[$k] = 0;
        }
        do {
            /** @var CommandParameter[] $set */
            $set = [];
            foreach($indexes as $k => $index){
                $param = $set[$k] = clone $input[$k][$index]->getNetworkParameter();

                if(isset($param->enum) && $param->enum instanceof CommandEnum){
                    $refClass = new ReflectionClass(CommandEnum::class);
                    $refProp = $refClass->getProperty("enumName");
                    $refProp->setAccessible(true);
                    $refProp->setValue($param->enum, "enum#" . spl_object_id($param->enum));
                }
            }
            $combinations[] = new CommandOverload(false, $set);

            foreach($indexes as $k => $v){
                $indexes[$k]++;
                $lim = count($input[$k]);
                if($indexes[$k] >= $lim){
                    $indexes[$k] = 0;
                    continue;
                }
                break;
            }
        } while(count($combinations) !== $outputLength);

        return $combinations;
    }

}