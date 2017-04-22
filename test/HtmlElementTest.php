<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Test;

use PHPUnit\Framework\TestCase;
use SetBased\Exception\LogicException;

//----------------------------------------------------------------------------------------------------------------------
class HtmlElementTest extends TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test all setAttr* methods.
   */
  public function testSetAttribute()
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
      $uuid = uniqid();
      $element->$method($uuid);
      $html = $element->generateElement();

      $doc = new \DOMDocument();
      $doc->loadXML($html);
      $xpath = new \DOMXpath($doc);

      // Test attribute is present.
      $list = $xpath->query("/test[@$attribute='$uuid' or @$attribute='true'or @$attribute='yes'or @$attribute='$attribute']");
      $this->assertEquals(1, $list->length, "Method: $method");

      $this->assertEquals($uuid, $element->getAttribute($attribute), "Attribute: $attribute");
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function testSetAttrClass()
  {
    $element = new TestElement();

     $element->addClass('hello');
    $html = $element->generateElement();

    $doc = new \DOMDocument();
    $doc->loadXML($html);
    $xpath = new \DOMXpath($doc);
    $list = $xpath->query("/test[@class='hello']");
    $this->assertEquals(1, $list->length, "assert 1");

    // Calling addClass adds another class.
    $element->addClass('world');
    $html = $element->generateElement();

    $doc = new \DOMDocument();
    $doc->loadXML($html);
    $xpath = new \DOMXpath($doc);
    $list = $xpath->query("/test[@class='hello world']");
    $this->assertEquals(1, $list->length, "assert 2");

    // Remove a class.
    $element->removeClass('hello');
    $html = $element->generateElement();

    $doc = new \DOMDocument();
    $doc->loadXML($html);
    $xpath = new \DOMXpath($doc);
    $list = $xpath->query("/test[@class='world']");
    $this->assertEquals(1, $list->length, "assert 3");

    // Call unsetClass resets class.
    $element->unsetClass();
    $html = $element->generateElement();
    $this->assertNotContains('class', $html, "assert 4");
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function testSetAttrData()
  {
    $element = new TestElement();
    $uuid = uniqid();
    $element->setAttrData('test', $uuid);
    $html = $element->generateElement();

    $doc = new \DOMDocument();
    $doc->loadXML($html);
    $xpath = new \DOMXpath($doc);

    // Test attribute is present.
    $list = $xpath->query("/test[@data-test='$uuid']");
    $this->assertEquals(1, $list->length, 'html');

    $this->assertSame($uuid, $element->getAttribute('data-test'), 'getAttribute');
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function testSetAttrAria()
  {
    $element = new TestElement();
    $element->setAttrAria('rowspan', 5);
    $html = $element->generateElement();

    $doc = new \DOMDocument();
    $doc->loadXML($html);
    $xpath = new \DOMXpath($doc);

    // Test attribute is present.
    $list = $xpath->query("/test[@aria-rowspan='5']");
    $this->assertEquals(1, $list->length, 'html');

    $this->assertSame(5, $element->getAttribute('aria-rowspan'), 'getAttribute');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test fake attributes.
   */
  public function testFakeAttribute1()
  {
    $element = new TestElement();
    $uuid = uniqid();
    $element->setFakeAttribute('_fake', $uuid);

    // Fake attributes must not end up in generated HTML code.
    $html = $element->generateElement();
    $this->assertNotContains('_fake', $html);

    // But attribute must be set.
    $this->assertSame($uuid, $element->getAttribute('_fake'));
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test fake attributes.
   *
   * @expectedException LogicException
   */
  public function testFakeAttribute2()
  {
    $element = new TestElement();
    $uuid = uniqid();
    $element->setFakeAttribute('not_fake', $uuid);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
