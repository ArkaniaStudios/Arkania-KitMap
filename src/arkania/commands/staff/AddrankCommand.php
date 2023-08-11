<?php
declare(strict_types=1);

namespace arkania\commands\staff;

use arkania\api\BaseCommand;
use arkania\language\CustomTranslationFactory;
use arkania\permissions\Permissions;

class AddrankCommand extends BaseCommand {

    public function __construct() {
        parent::__construct(
            'addrank',
            CustomTranslationFactory::arkania_ranks_addrank_description(),
            '/addrank <rank: string',
            permission: Permissions::ARKANIA_ADDRANK
        );
    }
}