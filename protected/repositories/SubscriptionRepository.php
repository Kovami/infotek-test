<?php
declare(strict_types=1);

class SubscriptionRepository
{
    public function create(int $authorId, string $phone): array
    {
        $subscription = new Subscription();
        $subscription->author_id = $authorId;
        $subscription->phone = $phone;

        if ($subscription->save()) {
            return array('success' => true, 'model' => $subscription);
        }

        return array('success' => false, 'errors' => $subscription->getErrors());
    }

    public function findByAuthorAndPhone($authorId, $phone)
    {
        return Subscription::model()->findByAttributes(array(
            'author_id' => $authorId,
            'phone' => $phone
        ));
    }

    public function findByAuthor($authorId)
    {
        return Subscription::model()
            ->with('author')
            ->findAllByAttributes(array('author_id' => $authorId));
    }

    public function delete($id): bool
    {
        $subscription = Subscription::model()->findByPk($id);
        if ($subscription) {
            return $subscription->delete();
        }
        return false;
    }

    public function isSubscribed($authorId, $phone): bool
    {
        return Subscription::model()->exists(
            'author_id = :author_id AND phone = :phone',
            array(':author_id' => $authorId, ':phone' => $phone)
        );
    }
}