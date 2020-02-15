<?php

namespace FacebookMessengerSendApi;

use GuzzleHttp\Client;

class SendAPI {

  /**
   * The access token of the app.
   *
   * @var string
   */
  protected $accessToken;

  /**
   * The user ID.
   *
   * @var integer
   */
  protected $recipientId;

  /**
   * A tag for the message.
   *
   * @var string
   */
  protected $tag;

  /**
   * Get the access token.
   *
   * @return string
   *   The access token of the app.
   */
  public function getAccessToken() {
    return $this->accessToken;
  }

  /**
   * Set the access token.
   *
   * @param string $accessToken
   *   The access token of the app.
   *
   * @return SendAPI
   *   The current instance.
   */
  public function setAccessToken($accessToken) {
    $this->accessToken = $accessToken;

    return $this;
  }

  /**
   * Set the user ID which will receive the message.
   *
   * @param int $recipientId
   *   The user ID.
   *
   * @return SendAPI
   *   The current instance.
   */
  public function setRecipientId($recipientId) {
    $this->recipientId = $recipientId;

    return $this;
  }

  /**
   * Get the user ID.
   *
   * @return int
   *   The user ID.
   */
  public function getRecipientId() {
    return $this->recipientId;
  }

  /**
   * Set the tag of a message.
   *
   * @param $tag
   *   The tag.
   *
   * @return $this
   */
  public function setTag($tag) {
    $this->tag = $tag;

    return $this;
  }

  /**
   * Get the tag.
   *
   * @return string
   */
  public function getTag() {
    return $this->tag;
  }

  /**
   * Sending a message.
   *
   * @param array|string $text
   *   The text is self or an array matching the send API.
   *
   * @return \Psr\Http\Message\ResponseInterface
   */
  public function sendMessage($text) {
    if ($text instanceof SendAPITransform) {
      $message = $text->getData();
    }
    else {
      $message = !is_array($text) ? $message = ['text' => $text] : $text;
    }

    return $this->send('message', $message);
  }

  /**
   * Send an action to the user.
   *
   * @param $action
   *   The action: mark_seen, typing_on or typing_off.
   *
   * @return \Psr\Http\Message\ResponseInterface
   */
  public function senderActions($action) {
    return $this->send('sender_action', $action);
  }

  /**
   * Send attachment to save
   *
   * @param Templates\Attachment $attachment
   *
   * @return \Psr\Http\Message\ResponseInterface
   */
  public function sendAttachment(Templates\Attachment $attachment) {
    $options = [
      'json' => [
        'message' => $attachment->getData()
      ]
    ];

    return $this->guzzle()->post('https://graph.facebook.com/v6.0/me/message_attachments?access_token=' . $this->accessToken, $options);
  }

  /**
   * Sending to the facebook messenger some payload.
   *
   * It could be a message with attachment or or a sender action.
   *
   * @param $key
   *   If you want to send a message the key need to be 'message'. If not, use
   *   'sender_action'
   *
   * @param $value
   *   The value of the payload.
   *
   * @return \Psr\Http\Message\ResponseInterface
   */
  protected function send($key, $value) {
    $options = [
      'form_params' => [
        'recipient' => [
          'id' => $this->recipientId,
        ],
        $key => $value,
      ],
    ];

    if (!empty($this->tag)) {
      // Adding the tag to the body.
      $options['form_params']['tag'] = $this->tag;
    }

    return $this->guzzle()->post('https://graph.facebook.com/v6.0/me/messages?access_token=' . $this->accessToken, $options);
  }

  /**
   * Alias for guzzle.
   *
   * @return \GuzzleHttp\Client
   *   Guzzle object.
   */
  public function guzzle() {
    return new Client();
  }

}
