<?php

namespace App\Notifications;

use App\Models\QuestionAnswer;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class QuestionAnsweredNotification extends Notification
{
    use Queueable;

    public function __construct(public QuestionAnswer $answer) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $question = $this->answer->question;

        return (new MailMessage)
            ->subject('Jawaban atas Pertanyaan Anda: '.$question->subject)
            ->greeting('Yth. '.$question->name.',')
            ->line('Terima kasih telah mengajukan pertanyaan melalui layanan masyarakat portal resmi kami.')
            ->line('**Topik:** '.($question->topic?->name ?? '-'))
            ->line('**Pertanyaan:** '.$question->body)
            ->line('---')
            ->line('**Jawaban Petugas:**')
            ->line($this->answer->body)
            ->line('Semoga informasi ini bermanfaat bagi Anda.')
            ->salutation('Salam hormat, Tim Layanan Informasi Publik');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'question_id' => $this->answer->question_id,
            'answer_id' => $this->answer->id,
            'subject' => $this->answer->question->subject,
        ];
    }
}
