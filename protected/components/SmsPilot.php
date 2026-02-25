<?php
declare(strict_types=1);

class SmsPilot extends CApplicationComponent
{
    public $apiUrl = 'https://smspilot.ru/api.php';
    public $apiKey;
    public $sender;

    public function init(): void
    {
        parent::init();

        if (empty($this->apiKey)) {
            $this->apiKey = $_ENV['SMS_API_KEY'];
        }

        if (empty($this->sender)) {
            $this->sender = Yii::app()->name;
        }
    }

    public function send($to, $message)
    {
        $params = array(
            'apikey' => $this->apiKey,
            'to' => $this->formatPhone($to),
            'text' => $message,
            'from' => $this->sender,
            'format' => 'json'
        );

        $ch = curl_init($this->apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        $response = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            throw new Exception("SMS Pilot error: " . $error);
        }

        $result = json_decode($response, true);

        if (isset($result['error'])) {
            throw new Exception("SMS Pilot API error: " . $result['error']['description']);
        }

        return $result;
    }

    private function formatPhone($phone): array|string|null
    {
        $phone = preg_replace('/[^0-9]/', '', $phone);

        if (strlen($phone) === 10) {
            $phone = '7' . $phone;
        } elseif (strlen($phone) === 11 && $phone[0] === '8') {
            $phone = '7' . substr($phone, 1);
        }

        return $phone;
    }
}