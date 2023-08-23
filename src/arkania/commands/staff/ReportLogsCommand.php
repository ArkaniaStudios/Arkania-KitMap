<?php
declare(strict_types=1);

namespace arkania\commands\staff;

use arkania\api\commands\BaseCommand;
use arkania\form\FormManager;
use arkania\language\CustomTranslationFactory;
use arkania\permissions\Permissions;
use arkania\player\CustomPlayer;
use pocketmine\command\CommandSender;

class ReportLogsCommand extends BaseCommand {

    public function __construct() {
        parent::__construct(
            'reportlogs',
            CustomTranslationFactory::arkania_reportlogs_description(),
            '/reportlogs',
            [],
            ['rl'],
            Permissions::ARKANIA_REPORTLOGS
        );
    }

    protected function registerArguments(): array {
        return [];
    }

    public function onRun(CommandSender $player, array $parameters): void {
        if (!$player instanceof CustomPlayer) return;

        FormManager::getInstance()->sendReportLogsForm($player);

    }

}