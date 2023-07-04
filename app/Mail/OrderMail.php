<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;


class OrderMail extends Mailable
{
    use Queueable, SerializesModels;
    public $data_order;
    public $list_order;
    /**
     * Create a new message instance.
     */
    public function __construct($data_order, $list_order)
    {
        //
        $this->data_order = $data_order;
        $this->list_order = $list_order;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('phuit2000@gmail.com', 'Hệ Thống Thương Mại Điện Tử ISMART'),
            replyTo: [
                new Address('phuit2000@gmail.com', 'Hệ Thống Thương Mại Điện Tử ISMART'),
            ],
            subject: '[ISMART] Xác nhận đăng kí mua hàng thành công',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'mails.order',
            with: [
                'data_order' => $this->data_order,
                'list_order' => $this->list_order,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
