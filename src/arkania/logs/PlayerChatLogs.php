<?php
declare(strict_types=1);

namespace arkania\logs;

use arkania\logs\async\SubmitMessageAsyncTask;
use arkania\Main;
use arkania\utils\trait\Date;
use arkania\webhook\Message;
use arkania\webhook\Webhook;
use pocketmine\utils\SingletonTrait;

class PlayerChatLogs {
    use SingletonTrait;

    /** @var (string|mixed)[] */
    private array $chat_messages = [];

    private int $last_message = 0;

    public function addChatMessage(string $player, string $message): void {
        if (isset($this->chat_messages[$player])){
            $this->chat_messages[$player]['messages'][] = $message;
        }else{
            $this->chat_messages[$player] = [
                'messages' => [$message],
                'date' => Date::create()->toString()
            ];
        }
        $this->last_message = time() + 30;
    }

    /**
     * @return array|string[]
     */
    public function getChatMessages(): array {
        return $this->chat_messages;
    }

    public function resetChatMessages(): void {
        $this->chat_messages = [];
    }

    /**
     * @param string $player
     * @return string[]
     */
    public function getChatMessage(string $player): array {
        return $this->chat_messages[$player];
    }

    public function checkIfSendMessage(string $playerName) : void {
        if (count($this->chat_messages) >= 50 || $this->last_message - time() === 0 || count($this->chat_messages[$playerName]['messages']) >= 25){
            $chatMessage = $this->getChatMessages();
            $this->sendChatMessage($chatMessage);
            $this->resetChatMessages();
        }
    }

    public function sendChatMessage(array $chatMessage) : void {
        Main::getInstance()->getServer()->getAsyncPool()->submitTask(
            new SubmitMessageAsyncTask(
                static function (SubmitMessageAsyncTask $task) use ($chatMessage) : void {
                    $messages = $chatMessage;
                    $msg = '';
                    foreach ($messages as $player => $value) {
                        foreach ($value['messages'] as $message){
                            $msg .= '[' . $value['date'] . '] ' . $player . ': ' . $message . "\n";
                        }
                    }
                    $task->setResult($msg);
                },
                static function (mixed $result) : void {
                    $webhook = new Webhook('https://discord.com/api/webhooks/1140459367171366993/w0rEWH9WB69zBXx_fmBA1xiYUfrSAFavu_5pzZDCcstgUyGrIuAbjKp9xuwDoC9roKXf');
                    $message = new Message();
                    $message->setContent($result);
                    $webhook->send($message);
                }
            )
        );
    }

}