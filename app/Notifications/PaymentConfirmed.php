<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class PaymentConfirmed extends Notification
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
            ->subject('Paiement confirmé')
            ->greeting('Bonjour ' . $notifiable->name)
            ->line("Nous avons bien reçu le paiement pour votre commande n°{$this->order->id}.")
            ->action('Voir votre commande', route('orders.show', $this->order->id))
            ->line('Merci d’avoir choisi notre boutique !');
    }
    protected function getTotalAmount(): float|int
    {
        // Remplace cette logique par la bonne méthode de calcul du total
        return method_exists($this->order, 'sum')
            ? $this->order->sum()
            : $this->order->items->sum('total');
    }
}
