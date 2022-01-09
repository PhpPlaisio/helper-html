<?php
declare(strict_types=1);

namespace Plaisio\Helper\Test;

use Plaisio\Helper\Html;
use Plaisio\Helper\HtmlElement;

/**
 * Element for testing purposes.
 */
class TestElement
{
  //--------------------------------------------------------------------------------------------------------------------
  use HtmlElement;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Echos the HTML code for this element.
   */
  public function echoElement(): void
  {
    $struct = ['tag'  => 'test',
               'attr' => $this->attributes,
               'html' => null];

    Html::echoNested($struct);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Generates the HTML code for this element.
   *
   * @return string
   */
  public function generateElement(): string
  {
    return Html::generateElement('test', $this->attributes);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Provides direct access to the attributes.
   *
   * @param mixed $key   The attribute
   * @param mixed $value The value of the attribute.
   *
   * @return $this
   */
  public function setAttribute($key, $value): self
  {
    $this->attributes[$key] = $value;

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
