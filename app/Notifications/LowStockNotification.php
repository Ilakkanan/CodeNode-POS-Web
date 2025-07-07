<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LowStockNotification extends Notification
{
    use Queueable;

    protected $inventory;

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
            'message' => 'Low stock alert for ' . $this->inventory->product->name,
            'product_id' => $this->inventory->product_id,
            'available_quantity' => $this->inventory->available_quantity,
            'alert_quantity' => $this->inventory->alert_quantity
        ];
    }
}