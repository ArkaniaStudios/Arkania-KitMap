<?php
declare(strict_types=1);

namespace arkania\ranks;

use arkania\language\CustomTranslationFactory;
use arkania\Main;
use arkania\webhook\Embed;
use arkania\webhook\Message;
use arkania\webhook\Webhook;
use JsonException;
use pocketmine\lang\Translatable;
use pocketmine\utils\SingletonTrait;

class RanksManager {
    use SingletonTrait;

    /**
     * @param Ranks $rank
     * @return Translatable
     * @throws JsonException
     * @throws RankFailureException
     */
    public function addRank(Ranks $rank) : Translatable {
        if (!$rank->create()) {
            throw new RankFailureException(CustomTranslationFactory::arkania_ranks_create_failure($rank->getName())->getText());
        }
        $webhook = new Webhook(Main::ADMIN_URL);
        $message = new Message();
        $embed = new Embed();
        $embed->setTitle('**RANKS - ADD**')
            ->setContent('- Ajout d\'un nouveau grade' . PHP_EOL . PHP_EOL . '*Informations:*' . PHP_EOL . '- Nom: ' . $rank->getName() . PHP_EOL . '- Couleur: ' . $rank->getColor() . PHP_EOL . '- Prefix: ' . $rank->getRankFormatInfo()->getFormat() . PHP_EOL . '- Nametag: ' . $rank->getRankNametagFormatInfo()->getFormat())
            ->setFooter('ArkaniaStudios - Ranks')
            ->setColor(0xEFEB05)
            ->setImage();
        $message->addEmbed($embed);
        $webhook->send($message);
        return CustomTranslationFactory::arkania_ranks_create_success($rank->getName());
    }
}