<?php

namespace FacebookMessengerSendApi\Templates;

use FacebookMessengerSendApi\SendAPITransform;

/**
 * Class Generic
 *
 * @see https://developers.facebook.com/docs/messenger-platform/send-api-reference/generic-template
 */
class Media extends SendAPITransform {

  /**
   * Generic constructor.
   */
  public function __construct() {
    $this->data['attachment']['type'] = 'template';
    $this->data['attachment']['payload']['template_type'] = 'media';
  }

  /**
   * Set shareable.
   *
   * @param $shareable
   *   Set to false to disable the native share button in Messenger for the
   *   template message.
   *
   * @return $this
   */
  public function sharable($shareable) {
    $this->data['attachment']['payload']['sharable'] = $shareable;

    return $this;
  }

  /**
   * Set the element.
   *
   * @param Element $element
   *   The element object.
   *
   * @return $this
   */
  public function addElement(Element $element) {
    $this->data['attachment']['payload']['elements'][] = $element->getData();

    return $this;
  }
}
