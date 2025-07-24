<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Order;

class OrderCreated extends Notification
{
    use Queueable;

    protected $order; // ✅ Déclaration de la propriété

    /**
     * Create a new notification instance.
     */
    public function __construct(Order $order) // ✅ Injection de l'objet Order
    {
        $this->order = $order; // ✅ Assignation
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Confirmation de votre commande')
            ->greeting('Bonjour ' . $notifiable->name)
            ->line('Votre commande #' . $this->order->id . ' a bien été enregistrée.')
            ->line('Montant total : ' . number_format($this->order->total_amount, 0, ',', ' ') . ' FCFA')
            ->action('Voir la commande', route('orders.show', $this->order->id))
            ->line('Merci pour votre achat !');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'order_id' => $this->order->id,
            'total' => $this->order->total_amount,
        ];
    }
}
