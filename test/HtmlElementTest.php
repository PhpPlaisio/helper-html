<?php
declare(strict_types=1);

namespace Plaisio\Helper\Test;

use PHPUnit\Framework\TestCase;

/**
 * Test cases for class HtmlElement.
 */
class HtmlElementTest extends TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test method setAttrContentEditable().
   */
  public function setAttrData(): void
  {
    $uuid    = uniqid();
    $element = new TestElement();
    $html    = $element->setAttrData('test', $uuid)
                       ->generateElement();

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
   *  Works when class is a string.
   */
  public function testAddClass1(): void
  {
    $element = new TestElement();
    $html    = $element->setAttribute('class', 'string')
                       ->addClass('my-class')
                       ->generateElement();

    $doc = new \DOMDocument();
    $doc->loadXML($html);
    $xpath = new \DOMXpath($doc);

    $list = $xpath->query("/test[1]/@class");
    self::assertSame(1, $list->length);
    self::assertSame('my-class string', $list->item(0)->nodeValue);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test for method addClasses().
   */
  public function testAddClasses1(): void
  {
    $element = new TestElement();
    $html    = $element->addClasses(['first', 'last'])
                       ->generateElement();

    $doc = new \DOMDocument();
    $doc->loadXML($html);
    $xpath = new \DOMXpath($doc);

    $list = $xpath->query("/test[1]/@class");
    self::assertSame(1, $list->length);
    self::assertSame('first last', $list->item(0)->nodeValue);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test for method addClasses(). Array keys are ignored.
   */
  public function testAddClasses2(): void
  {
    $element = new TestElement();
    $html    = $element->addClasses(['one' => 'first', 'two' => 'last'])
                       ->addClasses(['one' => 'one', 'two' => 'two'])
                       ->generateElement();

    $doc = new \DOMDocument();
    $doc->loadXML($html);
    $xpath = new \DOMXpath($doc);

    $list = $xpath->query("/test[1]/@class");
    self::assertSame(1, $list->length);
    self::assertSame('first last one two', $list->item(0)->nodeValue);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test for method addClasses(). Classes are sorted.
   */
  public function testAddClasses3(): void
  {
    $element = new TestElement();
    $html    = $element->addClasses(['z', 'a'])
                       ->addClass('k')
                       ->generateElement();

    $doc = new \DOMDocument();
    $doc->loadXML($html);
    $xpath = new \DOMXpath($doc);

    $list = $xpath->query("/test[1]/@class");
    self::assertSame(1, $list->length);
    self::assertSame('a k z', $list->item(0)->nodeValue);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test for method addClasses(). Works when class is a string.
   */
  public function testAddClasses4(): void
  {
    $element = new TestElement();
    $html    = $element->setAttribute('class', 'string')
                       ->addClasses(['my-class'])
                       ->generateElement();

    $doc = new \DOMDocument();
    $doc->loadXML($html);
    $xpath = new \DOMXpath($doc);

    $list = $xpath->query("/test[1]/@class");
    self::assertSame(1, $list->length);
    self::assertSame('my-class string', $list->item(0)->nodeValue);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test for method getId().
   */
  public function testGetId(): void
  {
    $element1 = new TestElement();
    $id       = $element1->getId();
    self::assertIsString($id);

    $element2 = new TestElement();
    $element2->setAttrId('is-me');
    $id = $element2->getId();
    self::assertSame($id, 'is-me');
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

    $html = $element->addClass('hello')
                    ->generateElement();

    $doc = new \DOMDocument();
    $doc->loadXML($html);
    $xpath = new \DOMXpath($doc);
    $list  = $xpath->query("/test[@class='hello']");
    self::assertEquals(1, $list->length, "assert 1");

    // Calling addClass adds another class.
    $html = $element->addClass('world')
                    ->generateElement();

    $doc = new \DOMDocument();
    $doc->loadXML($html);
    $xpath = new \DOMXpath($doc);
    $list  = $xpath->query("/test[@class='hello world']");
    self::assertEquals(1, $list->length, "assert 2");

    // Remove a class.
    $html = $element->removeClass('hello')
                    ->generateElement();

    $doc = new \DOMDocument();
    $doc->loadXML($html);
    $xpath = new \DOMXpath($doc);
    $list  = $xpath->query("/test[@class='world']");
    self::assertEquals(1, $list->length, "assert 3");

    // Call unsetClass resets class.
    $html = $element->unsetClass()
                    ->generateElement();
    self::assertStringNotContainsString('class', $html, "assert 4");

    // setClass must override previous set classes.
    $html = $element->addClass('class1')
                    ->setAttrClass('class2')
                    ->generateElement();
    self::assertStringNotContainsString('class1', $html, "assert 5");
    $html = $element->generateElement();
    self::assertStringContainsString('class2', $html, "assert 6");
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets class.
   */
  public function testSetAttrClass1(): void
  {
    $element = new TestElement();
    $html    = $element->setAttrClass('my-class')
                       ->generateElement();

    $doc = new \DOMDocument();
    $doc->loadXML($html);
    $xpath = new \DOMXpath($doc);

    $list = $xpath->query("/test[1]/@class");
    self::assertSame(1, $list->length);
    self::assertSame('my-class', $list->item(0)->nodeValue);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets class and overwrites the value.
   */
  public function testSetAttrClass2(): void
  {
    $element = new TestElement();
    $html    = $element->setAttrClass('old-class')
                       ->setAttrClass('new-class')
                       ->generateElement();

    $doc = new \DOMDocument();
    $doc->loadXML($html);
    $xpath = new \DOMXpath($doc);

    $list = $xpath->query("/test[1]/@class");
    self::assertSame(1, $list->length);
    self::assertSame('new-class', $list->item(0)->nodeValue);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets class and overwrites the value.
   */
  public function testSetAttrClass3(): void
  {
    $element = new TestElement();
    $element->setAttrClass('class')
            ->setAttrClass(null);

    self::assertArrayNotHasKey('class', $element->getAttributes());

    $element = new TestElement();
    $element->setAttrClass('class')
            ->setAttrClass('');

    self::assertArrayNotHasKey('class', $element->getAttributes());
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
    $html = $element->addClass(null)
                    ->generateElement();

    $doc = new \DOMDocument();
    $doc->loadXML($html);
    $xpath = new \DOMXpath($doc);
    $list  = $xpath->query("/test[not(@class)]");
    self::assertSame(1, $list->length);

    // Remove the class.
    $html = $element->removeClass(null)
                    ->generateElement();

    $doc = new \DOMDocument();
    $doc->loadXML($html);
    $xpath = new \DOMXpath($doc);
    $list  = $xpath->query("/test[not(@class)]");
    self::assertSame(1, $list->length);
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
    $html = $element->addClass($class)
                    ->generateElement();

    $doc = new \DOMDocument();
    $doc->loadXML($html);
    $xpath = new \DOMXpath($doc);
    $list  = $xpath->query("/test[@class='$class']");
    self::assertSame(1, $list->length);

    // Remove the class.
    $html = $element->removeClass($class)
                    ->generateElement();

    $doc = new \DOMDocument();
    $doc->loadXML($html);
    $xpath = new \DOMXpath($doc);
    $list  = $xpath->query("/test[not(@class)]");
    self::assertSame(1, $list->length);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test method setAttrContentEditable().
   */
  public function testSetAttrContentEditable(): void
  {
    $element = new TestElement();

    $html = $element->setAttrContentEditable(false)->generateElement();
    self::assertEquals('<test contenteditable="false"></test>', $html);

    $html = $element->setAttrContentEditable(true)->generateElement();
    self::assertEquals('<test contenteditable="true"></test>', $html);

    $html = $element->setAttrContentEditable(null)->generateElement();
    self::assertEquals('<test></test>', $html);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test method setAttrDraggable().
   */
  public function testSetAttrDraggable(): void
  {
    $element = new TestElement();

    $html = $element->setAttrDraggable('true')->generateElement();
    self::assertEquals('<test draggable="true"></test>', $html);

    $html = $element->setAttrDraggable('false')->generateElement();
    self::assertEquals('<test draggable="false"></test>', $html);

    $html = $element->setAttrDraggable('auto')->generateElement();
    self::assertEquals('<test draggable="auto"></test>', $html);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test method setAttrHidden().
   */
  public function testSetAttrHidden(): void
  {
    $element = new TestElement();

    $html = $element->setAttrHidden(false)->generateElement();
    self::assertEquals('<test></test>', $html);

    $html = $element->setAttrHidden(true)->generateElement();
    self::assertEquals('<test hidden="hidden"></test>', $html);

    $html = $element->setAttrHidden(null)->generateElement();
    self::assertEquals('<test></test>', $html);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test method setAttrSpellCheck().
   */
  public function testSetAttrSpellCheck(): void
  {
    $element = new TestElement();

    $html = $element->setAttrSpellCheck(false)->generateElement();
    self::assertEquals('<test></test>', $html);

    $html = $element->setAttrSpellCheck(true)->generateElement();
    self::assertEquals('<test spellcheck="spellcheck"></test>', $html);

    $html = $element->setAttrSpellCheck(null)->generateElement();
    self::assertEquals('<test></test>', $html);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test method setAttrTranslate().
   */
  public function testSetAttrTranslate(): void
  {
    $element = new TestElement();

    $html = $element->setAttrTranslate(false)->generateElement();
    self::assertEquals('<test translate="no"></test>', $html);

    $html = $element->setAttrTranslate(true)->generateElement();
    self::assertEquals('<test translate="yes"></test>', $html);

    $html = $element->setAttrTranslate(null)->generateElement();
    self::assertEquals('<test></test>', $html);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test all setAttr* methods.
   */
  public function testSetAttribute(): void
  {
    $methods = ['setAttrAccessKey'   => 'accesskey',
                'setAttrContextMenu' => 'contextmenu',
                'setAttrDir'         => 'dir',
                'setAttrDropZone'    => 'dropzone',
                'setAttrId'          => 'id',
                'setAttrLang'        => 'lang',
                'setAttrRole'        => 'role',
                'setAttrStyle'       => 'style',
                'setAttrTabIndex'    => 'tabindex',
                'setAttrTitle'       => 'title'];

    $element = new TestElement();
    foreach ($methods as $method => $attribute)
    {
      $uuid = ($attribute!=='tabindex') ? uniqid() : rand(1, 123);
      $html = $element->$method($uuid)->generateElement();

      $doc = new \DOMDocument();
      $doc->loadXML($html);
      $xpath = new \DOMXpath($doc);

      // Test attribute is present.
      $list = $xpath->query("/test[@$attribute='$uuid']");
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
    return [['class' => '0'],
            ['class' => '0.0']];
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
