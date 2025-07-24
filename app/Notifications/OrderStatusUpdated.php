<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class OrderStatusUpdated extends Notification
{
    use Queueable;

    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Statut de votre commande mis à jour')
            ->greeting('Bonjour ' . $notifiable->name)
            ->line("Le statut de votre commande n°{$this->order->id} a été mis à jour.")
            ->line("Nouveau statut : **{$this->order->status}**")
            ->action('Voir la commande', route('orders.show', $this->order->id))
            ->line('Merci pour votre confiance !');
    }
}
