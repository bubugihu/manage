<?php

namespace Yeni\Library\Api;

class Gmail_Api
{
    public function getMailAcb($content)
    {
        $scriptUrl = "https://script.google.com/macros/s/AKfycbw6IO6dbkTf8iN0IrUgtiKmPJeDr1DhycKjoI6ULu29tOxsurIGYxrExzxltHrQMntq/exec";
        $data = [
            'search' => 'is:unread from:mailalert@acb.com.vn after:2024/01/15'
        ];
        $ch = curl_init($scriptUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS , $data);
        $response = curl_exec($ch);
        return json_decode($response, true);
    }

    public function checkBodyMail($mail_array, $content)
    {
        $result = false;
        foreach($mail_array as $mail)
        {
            $body = $mail[2]; //body
            $convert_body_to_lower = strtolower($body);
            $key = strtolower($content);
            $result =  mb_stripos($convert_body_to_lower, $key) !== false;
            if($result) break;
        }
        return $result;
    }
}
