<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Order;

class OrderConfirmed extends Notification
{
    use Queueable;

    protected Order $order;

    /**
     * Create a new notification instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Confirmation de commande')
            ->greeting('Bonjour ' . $notifiable->name)
            ->line("Votre commande n°{$this->order->id} a été reçue avec succès.")
            ->line('Montant total : ' . number_format($this->getTotalAmount(), 0, ',', ' ') . ' FCFA')
            ->action('Voir ma commande', route('orders.show', $this->order->id))
            ->line('Merci pour votre confiance !');
    }

    /**
     * Get the total amount of the order.
     */
    protected function getTotalAmount(): float|int
    {
        // Remplace cette logique par la bonne méthode de calcul du total
        return method_exists($this->order, 'sum')
            ? $this->order->sum()
            : $this->order->items->sum('total');
    }
}
