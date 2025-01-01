<?php

namespace BayramoffCom\TelegramMailDriver\Transport;


use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Envelope;
use Symfony\Component\Mime\Email;
use Illuminate\Support\Facades\Http;

class TelegramTransport extends AbstractTransport
{
    protected string $chatId;
    protected string $botToken;

    /**
     * @param  string  $chatId
     * @param  string  $botToken
     */
    public function __construct(string $chatId, string $botToken)
    {
        $this->chatId = $chatId;
        $this->botToken = $botToken;

        parent::__construct(); // Call the AbstractTransport constructor
    }

    /**
     * This method is responsible for actually "sending" the mail, which in this
     * case means sending a Telegram message to the specified chat.
     */
    protected function doSend(SentMessage $message): void
    {
        $email = $message->getOriginalMessage();

        $subject = $email->getSubject() ?? '(No Subject)';
        $textBody = $email->getTextBody() ?? '';
        $htmlBody = $email->getHtmlBody() ?? '';

        // Here we combine the subject + textBody (or stripped HTML) into a single message
        $message = sprintf(
            "*%s*\n\n%s",
            $subject,
            !empty($textBody) ? $textBody : strip_tags($htmlBody)
        );

        $filename = 'public/' . rand(0, 1000) . ".html";
        $filename = storage_path('app/' . $filename);
        file_put_contents($filename, $message);



        $caption = "";
        // 2. TO addresses (also returns an array of Address objects)
        $toAddresses = $email->getTo();
        // You can loop to get them all
        foreach ($toAddresses as $address) {

            $recipientEmail = $address->getAddress() . "\n";
            $caption .= $recipientEmail;

            //$recipientName = $address->getName();
            // do something with $recipientEmail / $recipientName
        }

        $caption .= "\n\n📧 " . $subject;


        $ch = curl_init("https://api.telegram.org/bot{$this->botToken}/sendDocument");
        $postFields = [
            'chat_id' => $this->chatId,
            'caption' => $caption,
            'document' => new \CURLFile($filename)
        ];
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $postFields,
            CURLOPT_RETURNTRANSFER => true,
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        unlink($filename);
    }

    public function __toString(): string
    {
        // Unique identifier for logs/debugging
        return 'telegram';
    }
}
