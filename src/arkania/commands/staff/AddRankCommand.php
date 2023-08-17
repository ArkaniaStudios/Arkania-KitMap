<?php
declare(strict_types=1);

namespace arkania\commands\staff;

use arkania\api\commands\arguments\StringArgument;
use arkania\api\commands\BaseCommand;
use arkania\language\CustomTranslationFactory;
use arkania\permissions\Permissions;
use arkania\ranks\elements\RanksFormatInfo;
use arkania\ranks\InvalidFormatException;
use arkania\ranks\RankFailureException;
use arkania\ranks\Ranks;
use arkania\ranks\RanksManager;
use pocketmine\command\CommandSender;
use pocketmine\command\utils\InvalidCommandSyntaxException;

class AddRankCommand extends BaseCommand {

    public function __construct() {
        parent::__construct(
            'addrank',
            CustomTranslationFactory::arkania_ranks_addrank_description(),
            '/addrank <rank: string',
            permission: Permissions::ARKANIA_ADDRANK
        );
    }

    protected function registerArguments(): array {
        return [
            new StringArgument('rank', false),
            new StringArgument('color', true)
        ];
    }

    public function onRun(CommandSender $player, array $parameters): void {
        if (count($parameters) < 1) {
            throw new InvalidCommandSyntaxException();
        }

        $rank = $parameters['rank'];

        if (RanksManager::getInstance()->exists($rank)){
            $player->sendMessage(CustomTranslationFactory::arkania_ranks_addrank_exist($rank));
            return;
        }

        try {
            $message = RanksManager::getInstance()->addRank(new Ranks(
                $rank,
                new RanksFormatInfo('[{PLAYER_RANK}] [' . $rank . '] {PLAYER} » {MESSAGE}'),
                new RanksFormatInfo('[' . $rank . '] {LINE} {PLAYER}'),
                null,
                $parameters['color'] ?? '§f',
                false,
            ));
            $player->sendMessage($message);
        }catch (RankFailureException $exception){
            $player->sendMessage($exception->getMessage());
            return;
        } catch (InvalidFormatException $e) {
            $player->sendMessage($e->getMessage());
            return;
        }
    }
}