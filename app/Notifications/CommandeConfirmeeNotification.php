<?php

namespace App\Notifications;

use App\Models\Commande;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CommandeConfirmeeNotification extends Notification
{
    use Queueable;

    public function __construct(private readonly Commande $commande)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'commande_confirmee',
            'title' => 'Commande confirmee',
            'message' => "Votre commande #{$this->commande->id} a ete confirmee par l administrateur.",
            'commande_id' => $this->commande->id,
        ];
    }
}
