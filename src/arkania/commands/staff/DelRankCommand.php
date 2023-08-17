<?php
declare(strict_types=1);

namespace arkania\commands\staff;

use arkania\api\commands\arguments\StringArgument;
use arkania\api\commands\BaseCommand;
use arkania\language\CustomTranslationFactory;
use arkania\permissions\Permissions;
use arkania\player\CustomPlayer;
use arkania\ranks\RankFailureException;
use arkania\ranks\RanksManager;
use pocketmine\command\CommandSender;
use pocketmine\command\utils\InvalidCommandSyntaxException;

class DelRankCommand extends BaseCommand {

    public function __construct() {
        parent::__construct(
            'delrank',
            CustomTranslationFactory::arkania_ranks_delete_description(),
            '/delrank <rank>',
            permission: Permissions::ARKANIA_DELRANk
        );
    }

    protected function registerArguments(): array {
        return [
            new StringArgument('rank', false)
        ];
    }

    public function onRun(CommandSender $player, array $parameters): void {
        if (!$player instanceof CustomPlayer) return;

        if (count($parameters) !== 1){
            throw new InvalidCommandSyntaxException();
        }

        $rank = $parameters['rank'];

        if (!RanksManager::getInstance()->exists($rank)) {
            $player->sendMessage(CustomTranslationFactory::arkania_ranks_no_exist($rank));
            return;
        }

        try {
            $message = RanksManager::getInstance()->delRank($rank);
            $player->sendMessage($message);
        }catch (RankFailureException $exception) {
            $player->sendMessage($exception->getMessage());
        } catch (\JsonException $e) {
            return;
        }
    }
}