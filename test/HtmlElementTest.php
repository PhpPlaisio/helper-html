<?php

//----------------------------------------------------------------------------------------------------------------------
use SetBased\Exception\LogicException;

class HtmlElementTest extends PHPUnit_Framework_TestCase
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

      $doc = new DOMDocument();
      $doc->loadXML($html);
      $xpath = new DOMXpath($doc);

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

    $uuid1 = uniqid();
    $element->setAttrClass($uuid1);
    $html = $element->generateElement();

    $doc = new DOMDocument();
    $doc->loadXML($html);
    $xpath = new DOMXpath($doc);
    $list = $xpath->query("/test[@class='$uuid1']");
    $this->assertEquals(1, $list->length, "assert 1");

    // Calling setAttrClass adds another class.
    $uuid2 = uniqid();
    $element->setAttrClass($uuid2);
    $html = $element->generateElement();

    $doc = new DOMDocument();
    $doc->loadXML($html);
    $xpath = new DOMXpath($doc);
    $list = $xpath->query("/test[@class='$uuid1 $uuid2']");
    $this->assertEquals(1, $list->length, "assert 2");

    // Call setAttrClass with null resets the class.
    $element->setAttrClass(null);
    $html = $element->generateElement();
    $this->assertNotContains('class', $html, "assert 3");
  }

  //--------------------------------------------------------------------------------------------------------------------
  public function testSetAttrData()
  {
    $element = new TestElement();
    $uuid = uniqid();
    $element->setAttrData('test', $uuid);
    $html = $element->generateElement();

    $doc = new DOMDocument();
    $doc->loadXML($html);
    $xpath = new DOMXpath($doc);

    // Test attribute is present.
    $list = $xpath->query("/test[@data-test='$uuid']");
    $this->assertEquals(1, $list->length, 'html');

    $this->assertSame($uuid, $element->getAttribute('data-test'), 'getAttribute');
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
