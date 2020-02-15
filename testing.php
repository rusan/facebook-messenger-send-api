<?php

/**
 * Using this file for testing the code of this package.
 */

require_once __DIR__ . '/vendor/autoload.php';

$API = new \FacebookMessengerSendApi\SendAPI();

// The app access token.
$access_token = '';

// The user recipient ID.
$recipient = 0;

$API
  ->setAccessToken($access_token)
  ->setRecipientId($recipient);

$Attachment = new \FacebookMessengerSendApi\Templates\Attachment();

$Attachment
    ->type('image')
    ->url('https://media.giphy.com/media/tGbhyv8Wmi4EM/giphy.gif')
    ->isReusable(true);

$result = $API
    ->sendAttachment($Attachment);
$attachment = json_decode($result->getBody(), 1);

$Button = new \FacebookMessengerSendApi\Buttons\Url();
$Button
    ->url('https://pomogisviborom.ru')
    ->title('А вот тут текст ответа');

$Element = new \FacebookMessengerSendApi\Templates\Element();
$Element
    ->mediaType('image')
    ->attachmentId($attachment['attachment_id'])
    ->addButton($Button);

$Media = new \FacebookMessengerSendApi\Templates\Media();
$Media->sharable(true);
$Media->addElement($Element);

$result = $API->sendMessage($Media);
print_r($result);

