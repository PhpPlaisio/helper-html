<?php
declare(strict_types=1);

namespace Plaisio\Helper\Test;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Plaisio\Helper\Html;
use SetBased\Exception\FallenException;

/**
 * Test cases for class HTML.
 */
class HtmlTest extends TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns test cases for class attribute.
   *
   * @return array
   */
  public static function casesClassAttribute(): array
  {
    $cases = [];

    // Empty class.
    $cases[] = ['value'    => '',
                'expected' => '<div></div>'];

    $cases[] = ['value'    => null,
                'expected' => '<div></div>'];

    // False must be cast to '0'.
    $cases[] = ['value'    => false,
                'expected' => '<div class="0"></div>'];

    // Classes as an array.
    $cases[] = ['value'    => [],
                'expected' => '<div></div>'];

    $cases[] = ['value'    => ['hello', 'world'],
                'expected' => '<div class="hello world"></div>'];

    // Classes as an array with duplicate and empty values.
    $cases[] = ['value'    => ['hello', 'hello', '', null, 'world', false],
                'expected' => '<div class="0 hello world"></div>'];

    return $cases;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns invalid test cases for method txt2html.
   *
   * @return array
   */
  public static function casesInvalidTxt2Html(): array
  {
    $cases = [];

    $cases[] = ['value' => []];

    $cases[] = ['value' => new \stdClass()];

    return $cases;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns valid test cases for method txt2html.
   *
   * @return array
   */
  public static function casesValidTxt2Html(): array
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
   */
  #[DataProvider('casesClassAttribute')]
  public function testAttributeClass(mixed $value, string $expected)
  {
    $html = Html::htmlNested(['tag'  => 'div',
                              'attr' => ['class' => $value],
                              'html' => null]);
    $this->assertSame($expected, $html);
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
   * Test Html::htmlNested() with boolean attributes set.
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
        $html = Html::htmlNested(['tag'  => 'div',
                                  'attr' => [$attribute => $value],
                                  'html' => null]);
        $this->assertSame("<div $attribute=\"$attribute\"></div>", $html);
      }
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test Html::htmlNested() with boolean attributes not set.
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
      foreach ($values as $value)
      {
        $html = Html::htmlNested(['tag'  => 'div',
                                  'attr' => [$attribute => $value],
                                  'html' => null]);
        $this->assertSame('<div></div>', $html);
      }
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test Html::htmlNested() with boolean attributes set.
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
        $html = Html::htmlNested(['tag'  => 'div',
                                  'attr' => [$attribute => $value],
                                  'html' => null]);
        $this->assertSame("<div $attribute=\"true\"></div>", $html);
      }
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test Html::htmlNested() with boolean attributes not set.
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
        $html = Html::htmlNested(['tag'  => 'div',
                                  'attr' => [$attribute => $value],
                                  'html' => null]);
        $this->assertSame("<div $attribute=\"false\"></div>", $html);
      }

      $html = Html::htmlNested(['tag'  => 'div',
                                'attr' => [$attribute => null],
                                'html' => null]);
      $this->assertSame('<div></div>', $html);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test Html::htmlNested() with boolean attributes set.
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
        $html = Html::htmlNested(['tag'  => 'div',
                                  'attr' => [$attribute => $value],
                                  'html' => null]);
        $this->assertSame("<div $attribute=\"on\"></div>", $html);
      }
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test Html::htmlNested() with boolean attributes not set.
   */
  public function testBooleanAttributes6(): void
  {
    $attributes = ['autocomplete'];

    $values = ['0', 0, false, [], ''];

    foreach ($attributes as $attribute)
    {
      foreach ($values as $value)
      {
        $html = Html::htmlNested(['tag'  => 'div',
                                  'attr' => [$attribute => $value],
                                  'html' => null]);
        $this->assertSame("<div $attribute=\"off\"></div>", $html);
      }

      $html = Html::htmlNested(['tag'  => 'div',
                                'attr' => [$attribute => null],
                                'html' => null]);
      $this->assertSame('<div></div>', $html);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test Html::htmlNested() with boolean attributes set.
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
        $html = Html::htmlNested(['tag'  => 'div',
                                  'attr' => [$attribute => $value],
                                  'html' => null]);
        $this->assertSame("<div $attribute=\"yes\"></div>", $html);
      }
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test Html::htmlNested() with boolean attributes not set.
   */
  public function testBooleanAttributes8(): void
  {
    $attributes = ['translate'];

    $values = ['0', 0, false, [], ''];

    foreach ($attributes as $attribute)
    {
      foreach ($values as $value)
      {
        $html = Html::htmlNested(['tag'  => 'div',
                                  'attr' => [$attribute => $value],
                                  'html' => null]);
        $this->assertSame("<div $attribute=\"no\"></div>", $html);
      }

      $html = Html::htmlNested(['tag'  => 'div',
                                'attr' => [$attribute => null],
                                'html' => null]);
      $this->assertSame('<div></div>', $html);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test htmlNested() with void element.
   */
  public function testHtmlNested01(): void
  {
    $html = Html::htmlNested(['tag' => 'br']);
    self::assertSame('<br/>', $html);

    $html = Html::htmlNested(['tag'  => 'img',
                              'attr' => ['src' => '/images/logo.png', 'alt' => 'logo']]);
    $this->assertEquals('<img src="/images/logo.png" alt="logo"/>', $html);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test htmlNested() with an element.
   */
  public function testHtmlNested02(): void
  {
    $html = Html::htmlNested(['tag'  => 'a',
                              'attr' => ['href' => 'https://github.com/PhpPlaisio/helper-html'],
                              'text' => 'helper & html']);
    self::assertSame('<a href="https://github.com/PhpPlaisio/helper-html">helper &amp; html</a>', $html);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test htmlNested() with an element with integer value.
   */
  public function testHtmlNested03(): void
  {
    $html = Html::htmlNested(['tag'  => 'a',
                              'attr' => ['href' => 'https://github.com/PhpPlaisio/helper-html'],
                              'text' => 123]);
    self::assertSame('<a href="https://github.com/PhpPlaisio/helper-html">123</a>', $html);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test htmlNested() with element with HTML value.
   */
  public function testHtmlNested04(): void
  {
    $html = Html::htmlNested(['tag'  => 'a',
                              'attr' => ['href' => 'https://github.com/PhpPlaisio/helper-html'],
                              'html' => '<b>helper-html</b>']);
    self::assertSame('<a href="https://github.com/PhpPlaisio/helper-html"><b>helper-html</b></a>', $html);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test htmlNested() with nested elements.
   */
  public function testHtmlNested05(): void
  {
    $html = Html::htmlNested(['tag'   => 'a',
                              'attr'  => ['href' => 'https://github.com/PhpPlaisio/helper-html'],
                              'inner' => ['tag'  => 'b',
                                          'text' => 'helper-html']]);
    self::assertSame('<a href="https://github.com/PhpPlaisio/helper-html"><b>helper-html</b></a>', $html);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test htmlNested() with list of elements.
   */
  public function testHtmlNested06(): void
  {
    $html = Html::htmlNested([['tag'   => 'a',
                               'attr'  => ['href' => 'https://github.com/PhpPlaisio/helper-html'],
                               'inner' => ['tag'  => 'b',
                                           'text' => 'helper-html']],
                              ['tag' => 'br']]);
    self::assertSame('<a href="https://github.com/PhpPlaisio/helper-html"><b>helper-html</b></a><br/>', $html);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test htmlNested() with list of elements.
   */
  public function testHtmlNested07(): void
  {
    $this->expectException(\LogicException::class);
    Html::htmlNested(['xhtml' => 'xml-html']);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test htmlNested() with null.
   */
  public function testHtmlNested08(): void
  {
    $html = Html::htmlNested(null);

    self::assertSame('', $html);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test htmlNested() with null element.
   */
  public function testHtmlNested09(): void
  {
    $html = Html::htmlNested([null]);

    self::assertSame('', $html);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test htmlNested() with list of elements with null inner.
   */
  public function testHtmlNested10(): void
  {
    $html = Html::htmlNested(['tag'   => 'span',
                              'attr'  => ['class' => 'test'],
                              'inner' => null]);

    self::assertSame('<span class="test"></span>', $html);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test htmlNested() with list of elements.
   */
  public function testHtmlNested11(): void
  {
    $html = Html::htmlNested([['tag'   => 'table',
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

    self::assertSame('<table class="test"><tr id="first-row"><td>hello</td><td class="bold"><b>world</b></td></tr><tr><td>foo</td><td>bar</td></tr><tr id="last-row"><td>foo</td><td>bar</td></tr></table>The End!',
                     $html);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test htmlNested() with empty inner HTML code.
   */
  public function testHtmlNestedEmpty(): void
  {
    $html = Html::htmlNested(['tag'  => 'span',
                              'attr' => ['class' => 'null'],
                              'text' => '']);
    $this->assertEquals('<span class="null"></span>', $html);

    $html = Html::htmlNested(['tag'  => 'span',
                              'attr' => ['class' => 'null'],
                              'text' => null]);
    $this->assertEquals('<span class="null"></span>', $html);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test htmlNested() with empty attributes.
   */
  public function testHtmlNestedEmptyAttributes(): void
  {
    $values = [null, ''];

    foreach ($values as $value)
    {
      $struct = ['tag'  => 'div',
                 'attr' => ['data-test' => $value],
                 'html' => null];
      $html   = Html::htmlNested($struct);
      $this->assertSame('<div></div>', $html);
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test htmlNested() with normal attributes set.
   */
  public function testHtmlNestedNormalAttributes(): void
  {
    $values = ['0', 0, false];

    foreach ($values as $value)
    {
      $struct = ['tag'  => 'div',
                 'attr' => ['data-test' => $value],
                 'html' => null];
      $html   = Html::htmlNested($struct);
      $this->assertSame('<div data-test="0"></div>', $html);
    }

    $struct = ['tag'  => 'div',
               'attr' => ['qwerty&?<' => "<a>&"],
               'html' => null];
    $html   = Html::htmlNested($struct);
    $this->assertSame('<div qwerty&amp;?&lt;="&lt;a&gt;&amp;"></div>', $html);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Invalid tests for method txt2html().
   *
   * @param mixed $value The value.
   */
  #[DataProvider('casesInvalidTxt2Html')]
  public function testInvalidTxt2Html(mixed $value)
  {
    $this->expectException(FallenException::class);
    Html::txt2Html($value);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test cases for txt2Slug().
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
   * Valid tests for method txt2html().
   *
   * @param mixed  $value    The value.
   * @param string $expected The expected generated HTML code.
   */
  #[DataProvider('casesValidTxt2Html')]
  public function testValidTxt2Html(mixed $value, string $expected)
  {
    $html = Html::txt2Html($value);
    $this->assertSame($expected, $html);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------


