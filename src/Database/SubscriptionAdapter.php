<?php
namespace App\Database;

class SubscriptionAdapter
{
    private $subscriptionFactory;
    private $subscriptions = [];
    private $filePath = 'subscriptions.txt';

    public function __construct(SubscriptionFactory $subscriptionFactory)
    {
        $this->subscriptionFactory = $subscriptionFactory;
        $this->loadSubscriptionsFromFile();
    }

    public function saveSubscription(Subscription $subscription)
    {
        $this->subscriptions[] = $subscription;
        $this->saveSubscriptionsToFile();
    }

    public function getSubscriptionsByUser($userId)
    {
        $userSubscriptions = [];
        foreach ($this->subscriptions as $subscription) {
            if ($subscription->getUserId() == $userId) {
                $userSubscriptions[] = $subscription;
            }
        }
        return $userSubscriptions;
    }

    public function deleteSubscription($subscriptionId)
    {
        foreach ($this->subscriptions as $key => $subscription) {
            if ($subscription->getUserId() == $subscriptionId) {
                unset($this->subscriptions[$key]);
                break;
            }
        }
        $this->saveSubscriptionsToFile();
    }

    private function loadSubscriptionsFromFile()
    {
        if (file_exists($this->filePath)) {
            $subscriptionsData = file_get_contents($this->filePath);
            $subscriptionsData = json_decode($subscriptionsData, true);
            foreach ($subscriptionsData as $subscriptionData) {
                $subscription = $this->subscriptionFactory->createSubscription(
                    $subscriptionData['user_id'],
                    $subscriptionData['email'],
                    $subscriptionData['sms']
                );
                $subscription->setUserId($subscriptionData['user_id']);
                //$subscription->setStatus($subscriptionData['status']);
                $this->subscriptions[] = $subscription;
            }
        }

    }

    private function saveSubscriptionsToFile()
    {
        $subscriptionsData = [];
        foreach ($this->subscriptions as $subscription) {
            $subscriptionsData[] = [
                'user_id' => $subscription->getUserId(),
                'email' => $subscription->getEmail(),
                'sms' => $subscription->getSms(),
            ];
        }
        file_put_contents($this->filePath, json_encode($subscriptionsData));
    }

    public function getAllSubscriptions()
    {
        $prepareSubscriptions = [];
        if (!empty($this->subscriptions)) {
            foreach($this->subscriptions as $subscription) {
                $prepareSubscriptions[$subscription->getUserId()] = [
                    'sms' => $subscription->getSms(),
                    'email' => $subscription->getEmail()
                ];
            }
        }
        return $prepareSubscriptions;
    }
}