<?php

namespace Yeni\Library\Api;

class Gmail_Api
{
    public function getBodyMailVCB()
    {
        $client = new \Google_Client();
        $client->setAuthConfig('gmail.json');
        $client->setRedirectUri("http://manage.local/yeni/report/");
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');
        $client->addScope(\Google_Service_Gmail::GMAIL_READONLY);

        // Authenticate
        if ($client->isAccessTokenExpired())
        {
            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
        }

        // Create Gmail service
        $service = new \Google_Service_Gmail($client);

        // Lấy ngày hôm nay (theo múi giờ của bạn)
        $today = new \DateTime('now', new \DateTimeZone('YOUR_TIMEZONE'));

        // Định dạng ngày thành chuỗi theo định dạng của Gmail
        $todayString = $today->format('Y/m/d');

        // Tạo truy vấn lọc
        $query = 'is:unread from:VCBDigibank@info.vietcombank.com.vn after:' . $todayString;

        // Lấy danh sách email chưa đọc và được gửi từ địa chỉ cụ thể trong ngày hôm nay
        $messages = $service->users_messages->listUsersMessages('me', ['q' => $query]);
        // Xử lý các email như bạn cần
        foreach ($messages->getMessages() as $message) {
            $message = $service->users_messages->get('me', $message->getId());
            $headers = $message->getPayload()->getHeaders();

            // Extract sender, subject, and other information from headers
            foreach ($headers as $header) {
                if ($header->getName() == 'From') {
                    $sender = $header->getValue();
                } elseif ($header->getName() == 'Subject') {
                    $subject = $header->getValue();
                }
            }
            dd($headers);
            // Đánh dấu email là đã đọc (nếu cần)
            $service->users_messages->modify('me', $message->getId(), ['removeLabelIds' => ['UNREAD']]);
        }
    }
}
