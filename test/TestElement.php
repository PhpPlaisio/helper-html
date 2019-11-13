<?php
declare(strict_types=1);

namespace Plaisio\Helper\Test;

use Plaisio\Helper\Html;
use Plaisio\Helper\HtmlElement;

class TestElement extends HtmlElement
{
  //--------------------------------------------------------------------------------------------------------------------
  public function generateElement()
  {
    return Html::generateElement('test', $this->attributes);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
