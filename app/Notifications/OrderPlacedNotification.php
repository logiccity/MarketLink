<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderPlacedNotification extends Notification
{
    use Queueable;

    public function __construct(public Order $order)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Order Confirmation: ' . $this->order->order_number . ' - eGreen Basket')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('Your pre-order has been placed successfully with ' . $this->order->farmer->stall_name . ' at ' . $this->order->market->name . '.')
            ->line('Pickup Date: ' . $this->order->pickup_date->format('M d, Y'))
            ->line('Pickup Window: ' . ($this->order->pickupSlot ? $this->order->pickupSlot->time_range : 'During market hours'))
            ->line('Total: PKR ' . number_format($this->order->total, 2))
            ->line('Payment: In person at market pickup.')
            ->action('View Your Order', url('/customer/orders/' . $this->order->id))
            ->line('Thank you for supporting your local farmers!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'order_placed',
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'title' => 'Order ' . $this->order->order_number . ' Placed',
            'message' => 'Your order of PKR ' . number_format($this->order->total, 2) . ' with ' . $this->order->farmer->stall_name . ' was placed for ' . $this->order->pickup_date->format('M d, Y') . '.',
            'url' => route('customer.orders.show', $this->order->id),
        ];
    }
}
