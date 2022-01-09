<?php
declare(strict_types=1);

namespace Plaisio\Helper\Test;

use PHPUnit\Framework\TestCase;
use Plaisio\Helper\Html;
use SetBased\Exception\FallenException;

/**
 * Test cases for class Html.
 */
class HtmlTest extends TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns test cases for class attribute.
   *
   * @return array
   */
  public function casesClassAttribute(): array
  {
    $cases = [];

    // Empty class.
    $cases[] = ['value'    => '',
                'expected' => ''];

    $cases[] = ['value'    => null,
                'expected' => ''];

    // False must be cast to '0'.
    $cases[] = ['value'    => false,
                'expected' => ' class="0"'];

    // Classes as array.
    $cases[] = ['value'    => [],
                'expected' => ''];

    $cases[] = ['value'    => ['hello', 'world'],
                'expected' => ' class="hello world"'];

    // Classes as array with duplicate and empty values.
    $cases[] = ['value'    => ['hello', 'hello', '', null, 'world', false],
                'expected' => ' class="0 hello world"'];

    return $cases;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns invalid test cases for method txt2html.
   *
   * @return array
   */
  public function casesInvalidTxt2Html(): array
  {
    $cases = [];

    $cases[] = ['value' => []];

    $cases[] = ['value' => $this];

    return $cases;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns valid test cases for method txt2html.
   *
   * @return array
   */
  public function casesValidTxt2Html(): array
  {
    $cases = [];

    $cases[] = ['value'    => '',
                'expected' => ''];

    $cases[] = ['value'    => null,
                'expected' => ''];

    $cases[] = ['value'    => false,
                'expected' => '0'];

    $cases[] = ['value'    => true,
                'expected' => '1'];

    $cases[] = ['value'    => '123',
                'expected' => '123'];

    $cases[] = ['value'    => M_PI,
                'expected' => (string)M_PI];

    return $cases;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Tests for generation attribute class.
   *
   * @param mixed  $value    The value for the class attribute.
   * @param string $expected The expected generated HTML code.
   *
   * @dataProvider casesClassAttribute
   */
  public function testAttributeClass($value, string $expected)
  {
    $html = Html::generateAttribute('class', $value);
    $this->assertSame($expected, $html);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test normal attributes set.
   */
  public function testAttributes1(): void
  {
    $values = ['0', 0, false];

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
  public function testAttributes2(): void
  {
    $values = [null, ''];

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
  public function testAutoId(): void
  {
    $id1 = Html::getAutoId();
    $id2 = Html::getAutoId();

    $this->assertNotSame($id1, $id2);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test boolean attributes set.
   */
  public function testBooleanAttributes1(): void
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

    $values   = ['1', 1, true, $this, 'hello, world', ['hello, world']];
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
  public function testBooleanAttributes2(): void
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
  public function testBooleanAttributes3(): void
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
  public function testBooleanAttributes4(): void
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
  public function testBooleanAttributes5(): void
  {
    $attributes = ['autocomplete'];

    $values   = ['1', 1, true, $this, 'hello, world', ['hello, world']];
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
  public function testBooleanAttributes6(): void
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
  public function testBooleanAttributes7(): void
  {
    $attributes = ['translate'];

    $values   = ['1', 1, true, $this, 'hello, world', ['hello, world']];
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
  public function testBooleanAttributes8(): void
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
   * Tests for generation attribute class.
   *
   * @param mixed  $value    The value for the class attribute.
   * @param string $expected The expected generated HTML code.
   *
   * @dataProvider casesClassAttribute
   */
  public function testEchoAttributeClass($value, string $expected)
  {
    $struct = ['tag'  => 'div',
               'attr' => ['class' => $value],
               'html' => null];
    ob_start();
    Html::echoNested($struct);
    $html = ob_get_clean();
    $this->assertSame("<div$expected></div>", $html);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test normal attributes set.
   */
  public function testEchoAttributes1(): void
  {
    $values = ['0', 0, false];

    foreach ($values as $value)
    {
      $struct = ['tag'  => 'div',
                 'attr' => ['data-test' => $value],
                 'html' => null];
      ob_start();
      Html::echoNested($struct);
      $html = ob_get_clean();
      $this->assertSame('<div data-test="0"></div>', $html);
    }

    $struct = ['tag'  => 'div',
               'attr' => ['qwerty&?<' => "<a>&"],
               'html' => null];
    ob_start();
    Html::echoNested($struct);
    $html = ob_get_clean();
    $this->assertSame('<div qwerty&amp;?&lt;="&lt;a&gt;&amp;"></div>', $html);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test attributes not set.
   */
  public function testEchoAttributes2(): void
  {
    $values = [null, ''];

    foreach ($values as $value)
    {
      $struct = ['tag'  => 'div',
                 'attr' => ['data-test' => $value],
                 'html' => null];
      ob_start();
      Html::echoNested($struct);
      $html = ob_get_clean();
      $this->assertSame('<div></div>', $html);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test boolean attributes set.
   */
  public function testEchoBooleanAttributes1(): void
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

    $values   = ['1', 1, true, $this, 'hello, world', ['hello, world']];
    $values[] = $values;

    foreach ($attributes as $attribute)
    {
      foreach ($values as $value)
      {
        $struct = ['tag'  => 'div',
                   'attr' => [$attribute => $value],
                   'html' => null];
        ob_start();
        Html::echoNested($struct);
        $html = ob_get_clean();
        $this->assertSame("<div $attribute=\"$attribute\"></div>", $html);
      }
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test boolean attributes not set.
   */
  public function testEchoBooleanAttributes2(): void
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
      foreach ($values as $value)
      {
        $struct = ['tag'  => 'div',
                   'attr' => [$attribute => $value],
                   'html' => null];
        ob_start();
        Html::echoNested($struct);
        $html = ob_get_clean();
        $this->assertSame("<div></div>", $html);
      }
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test boolean attributes set.
   */
  public function testEchoBooleanAttributes3(): void
  {
    $attributes = ['draggable', 'contenteditable'];

    $values   = ['1', 1, true, $this];
    $values[] = $values;

    foreach ($attributes as $attribute)
    {
      foreach ($values as $value)
      {
        $struct = ['tag'  => 'div',
                   'attr' => [$attribute => $value],
                   'html' => null];
        ob_start();
        Html::echoNested($struct);
        $html = ob_get_clean();
        $this->assertSame("<div $attribute=\"true\"></div>", $html);
      }
    }

    $struct = ['tag'  => 'div',
               'attr' => ['draggable' => 'auto'],
               'html' => null];
    ob_start();
    Html::echoNested($struct);
    $html = ob_get_clean();
    $this->assertSame("<div draggable=\"auto\"></div>", $html);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test boolean attributes not set.
   */
  public function testEchoBooleanAttributes4(): void
  {
    $attributes = ['draggable', 'contenteditable'];

    $values = ['0', 0, false, [], ''];

    foreach ($attributes as $attribute)
    {
      foreach ($values as $value)
      {
        $struct = ['tag'  => 'div',
                   'attr' => [$attribute => $value],
                   'html' => null];
        ob_start();
        Html::echoNested($struct);
        $html = ob_get_clean();
        $this->assertSame("<div $attribute=\"false\"></div>", $html);
      }

      $struct = ['tag'  => 'div',
                 'attr' => [$attribute => null],
                 'html' => null];
      ob_start();
      Html::echoNested($struct);
      $html = ob_get_clean();
      $this->assertSame('<div></div>', $html);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test boolean attributes set.
   */
  public function testEchoBooleanAttributes5(): void
  {
    $attributes = ['autocomplete'];

    $values   = ['1', 1, true, $this, 'hello, world', ['hello, world']];
    $values[] = $values;

    foreach ($attributes as $attribute)
    {
      foreach ($values as $value)
      {
        $struct = ['tag'  => 'div',
                   'attr' => [$attribute => $value],
                   'html' => null];
        ob_start();
        Html::echoNested($struct);
        $html = ob_get_clean();
        $this->assertSame("<div $attribute=\"on\"></div>", $html);
      }
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test boolean attributes not set.
   */
  public function testEchoBooleanAttributes6(): void
  {
    $attributes = ['autocomplete'];

    $values = ['0', 0, false, [], ''];

    foreach ($attributes as $attribute)
    {
      foreach ($values as $value)
      {
        $struct = ['tag'  => 'div',
                   'attr' => [$attribute => $value],
                   'html' => null];
        ob_start();
        Html::echoNested($struct);
        $html = ob_get_clean();
        $this->assertSame("<div $attribute=\"off\"></div>", $html);
      }

      $struct = ['tag'  => 'div',
                 'attr' => [$attribute => null],
                 'html' => null];
      ob_start();
      Html::echoNested($struct);
      $html = ob_get_clean();
      $this->assertSame('<div></div>', $html);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test boolean attributes set.
   */
  public function testEchoBooleanAttributes7(): void
  {
    $attributes = ['translate'];

    $values   = ['1', 1, true, $this, 'hello, world', ['hello, world']];
    $values[] = $values;

    foreach ($attributes as $attribute)
    {
      foreach ($values as $value)
      {
        $struct = ['tag'  => 'div',
                   'attr' => [$attribute => $value],
                   'html' => null];
        ob_start();
        Html::echoNested($struct);
        $html = ob_get_clean();
        $this->assertSame("<div $attribute=\"yes\"></div>", $html);
      }
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test boolean attributes not set.
   */
  public function testEchoBooleanAttributes8(): void
  {
    $attributes = ['translate'];

    $values = ['0', 0, false, [], ''];

    foreach ($attributes as $attribute)
    {
      foreach ($values as $value)
      {
        $struct = ['tag'  => 'div',
                   'attr' => [$attribute => $value],
                   'html' => null];
        ob_start();
        Html::echoNested($struct);
        $html = ob_get_clean();
        $this->assertSame("<div $attribute=\"no\"></div>", $html);
      }

      $struct = ['tag'  => 'div',
                 'attr' => [$attribute => null],
                 'html' => null];
      ob_start();
      Html::echoNested($struct);
      $html = ob_get_clean();
      $this->assertSame('<div></div>', $html);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test echoNested with void element.
   */
  public function testEchoNested01(): void
  {
    ob_start();

    Html::echoNested(['tag' => 'br']);
    self::assertSame('<br/>', ob_get_clean());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test echoNested with element.
   */
  public function testEchoNested02(): void
  {
    ob_start();

    Html::echoNested(['tag'  => 'a',
                      'attr' => ['href' => 'https://github.com/PhpPlaisio/helper-html'],
                      'text' => 'helper & html']);
    self::assertSame('<a href="https://github.com/PhpPlaisio/helper-html">helper &amp; html</a>', ob_get_clean());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test echoNested with element with integer value.
   */
  public function testEchoNested03(): void
  {
    ob_start();

    Html::echoNested(['tag'  => 'a',
                      'attr' => ['href' => 'https://github.com/PhpPlaisio/helper-html'],
                      'text' => 123]);
    self::assertSame('<a href="https://github.com/PhpPlaisio/helper-html">123</a>', ob_get_clean());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test echoNested with element with HTML value.
   */
  public function testEchoNested04(): void
  {
    ob_start();

    Html::echoNested(['tag'  => 'a',
                      'attr' => ['href' => 'https://github.com/PhpPlaisio/helper-html'],
                      'html' => '<b>helper-html</b>']);
    self::assertSame('<a href="https://github.com/PhpPlaisio/helper-html"><b>helper-html</b></a>', ob_get_clean());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test echoNested with nested elements.
   */
  public function testEchoNested05(): void
  {
    ob_start();

    Html::echoNested(['tag'   => 'a',
                      'attr'  => ['href' => 'https://github.com/PhpPlaisio/helper-html'],
                      'inner' => ['tag'  => 'b',
                                  'text' => 'helper-html']]);
    self::assertSame('<a href="https://github.com/PhpPlaisio/helper-html"><b>helper-html</b></a>', ob_get_clean());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test echoNested with list of elements.
   */
  public function testEchoNested06(): void
  {
    ob_start();

    Html::echoNested([['tag'   => 'a',
                       'attr'  => ['href' => 'https://github.com/PhpPlaisio/helper-html'],
                       'inner' => ['tag'  => 'b',
                                   'text' => 'helper-html']],
                      ['tag' => 'br']]);
    self::assertSame('<a href="https://github.com/PhpPlaisio/helper-html"><b>helper-html</b></a><br/>', ob_get_clean());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test echoNested with list of elements.
   */
  public function testEchoNested07(): void
  {
    $this->expectException(\LogicException::class);
    Html::echoNested(['xhtml' => 'xml-html']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test echoNested with null.
   */
  public function testEchoNested08(): void
  {
    ob_start();

    Html::echoNested(null);

    self::assertSame('', ob_get_clean());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test echoNested with null element.
   */
  public function testEchoNested09(): void
  {
    ob_start();

    Html::echoNested([null]);

    self::assertSame('', ob_get_clean());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test echoNested with list of elements with null inner.
   */
  public function testEchoNested10(): void
  {
    ob_start();

    Html::echoNested(['tag'   => 'span',
                      'attr'  => ['class' => 'test'],
                      'inner' => null]);

    self::assertSame('<span class="test"></span>', ob_get_clean());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test echoNested with list of elements.
   */
  public function testEchoNested11(): void
  {
    ob_start();

    Html::echoNested([['tag'   => 'table',
                       'attr'  => ['class' => 'test'],
                       'inner' => [['tag'   => 'tr',
                                    'attr'  => ['id' => 'first-row'],
                                    'inner' => [['tag'  => 'td',
                                                 'text' => 'hello'],
                                                ['tag'  => 'td',
                                                 'attr' => ['class' => 'bold'],
                                                 'html' => '<b>world</b>']]],
                                   ['tag'   => 'tr',
                                    'inner' => [['tag'  => 'td',
                                                 'text' => 'foo'],
                                                ['tag'  => 'td',
                                                 'text' => 'bar']]],
                                   ['tag'   => 'tr',
                                    'attr'  => ['id' => 'last-row'],
                                    'inner' => [['tag'  => 'td',
                                                 'text' => 'foo'],
                                                ['tag'  => 'td',
                                                 'text' => 'bar']]]]],
                      ['text' => 'The End'],
                      ['html' => '!']]);

    self::assertSame('<table class="test"><tr id="first-row"><td>hello</td><td class="bold"><b>world</b></td></tr><tr><td>foo</td><td>bar</td></tr><tr id="last-row"><td>foo</td><td>bar</td></tr></table>The End!', ob_get_clean());
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test generateElement.
   */
  public function testGenerateElement1(): void
  {
    $tag = Html::generateElement('a', ['href' => 'https://www.setbased.nl'], 'SetBased');
    $this->assertEquals('<a href="https://www.setbased.nl">SetBased</a>', $tag);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test attributes with empty innerText.
   */
  public function testGenerateElement3(): void
  {
    $tag = Html::generateElement('span', ['class' => 'null'], '');
    $this->assertEquals('<span class="null"></span>', $tag);

    $tag = Html::generateElement('span', ['class' => 'null'], null);
    $this->assertEquals('<span class="null"></span>', $tag);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test generateNested with void element.
   */
  public function testGenerateNested01(): void
  {
    $html = Html::generateNested(['tag' => 'br']);
    self::assertSame('<br/>', $html);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test generateNested with element.
   */
  public function testGenerateNested02(): void
  {
    $html = Html::generateNested(['tag'  => 'a',
                                  'attr' => ['href' => 'https://github.com/PhpPlaisio/helper-html'],
                                  'text' => 'helper & html']);
    self::assertSame('<a href="https://github.com/PhpPlaisio/helper-html">helper &amp; html</a>', $html);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test generateNested with element with integer value.
   */
  public function testGenerateNested03(): void
  {
    $html = Html::generateNested(['tag'  => 'a',
                                  'attr' => ['href' => 'https://github.com/PhpPlaisio/helper-html'],
                                  'text' => 123]);
    self::assertSame('<a href="https://github.com/PhpPlaisio/helper-html">123</a>', $html);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test generateNested with element with HTML value.
   */
  public function testGenerateNested04(): void
  {
    $html = Html::generateNested(['tag'  => 'a',
                                  'attr' => ['href' => 'https://github.com/PhpPlaisio/helper-html'],
                                  'html' => '<b>helper-html</b>']);
    self::assertSame('<a href="https://github.com/PhpPlaisio/helper-html"><b>helper-html</b></a>', $html);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test generateNested with nested elements.
   */
  public function testGenerateNested05(): void
  {
    $html = Html::generateNested(['tag'   => 'a',
                                  'attr'  => ['href' => 'https://github.com/PhpPlaisio/helper-html'],
                                  'inner' => ['tag'  => 'b',
                                              'text' => 'helper-html']]);
    self::assertSame('<a href="https://github.com/PhpPlaisio/helper-html"><b>helper-html</b></a>', $html);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test generateNested with list of elements.
   */
  public function testGenerateNested06(): void
  {
    $html = Html::generateNested([['tag'   => 'a',
                                   'attr'  => ['href' => 'https://github.com/PhpPlaisio/helper-html'],
                                   'inner' => ['tag'  => 'b',
                                               'text' => 'helper-html']],
                                  ['tag' => 'br']]);
    self::assertSame('<a href="https://github.com/PhpPlaisio/helper-html"><b>helper-html</b></a><br/>', $html);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test generateNested with list of elements.
   */
  public function testGenerateNested07(): void
  {
    $this->expectException(\LogicException::class);
    Html::generateNested(['xhtml' => 'xml-html']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test generateNested with null.
   */
  public function testGenerateNested08(): void
  {
    $html = Html::generateNested(null);

    self::assertSame('', $html);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test generateNested with null element.
   */
  public function testGenerateNested09(): void
  {
    $html = Html::generateNested([null]);

    self::assertSame('', $html);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test generateNested with list of elements with null inner.
   */
  public function testGenerateNested10(): void
  {
    $html = Html::generateNested(['tag'   => 'span',
                                  'attr'  => ['class' => 'test'],
                                  'inner' => null]);

    self::assertSame('<span class="test"></span>', $html);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test generateNested with list of elements.
   */
  public function testGenerateNested11(): void
  {
    $html = Html::generateNested([['tag'   => 'table',
                                   'attr'  => ['class' => 'test'],
                                   'inner' => [['tag'   => 'tr',
                                                'attr'  => ['id' => 'first-row'],
                                                'inner' => [['tag'  => 'td',
                                                             'text' => 'hello'],
                                                            ['tag'  => 'td',
                                                             'attr' => ['class' => 'bold'],
                                                             'html' => '<b>world</b>']]],
                                               ['tag'   => 'tr',
                                                'inner' => [['tag'  => 'td',
                                                             'text' => 'foo'],
                                                            ['tag'  => 'td',
                                                             'text' => 'bar']]],
                                               ['tag'   => 'tr',
                                                'attr'  => ['id' => 'last-row'],
                                                'inner' => [['tag'  => 'td',
                                                             'text' => 'foo'],
                                                            ['tag'  => 'td',
                                                             'text' => 'bar']]]]],
                                  ['text' => 'The End'],
                                  ['html' => '!']]);

    self::assertSame('<table class="test"><tr id="first-row"><td>hello</td><td class="bold"><b>world</b></td></tr><tr><td>foo</td><td>bar</td></tr><tr id="last-row"><td>foo</td><td>bar</td></tr></table>The End!', $html);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test generateVoidElement.
   */
  public function testGenerateVoidElement1(): void
  {
    $tag = Html::generateVoidElement('img', ['src' => '/images/logo.png', 'alt' => 'logo']);
    $this->assertEquals('<img src="/images/logo.png" alt="logo"/>', $tag);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Invalid tests for method txt2html.
   *
   * @param mixed $value The value.
   *
   * @dataProvider casesInvalidTxt2Html
   */
  public function testInvalidTxt2Html($value)
  {
    $this->expectException(FallenException::class);
    Html::txt2Html($value);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test cases for txt2Slug.
   *
   * Test cases copied from [Matteo Spinelli's Cubiq.org](http://cubiq.org/the-perfect-php-clean-url-generator) and
   * from <http://stackoverflow.com>.
   */
  public function testTxt2Slug(): void
  {
    // Test for null.
    $part = Html::txt2Slug(null);
    $this->assertSame('', $part);

    // Test for empty string.
    $part = Html::txt2Slug('');
    $this->assertSame('', $part);

    // Test for spaces.
    $part = Html::txt2Slug('  ');
    $this->assertSame('', $part);

    // Test for normal string.
    $part = Html::txt2Slug('bar');
    $this->assertEquals('bar', $part);

    $cases["Mess'd up --text-- just (to) stress /test/ ?our! `little` clean url fun.ction!?-->"] = 'mess-d-up-text-just-to-stress-test-our-little-clean-url-fun-ction';
    $cases['Perché l\'erba è verde?']                                                            = 'perche-l-erba-e-verde';
    $cases['Peux-tu m\'aider s\'il te plaît?']                                                   = 'peux-tu-m-aider-s-il-te-plait';
    $cases['Tänk efter nu – förr\'n vi föser dig bort']                                          = 'tank-efter-nu-forr-n-vi-foser-dig-bort';
    $cases['ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖÙÚÛÜÝßàáâãäåæçèéêëìíîïðñòóôõöùúûüýÿ']                         = 'aaaaaaaeceeeeiiiienooooouuuuyszaaaaaaaeceeeeiiiienooooouuuuyy';
    $cases['Custom`delimiter*example']                                                           = 'custom-delimiter-example';
    $cases['My+Last_Crazy|delimiter/example']                                                    = 'my-last-crazy-delimiter-example';
    $cases['Perché l’erba è verde?']                                                             = 'perche-l-erba-e-verde';
    $cases['test é another for à and why not ô ?']                                               = 'test-e-another-for-a-and-why-not-o';
    $cases['I just say no! #$%^&*']                                                              = 'i-just-say-no';
    $cases['əƏ']                                                                                 = '';
    $cases['Æther']                                                                              = 'aether';
    $cases['one kožušček']                                                                       = 'one-kozuscek';
    $cases['Компьютер']                                                                          = 'kompiuter';
    $cases['My custom хелло ворлд']                                                              = 'my-custom-khello-vorld';
    $cases['Mørdag']                                                                             = 'mordag';

    foreach ($cases as $case => $expected)
    {
      $part = Html::txt2Slug($case);
      $this->assertEquals($expected, $part, $case);
    }
  }
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Valid tests for method txt2html.
   *
   * @param mixed  $value    The value.
   * @param string $expected The expected generated HTML code.
   *
   * @dataProvider casesValidTxt2Html
   */
  public function testValidTxt2Html($value, string $expected)
  {
    $html = Html::txt2Html($value);
    $this->assertSame($expected, $html);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------


