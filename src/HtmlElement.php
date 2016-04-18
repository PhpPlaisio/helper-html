<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc;

use SetBased\Exception\LogicException;

//----------------------------------------------------------------------------------------------------------------------
/**
 * Parent class for HTML elements.
 *
 * This class should be used for generation "heavy" HTML elements only. For light weight elements use methods of
 * {@link \SetBased\Abc\Helper\Html}.
 *
 * #### Global Attributes
 * This class defines methods for getting attributes and setting
 * [global attributes](http://www.w3schools.com/tags/ref_standardattributes.asp) only.
 *
 * Unless stated otherwise setting an attribute to null, false, or '' will unset the attribute.
 *
 * #### Event Attributes
 * This class does not define methods for setting event attributes. Events handlers must be set with JavaScript.
 */
class HtmlElement
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The attributes of this HTML element.
   *
   * @var array
   */
  protected $attributes = [];

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adds a class to the list of classes.
   *
   * @param string $class The class.
   */
  public function addClass($class)
  {
    // If class is empty return immediately.
    if ($class=='') return;

    if (isset($this->attributes['class']))
    {
      $this->attributes['class'] .= ' ';
      $this->attributes['class'] .= $class;
    }
    else
    {
      $this->attributes['class'] = $class;
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the value of an attribute.
   *
   * @param string $attributeName The name of the attribute.
   *
   * @return mixed
   */
  public function getAttribute($attributeName)
  {
    return (isset($this->attributes[$attributeName])) ? $this->attributes[$attributeName] : null;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Removes a class from the list of classes.
   *
   * @param string $class The class to be removed.
   */
  public function removeClass($class)
  {
    // If class is empty or no classes are set return immediately.
    if ($class=='' || !isset($this->attributes['class'])) return;

    // Remove the class from the list of classes.
    $this->attributes['class'] = implode(' ', array_diff(explode(' ', $this->attributes['class']), [$class]));
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [accesskey](http://www.w3schools.com/tags/att_global_accesskey.asp).
   *
   * @param string $value The attribute value.
   */
  public function setAttrAccessKey($value)
  {
    $this->attributes['accesskey'] = $value;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [contenteditable](http://www.w3schools.com/tags/att_global_contenteditable.asp).
   * * Any value that evaluates to true will set the attribute to 'true'.
   * * Any value that evaluates to false will set the attribute to 'false'.
   * * Null will unset the attribute.
   *
   *
   * @param mixed $value The attribute value.
   */
  public function setAttrContentEditable($value)
  {
    $this->attributes['contenteditable'] = $value;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [contextmenu](http://www.w3schools.com/tags/att_global_contextmenu.asp).
   *
   * @param string $value The attribute value.
   */
  public function setAttrContextMenu($value)
  {
    $this->attributes['contextmenu'] = $value;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets a [data](http://www.w3schools.com/tags/att_global_data.asp) attribute.
   *
   * @param string $name  The name of the attribute (without 'data-').
   * @param string $value The attribute value.
   */
  public function setAttrData($name, $value)
  {
    $this->attributes['data-'.$name] = $value;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [dir](http://www.w3schools.com/tags/att_global_dir.asp). Possible values:
   * * ltr
   * * rtl
   * * auto
   *
   * @param string $value The attribute value.
   */
  public function setAttrDir($value)
  {
    $this->attributes['dir'] = $value;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [draggable](http://www.w3schools.com/tags/att_global_draggable.asp). Possible values:
   * * true
   * * false
   * * auto
   *
   * @param string $value The attribute value.
   */
  public function setAttrDraggable($value)
  {
    $this->attributes['draggable'] = $value;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [dropzone](http://www.w3schools.com/tags/att_global_dropzone.asp).
   *
   * @param string $value The attribute value.
   */
  public function setAttrDropZone($value)
  {
    $this->attributes['dropzone'] = $value;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [hidden](http://www.w3schools.com/tags/att_global_hidden.asp).
   * This is a boolean attribute. Any none [empty](http://php.net/manual/function.empty.php) value will set the
   * attribute to 'hidden'. Any other value will unset the attribute.
   *
   * @param mixed $value The attribute value.
   */
  public function setAttrHidden($value)
  {
    $this->attributes['hidden'] = $value;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [id](http://www.w3schools.com/tags/att_global_id.asp).
   *
   * @param string $value The attribute value.
   */
  public function setAttrId($value)
  {
    $this->attributes['id'] = $value;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [lang](http://www.w3schools.com/tags/att_global_lang.asp).
   *
   * @param string $value The attribute value.
   */
  public function setAttrLang($value)
  {
    $this->attributes['lang'] = $value;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [spellcheck](http://www.w3schools.com/tags/att_global_spellcheck.asp).
   * * Any value that evaluates to true will set the attribute to 'true'.
   * * Any value that evaluates to false will set the attribute to 'false'.
   * * Null will unset the attribute.
   *
   * @param string $value The attribute value.
   */
  public function setAttrSpellCheck($value)
  {
    $this->attributes['spellcheck'] = $value;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [style](http://www.w3schools.com/tags/att_global_style.asp)
   *
   * @param string $value The attribute value.
   */
  public function setAttrStyle($value)
  {
    $this->attributes['style'] = $value;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [tabindex](http://www.w3schools.com/tags/att_global_tabindex.asp).
   *
   * @param int $value The attribute value.
   */
  public function setAttrTabIndex($value)
  {
    $this->attributes['tabindex'] = $value;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [title](http://www.w3schools.com/tags/att_global_title.asp).
   *
   * @param string $value The attribute value.
   */
  public function setAttrTitle($value)
  {
    $this->attributes['title'] = $value;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [translate](http://www.w3schools.com/tags/att_global_translate.asp).
   * * Any value that evaluates to true will set the attribute to 'yes'.
   * * Any value that evaluates to false will set the attribute to 'no'.
   * * Null will unset the attribute.
   *
   * @param mixed $value The attribute value.
   */
  public function setAttrTranslate($value)
  {
    $this->attributes['translate'] = $value;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets a fake attribute. A fake attribute has a name that starts with an underscore. Fake attributes will not be
   * included in the generated HTML code.
   *
   * @param string $name  The name of the fake attribute.
   * @param mixed  $value The value of the fake attribute.
   */
  public function setFakeAttribute($name, $value)
  {
    if (strpos($name, '_')!==0)
    {
      throw new LogicException("Attribute '%s' is not a valid fake attribute.", $name);
    }

    $this->attributes[$name] = $value;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Removes all classes for the list of classes.
   */
  public function unsetClass()
  {
    unset($this->attributes['class']);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
