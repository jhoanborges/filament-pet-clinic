<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TransactionEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public User $receiver,
        public User $sender,
        public string $message,
        public object $transaction,
    ) {}

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        \Log::info('Broadcasting TransactionEvent event', [
            'channel' => 'transaction.'.$this->receiver->id,
            'receiver_id' => $this->receiver->id,
            'message' => $this->message,
        ]);

        return [
            new PrivateChannel('transaction.'.$this->receiver->id),
        ];
    }

    public function broadcastAs()
    {
        return 'transaction.received';
    }

    public function broadcastWith()
    {
        return [
            'message' => $this->message,
            'from' => [
                'id' => $this->sender->id,
                'name' => $this->sender->name,
                'email' => $this->sender->email,
            ],
            'receiver' => [
                'id' => $this->receiver->id,
            ],
            'created_at' => now()->toDateTimeString(),
            'transaction' => $this->transaction,
        ];
    }

    public function broadcastWhen(): bool
    {
        \Log::info('Checking if event should be broadcast', [
            'receiver_id' => $this->receiver->id,
            'sender_id' => $this->sender->id,
        ]);

        return true;
    }
}
