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
   * Returns the HTML code for this element.
   *
   * @return string
   */
  public function html(): string
  {
    $struct = ['tag'  => 'test',
              'attr' => $this->attributes,
              'html' => null];

    return Html::htmlNested($struct);
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
  public function setAttribute(mixed $key, mixed $value): self
  {
    $this->attributes[$key] = $value;

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
