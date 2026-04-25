<?php

namespace App\Notifications;

use App\Models\Commande;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CommandeRejeteeNotification extends Notification
{
    use Queueable;

    public function __construct(
        private readonly Commande $commande,
        private readonly string $raison
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'commande_rejetee',
            'title' => 'Commande rejetee',
            'message' => "Votre commande #{$this->commande->id} a ete rejetee.",
            'commande_id' => $this->commande->id,
            'raison' => $this->raison,
        ];
    }
}
