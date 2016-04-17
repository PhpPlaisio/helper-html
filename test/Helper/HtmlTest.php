<?php
//----------------------------------------------------------------------------------------------------------------------
use SetBased\Abc\Helper\Html;

//----------------------------------------------------------------------------------------------------------------------
class HtmlTest extends PHPUnit_Framework_TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test normal attributes set.
   */
  public function testAttributes1()
  {
    $values = ['0', 0];

    foreach ($values as $true)
    {
      $html = Html::generateAttribute('data-test', $true);
      $this->assertSame(' data-test="0"', $html);
    }

    $html = Html::generateAttribute('qwerty&?<', "<a>&");
    $this->assertSame(' qwerty&amp;?&lt;="&lt;a&gt;&amp;"', $html);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test attributes not set.
   */
  public function testAttributes2()
  {
    $values = [false, null, ''];

    foreach ($values as $value)
    {
      $html = Html::generateAttribute('data-test', $value);
      $this->assertSame('', $html);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test auto ID are different on each call.
   */
  public function testAutoId()
  {
    $id1 = Html::getAutoId();
    $id2 = Html::getAutoId();

    $this->assertNotSame($id1, $id2);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test boolean attributes set.
   */
  public function testBooleanAttributes1()
  {
    $attributes = ['autofocus',
                   'checked',
                   'disabled',
                   'hidden',
                   'ismap',
                   'multiple',
                   'novalidate',
                   'readonly',
                   'required',
                   'selected',
                   'spellcheck'];

    $values   = ['1', 1, true, $this];
    $values[] = $values;

    foreach ($attributes as $attribute)
    {
      foreach ($values as $value)
      {
        $html = Html::generateAttribute($attribute, $value);
        $this->assertSame(" $attribute=\"$attribute\"", $html);
      }
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test boolean attributes not set.
   */
  public function testBooleanAttributes2()
  {
    $attributes = ['autofocus',
                   'checked',
                   'disabled',
                   'hidden',
                   'ismap',
                   'multiple',
                   'novalidate',
                   'readonly',
                   'required',
                   'selected',
                   'spellcheck'];

    $values = ['0', 0, false, [], null, ''];

    foreach ($attributes as $attribute)
    {
      foreach ($values as $true)
      {
        $html = Html::generateAttribute($attribute, $true);
        $this->assertSame('', $html);
      }
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test boolean attributes set.
   */
  public function testBooleanAttributes3()
  {
    $attributes = ['draggable',
                   'contenteditable'];

    $values   = ['1', 1, true, $this];
    $values[] = $values;

    foreach ($attributes as $attribute)
    {
      foreach ($values as $value)
      {
        $html = Html::generateAttribute($attribute, $value);
        $this->assertSame(" $attribute=\"true\"", $html);
      }
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test boolean attributes not set.
   */
  public function testBooleanAttributes4()
  {
    $attributes = ['draggable',
                   'contenteditable'];

    $values = ['0', 0, false, [], ''];

    foreach ($attributes as $attribute)
    {
      foreach ($values as $value)
      {
        $html = Html::generateAttribute($attribute, $value);
        $this->assertSame(" $attribute=\"false\"", $html);
      }

      $html = Html::generateAttribute($attribute, null);
      $this->assertSame('', $html);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test boolean attributes set.
   */
  public function testBooleanAttributes5()
  {
    $attributes = ['autocomplete'];

    $values   = ['1', 1, true, $this];
    $values[] = $values;

    foreach ($attributes as $attribute)
    {
      foreach ($values as $value)
      {
        $html = Html::generateAttribute($attribute, $value);
        $this->assertSame(" $attribute=\"on\"", $html);
      }
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test boolean attributes not set.
   */
  public function testBooleanAttributes6()
  {
    $attributes = ['autocomplete'];

    $values = ['0', 0, false, [], ''];

    foreach ($attributes as $attribute)
    {
      foreach ($values as $value)
      {
        $html = Html::generateAttribute($attribute, $value);
        $this->assertSame(" $attribute=\"off\"", $html);
      }

      $html = Html::generateAttribute($attribute, null);
      $this->assertSame('', $html);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test boolean attributes set.
   */
  public function testBooleanAttributes7()
  {
    $attributes = ['translate'];

    $values   = ['1', 1, true, $this];
    $values[] = $values;

    foreach ($attributes as $attribute)
    {
      foreach ($values as $value)
      {
        $html = Html::generateAttribute($attribute, $value);
        $this->assertSame(" $attribute=\"yes\"", $html);
      }
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test boolean attributes not set.
   */
  public function testBooleanAttributes8()
  {
    $attributes = ['translate'];

    $values = ['0', 0, false, [], ''];

    foreach ($attributes as $attribute)
    {
      foreach ($values as $value)
      {
        $html = Html::generateAttribute($attribute, $value);
        $this->assertSame(" $attribute=\"no\"", $html);
      }

      $html = Html::generateAttribute($attribute, null);
      $this->assertSame('', $html);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test generateElement.
   */
  public function testGenerateTag1()
  {
    $tag = Html::generateElement('a', ['href' => 'https://www.setbased.nl'], 'SetBased');
    $this->assertEquals('<a href="https://www.setbased.nl">SetBased</a>', $tag);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test attributes with leading underscores are ignored.
   */
  public function testGenerateTag2()
  {
    $tag = Html::generateElement('a', ['href' => 'https://www.setbased.nl', '_ignore' => 'ignored'], 'SetBased');
    $this->assertEquals('<a href="https://www.setbased.nl">SetBased</a>', $tag);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test generateVoidElement.
   */
  public function testGenerateVoidTag1()
  {
    $tag = Html::generateVoidElement('img', ['src' => '/images/logo.png', 'alt' => 'logo']);
    $this->assertEquals('<img src="/images/logo.png" alt="logo"/>', $tag);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test attributes with leading underscores are ignored.
   */
  public function testGenerateVoidTag2()
  {
    $tag = Html::generateVoidElement('img', ['src' => '/images/logo.png', 'alt' => 'logo', '_ignore' => 'ignored']);
    $this->assertEquals('<img src="/images/logo.png" alt="logo"/>', $tag);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------

