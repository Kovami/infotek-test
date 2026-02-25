<?php
declare(strict_types=1);

class SubscriptionService
{
    private $subscriptionRepository;
    private $smsComponent;

    public function __construct()
    {
        $this->subscriptionRepository = new SubscriptionRepository();
        $this->smsComponent = Yii::app()->sms;
    }

    public function subscribe($authorId, $phone): array
    {
        $author = Author::model()->findByPk($authorId);
        if (!$author) {
            return array(
                'success' => false,
                'errors' => array('author_id' => array('Автор не найден'))
            );
        }

        if (!$this->validatePhone($phone)) {
            return array(
                'success' => false,
                'errors' => array('phone' => array('Неверный формат телефона'))
            );
        }

        if ($this->subscriptionRepository->isSubscribed($authorId, $phone)) {
            return array(
                'success' => false,
                'errors' => array('phone' => array('Вы уже подписаны на этого автора'))
            );
        }

        $result = $this->subscriptionRepository->create($authorId, $phone);

        if ($result['success']) {
            $this->smsComponent->send(
                $phone,
                "Вы подписались на автора: {$author->full_name}"
            );

            Yii::log("Новая подписка: {$phone} -> {$author->full_name}", 'info', 'subscription');
        }

        return $result;
    }

    public function notifyAboutNewBook($authorId, Book $book): int
    {
        $subscribers = $this->subscriptionRepository->findByAuthor($authorId);
        $author = Author::model()->findByPk($authorId);

        if (empty($subscribers)) {
            return 0;
        }

        $message = "Новая книга у {$author->full_name}: {$book->title} ({$book->year})";

        $sent = 0;
        foreach ($subscribers as $subscription) {
            try {
                $this->smsComponent->send($subscription->phone, $message);
                $sent++;

                Yii::log("Уведомление отправлено: {$subscription->phone}", 'info', 'sms');
            } catch (Exception $e) {
                Yii::log("Ошибка отправки SMS: " . $e->getMessage(), 'error', 'sms');
            }
        }

        return $sent;
    }

    private function validatePhone($phone): bool
    {
        return (bool)preg_match('/^(\+7|7|8)?[\s\-]?\(?[0-9]{3}\)?[\s\-]?[0-9]{3}[\s\-]?[0-9]{2}[\s\-]?[0-9]{2}$/', $phone);
    }
}