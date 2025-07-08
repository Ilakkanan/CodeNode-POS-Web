<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LowStockNotification extends Notification
{
    use Queueable;

    public $inventory;

    public function __construct($inventory)
    {
        $this->inventory = $inventory;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'message' => "Low stock alert for {$this->inventory->product->name}. Available: {$this->inventory->available_quantity}, Alert: {$this->inventory->alert_quantity}",
            'product_id' => $this->inventory->product_id,
            'date' => now()->toDateString(), // Store the current date
        ];
    }
}