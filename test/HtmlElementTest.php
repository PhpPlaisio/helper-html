<?php
declare(strict_types=1);

namespace SetBased\Abc\Test;

use PHPUnit\Framework\TestCase;
use SetBased\Exception\LogicException;

/**
 * Test cases for class HtmlElement.
 */
class HtmlElementTest extends TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test fake attributes.
   */
  public function testFakeAttribute1(): void
  {
    $element = new TestElement();
    $uuid    = uniqid();
    $element->setFakeAttribute('_fake', $uuid);

    // Fake attributes must not end up in generated HTML code.
    $html = $element->generateElement();
    self::assertStringNotContainsString('_fake', $html);

    // But attribute must be set.
    self::assertSame($uuid, $element->getAttribute('_fake'));
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test fake attributes.
   */
  public function testFakeAttribute2(): void
  {
    $this->expectException(LogicException::class);

    $element = new TestElement();
    $uuid    = uniqid();
    $element->setFakeAttribute('not_fake', $uuid);
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function testSetAttrAria(): void
  {
    $element = new TestElement();
    $element->setAttrAria('rowspan', '5');
    $html = $element->generateElement();

    $doc = new \DOMDocument();
    $doc->loadXML($html);
    $xpath = new \DOMXpath($doc);

    // Test attribute is present.
    $list = $xpath->query("/test[@aria-rowspan='5']");
    self::assertEquals(1, $list->length, 'html');

    self::assertSame('5', $element->getAttribute('aria-rowspan'), 'getAttribute');
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function testSetAttrClass(): void
  {
    $element = new TestElement();

    $element->addClass('hello');
    $html = $element->generateElement();

    $doc = new \DOMDocument();
    $doc->loadXML($html);
    $xpath = new \DOMXpath($doc);
    $list  = $xpath->query("/test[@class='hello']");
    self::assertEquals(1, $list->length, "assert 1");

    // Calling addClass adds another class.
    $element->addClass('world');
    $html = $element->generateElement();

    $doc = new \DOMDocument();
    $doc->loadXML($html);
    $xpath = new \DOMXpath($doc);
    $list  = $xpath->query("/test[@class='hello world']");
    self::assertEquals(1, $list->length, "assert 2");

    // Remove a class.
    $element->removeClass('hello');
    $html = $element->generateElement();

    $doc = new \DOMDocument();
    $doc->loadXML($html);
    $xpath = new \DOMXpath($doc);
    $list  = $xpath->query("/test[@class='world']");
    self::assertEquals(1, $list->length, "assert 3");

    // Call unsetClass resets class.
    $element->unsetClass();
    $html = $element->generateElement();
    self::assertStringNotContainsString('class', $html, "assert 4");

    // setClass must override previous set classes.
    $element->addClass('class1');
    $element->setAttrClass('class2');
    $html = $element->generateElement();
    self::assertStringNotContainsString('class1', $html, "assert 5");
    $html = $element->generateElement();
    self::assertStringContainsString('class2', $html, "assert 6");
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Tests methods addClass and removeClass with zeros.
   *
   * @param string $class The class.
   *
   * @dataProvider zeros
   */
  public function testSetAttrClassWithZeros(string $class): void
  {
    $element = new TestElement();

    // Add the class.
    $element->addClass($class);
    $html = $element->generateElement();

    $doc = new \DOMDocument();
    $doc->loadXML($html);
    $xpath = new \DOMXpath($doc);
    $list  = $xpath->query("/test[@class='$class']");
    self::assertSame(1, $list->length);

    // Remove the class.
    $element->removeClass($class);
    $html = $element->generateElement();

    $doc = new \DOMDocument();
    $doc->loadXML($html);
    $xpath = new \DOMXpath($doc);
    $list  = $xpath->query("/test[not(@class)]");
    self::assertSame(1, $list->length);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Tests methods addClass and removeClass with null.
   *
   * @dataProvider zeros
   */
  public function testSetAttrClassWithNull(): void
  {
    $element = new TestElement();

    // Add the class.
    $element->addClass(null);
    $html = $element->generateElement();

    $doc = new \DOMDocument();
    $doc->loadXML($html);
    $xpath = new \DOMXpath($doc);
    $list  = $xpath->query("/test[not(@class)]");
    self::assertSame(1, $list->length);

    // Remove the class.
    $element->removeClass(null);
    $html = $element->generateElement();

    $doc = new \DOMDocument();
    $doc->loadXML($html);
    $xpath = new \DOMXpath($doc);
    $list  = $xpath->query("/test[not(@class)]");
    self::assertSame(1, $list->length);
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function testSetAttrData(): void
  {
    $element = new TestElement();
    $uuid    = uniqid();
    $element->setAttrData('test', $uuid);
    $html = $element->generateElement();

    $doc = new \DOMDocument();
    $doc->loadXML($html);
    $xpath = new \DOMXpath($doc);

    // Test attribute is present.
    $list = $xpath->query("/test[@data-test='$uuid']");
    self::assertEquals(1, $list->length, 'html');

    self::assertSame($uuid, $element->getAttribute('data-test'), 'getAttribute');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test all setAttr* methods.
   */
  public function testSetAttribute(): void
  {
    $methods = ['setAttrAccessKey'       => 'accesskey',
                'setAttrContentEditable' => 'contenteditable',
                'setAttrContextMenu'     => 'contextmenu',
                'setAttrDir'             => 'dir',
                'setAttrDraggable'       => 'draggable',
                'setAttrDropZone'        => 'dropzone',
                'setAttrHidden'          => 'hidden',
                'setAttrId'              => 'id',
                'setAttrLang'            => 'lang',
                'setAttrRole'            => 'role',
                'setAttrSpellCheck'      => 'spellcheck',
                'setAttrStyle'           => 'style',
                'setAttrTabIndex'        => 'tabindex',
                'setAttrTitle'           => 'title',
                'setAttrTranslate'       => 'translate'];

    $element = new TestElement();
    foreach ($methods as $method => $attribute)
    {
      $uuid = ($attribute!='tabindex') ? uniqid() : rand(1, 123);
      $element->$method($uuid);
      $html = $element->generateElement();

      $doc = new \DOMDocument();
      $doc->loadXML($html);
      $xpath = new \DOMXpath($doc);

      // Test attribute is present.
      $list = $xpath->query("/test[@$attribute='$uuid' or @$attribute='true'or @$attribute='yes'or @$attribute='$attribute']");
      self::assertEquals(1, $list->length, "Method: $method");

      self::assertEquals($uuid, $element->getAttribute($attribute), "Attribute: $attribute");
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns non-empty string the are equal to empty string.
   *
   * @return array
   */
  public function zeros(): array
  {
    $cases = [];

    $cases[] = ['class' => '0'];
    $cases[] = ['class' => '0.0'];

    return $cases;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
