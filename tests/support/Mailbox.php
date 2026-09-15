<?php

declare(strict_types=1);

namespace app\tests\support;

use Closure;
use PHPUnit\Framework\Assert;
use yii\base\Event;
use yii\mail\BaseMailer;
use yii\mail\MailEvent;
use yii\mail\MessageInterface;

/**
 * Captures every message the application tries to send and cancels the actual delivery.
 */
final class Mailbox
{
    /** @var list<MessageInterface> */
    private array $messages = [];

    private Closure $handler;

    private function __construct()
    {
        $this->handler = function (MailEvent $event): void {
            $this->messages[] = $event->message;
            $event->isValid = false;
        };
    }

    public static function open(): self
    {
        $mailbox = new self();
        Event::on(BaseMailer::class, BaseMailer::EVENT_BEFORE_SEND, $mailbox->handler);

        return $mailbox;
    }

    public function close(): void
    {
        Event::off(BaseMailer::class, BaseMailer::EVENT_BEFORE_SEND, $this->handler);
    }

    /**
     * @return list<MessageInterface>
     */
    public function messages(): array
    {
        return $this->messages;
    }

    public function count(): int
    {
        return count($this->messages);
    }

    public function last(): MessageInterface
    {
        Assert::assertNotEmpty($this->messages, 'Expected at least one message to have been sent');

        return $this->messages[array_key_last($this->messages)];
    }

    public function assertNothingSent(): void
    {
        Assert::assertCount(0, $this->messages, 'Expected no message to have been sent');
    }

    /**
     * Asserts that exactly one message was addressed to the email and returns it.
     */
    public function assertSentTo(string $email): MessageInterface
    {
        $matches = array_values(array_filter(
            $this->messages,
            static fn (MessageInterface $message): bool => in_array($email, self::recipients($message), true),
        ));

        Assert::assertCount(1, $matches, sprintf('Expected exactly one message sent to %s', $email));

        return $matches[0];
    }

    /**
     * @return list<string>
     */
    private static function recipients(MessageInterface $message): array
    {
        $to = $message->getTo();

        if (is_string($to)) {
            return [$to];
        }

        // Yii represents recipients either as a list of addresses or as address => name pairs.
        return array_map(
            static fn (int|string $key, mixed $value): string => is_int($key) ? (string) $value : (string) $key,
            array_keys($to),
            $to,
        );
    }
}
