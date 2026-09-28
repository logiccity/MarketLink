<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderStatusChangedNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Order $order,
        public string $previousStatus,
        public ?string $reason = null
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject('Order Update: ' . $this->order->order_number . ' is ' . $this->order->status_label)
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('Your order ' . $this->order->order_number . ' status has updated to: **' . $this->order->status_label . '**.');

        if ($this->order->status === Order::STATUS_READY_FOR_PICKUP) {
            $mail->line('Your basket is packaged and ready for pickup at ' . $this->order->market->name . ' (' . $this->order->farmer->stall_name . ').');
        } elseif ($this->order->status === Order::STATUS_DECLINED) {
            $mail->line('Reason provided: ' . ($this->reason ?? 'Seller unavailable for this slot.'));
        }

        return $mail
            ->action('View Order', url('/customer/orders/' . $this->order->id))
            ->line('Thank you for choosing eGreen Basket MarketLink.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'order_status_changed',
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'status' => $this->order->status,
            'title' => 'Order ' . $this->order->order_number . ': ' . $this->order->status_label,
            'message' => 'Status changed to ' . $this->order->status_label . ($this->reason ? ' (' . $this->reason . ')' : '') . '.',
            'url' => route('customer.orders.show', $this->order->id),
        ];
    }
}
