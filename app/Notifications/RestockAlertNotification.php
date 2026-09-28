<?php

namespace App\Notifications;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RestockAlertNotification extends Notification
{
    use Queueable;

    public function __construct(public Product $product)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Restock Alert: ' . $this->product->name . ' is back in stock!')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('Great news! A product you favorited, **' . $this->product->name . '**, has been restocked by ' . $this->product->farmer->stall_name . '.')
            ->line('Price: $' . number_format($this->product->price, 2) . ' / ' . $this->product->unit)
            ->action('View Product & Pre-Order', url('/products/' . $this->product->id))
            ->line('Reserve your produce early before stock runs out!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'product_restocked',
            'product_id' => $this->product->id,
            'title' => $this->product->name . ' is back in stock!',
            'message' => $this->product->name . ' from ' . $this->product->farmer->stall_name . ' is now available with ' . $this->product->quantity . ' ' . $this->product->unit . ' in stock.',
            'url' => route('products.show', $this->product->id),
        ];
    }
}
