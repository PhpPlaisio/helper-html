<?php
declare(strict_types=1);

namespace Plaisio\Helper;

/**
 * Trait for HTML elements.
 *
 * This class should be used for generation "heavy" HTML elements only. For lightweight elements use methods of
 * {@link \Plaisio\Helper\Html}.
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
trait HtmlElement
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The attributes of this HTML element.
   *
   * @var array
   *
   * @since 1.0.0
   * @api
   */
  protected array $attributes = [];

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adds a class to the list of classes.
   *
   * @param string|null $class The class.
   *
   * @return $this
   *
   * @since 1.0.0
   * @api
   */
  public function addClass(?string $class): self
  {
    if ($class===null || $class==='')
    {
      return $this;
    }

    if (empty($this->attributes['class']))
    {
      $this->attributes['class'] = [];
    }
    elseif (is_string($this->attributes['class']))
    {
      $this->attributes['class'] = explode(' ', $this->attributes['class']);
    }

    $this->attributes['class'][] = $class;

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Adds classes to the list of classes.
   *
   * @param array $classes The classes.
   *
   * @return $this
   *
   * @since 1.5.0
   * @api
   */
  public function addClasses(array $classes): self
  {
    if (empty($this->attributes['class']))
    {
      $this->attributes['class'] = [];
    }
    elseif (is_string($this->attributes['class']))
    {
      $this->attributes['class'] = explode(' ', $this->attributes['class']);
    }

    foreach ($classes as $class)
    {
      $this->attributes['class'][] = $class;
    }

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the value of an attribute.
   *
   * @param string $attributeName The name of the attribute.
   *
   * @return mixed
   *
   * @since 1.0.0
   * @api
   */
  public function getAttribute(string $attributeName)
  {
    return $this->attributes[$attributeName] ?? null;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the attributes of this HTML element.
   *
   * @return array
   */
  public function getAttributes(): array
  {
    return $this->attributes;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the mandatory attribute [id](http://www.w3schools.com/tags/att_global_id.asp) of this element. If the id
   * attribute was not set prior calling this method the id attribute will set with an automatically generate value.
   *
   * @return string
   *
   * @since 1.4.0
   * @api
   */
  public function getId(): string
  {
    $this->attributes['id'] ??= Html::getAutoId();

    return $this->attributes['id'];
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Removes a class from the list of classes.
   *
   * @param string|null $class The class to be removed.
   *
   * @return $this
   *
   * @since 1.0.0
   * @api
   */
  public function removeClass(?string $class): self
  {
    // If class is empty or no classes are set return immediately.
    if ($class===null || $class==='' || !isset($this->attributes['class'])) return $this;

    $this->attributes['class'] = array_unique($this->attributes['class']);
    $key                       = array_search($class, $this->attributes['class']);
    if ($key!==false)
    {
      unset($this->attributes['class'][$key]);
    }

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [accesskey](http://www.w3schools.com/tags/att_global_accesskey.asp).
   *
   * @param string|null $value The attribute value.
   *
   * @return $this
   *
   * @api
   * @since 1.0.0
   */
  public function setAttrAccessKey(?string $value): self
  {
    $this->attributes['accesskey'] = $value;

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets a [aria](http://w3c.github.io/html/infrastructure.html#element-attrdef-aria-aria) attribute.
   *
   * @param string      $name  The name of the attribute (without 'aria-').
   * @param string|null $value The attribute value.
   *
   * @return $this
   *
   * @since 1.3.0
   * @api
   */
  public function setAttrAria(string $name, ?string $value): self
  {
    $this->attributes['aria-'.$name] = $value;

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [class](https://www.w3schools.com/tags/att_global_class.asp).
   *
   * @param string|null $value The class or classes. Any value set by {@link addClass} will be overwritten.
   *
   * @return $this
   *
   * @since 1.4.0
   * @api
   */
  public function setAttrClass(?string $value): self
  {
    if ($value===null || $value==='')
    {
      unset($this->attributes['class']);
    }
    else
    {
      $this->attributes['class'] = [$value];
    }

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [contenteditable](http://www.w3schools.com/tags/att_global_contenteditable.asp).
   * <ul>
   * <li> True will set the attribute to 'true'.
   * <li> False will set the attribute to 'false'.
   * <li> Null will unset the attribute.
   * </ul>
   *
   * @param bool|null $value The attribute value.
   *
   * @return $this
   *
   * @since 1.0.0
   * @api
   */
  public function setAttrContentEditable(?bool $value): self
  {
    $this->attributes['contenteditable'] = $value;

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [contextmenu](http://www.w3schools.com/tags/att_global_contextmenu.asp).
   *
   * @param string|null $value The attribute value.
   *
   * @return $this
   *
   * @since 1.0.0
   * @api
   */
  public function setAttrContextMenu(?string $value): self
  {
    $this->attributes['contextmenu'] = $value;

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets a [data](http://www.w3schools.com/tags/att_global_data.asp) attribute.
   *
   * @param string      $name  The name of the attribute (without 'data-').
   * @param string|null $value The attribute value.
   *
   * @return $this
   *
   * @since 1.0.0
   * @api
   */
  public function setAttrData(string $name, ?string $value): self
  {
    $this->attributes['data-'.$name] = $value;

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [dir](http://www.w3schools.com/tags/att_global_dir.asp). Possible values:
   * <ul>
   * <li> ltr
   * <li> rtl
   * <li> auto
   * </ul>
   *
   * @param string|null $value The attribute value.
   *
   * @return $this
   *
   * @since 1.0.0
   * @api
   */
  public function setAttrDir(?string $value): self
  {
    $this->attributes['dir'] = $value;

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [draggable](http://www.w3schools.com/tags/att_global_draggable.asp). Possible values:
   * <ul>
   * <li> true
   * <li> false
   * <li> auto
   * </ul>
   *
   * @param string|null $value The attribute value.
   *
   * @return $this
   *
   * @since 1.0.0
   * @api
   */
  public function setAttrDraggable(?string $value): self
  {
    $this->attributes['draggable'] = $value;

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [dropzone](http://www.w3schools.com/tags/att_global_dropzone.asp).
   *
   * @param string|null $value The attribute value.
   *
   * @return $this
   *
   * @since 1.0.0
   * @api
   */
  public function setAttrDropZone(?string $value): self
  {
    $this->attributes['dropzone'] = $value;

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [hidden](http://www.w3schools.com/tags/att_global_hidden.asp).
   *
   * @param ?bool $value The attribute value.
   *
   * @return $this
   *
   * @since 1.0.0
   * @api
   */
  public function setAttrHidden(?bool $value): self
  {
    $this->attributes['hidden'] = $value;

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [id](http://www.w3schools.com/tags/att_global_id.asp).
   *
   * @param string|null $value The attribute value.
   *
   * @return $this
   *
   * @since 1.0.0
   * @api
   */
  public function setAttrId(?string $value): self
  {
    $this->attributes['id'] = $value;

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [lang](http://www.w3schools.com/tags/att_global_lang.asp).
   *
   * @param string|null $value The attribute value.
   *
   * @return $this
   *
   * @since 1.0.0
   * @api
   */
  public function setAttrLang(?string $value): self
  {
    $this->attributes['lang'] = $value;

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [role](http://w3c.github.io/html/infrastructure.html#element-attrdef-aria-role).
   *
   * @param string|null $value The attribute value.
   *
   * @return $this
   *
   * @since 1.3.0
   * @api
   */
  public function setAttrRole(?string $value): self
  {
    $this->attributes['role'] = $value;

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [spellcheck](http://www.w3schools.com/tags/att_global_spellcheck.asp).
   *
   * @param bool|null $value The attribute value.
   *
   * @return $this
   *
   * @since 1.0.0
   * @api
   */
  public function setAttrSpellCheck(?bool $value): self
  {
    $this->attributes['spellcheck'] = $value;

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [style](http://www.w3schools.com/tags/att_global_style.asp)
   *
   * @param string|null $value The attribute value.
   *
   * @return $this
   *
   * @since 1.0.0
   * @api
   */
  public function setAttrStyle(?string $value): self
  {
    $this->attributes['style'] = $value;

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [tabindex](http://www.w3schools.com/tags/att_global_tabindex.asp).
   *
   * @param int|null $value The attribute value.
   *
   * @return $this
   *
   * @since 1.0.0
   * @api
   */
  public function setAttrTabIndex(?int $value): self
  {
    $this->attributes['tabindex'] = $value;

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [title](http://www.w3schools.com/tags/att_global_title.asp).
   *
   * @param string|null $value The attribute value.
   *
   * @return $this
   *
   * @since 1.0.0
   * @api
   */
  public function setAttrTitle(?string $value): self
  {
    $this->attributes['title'] = $value;

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets the attribute [translate](http://www.w3schools.com/tags/att_global_translate.asp).
   * <ul>
   * <li> True will set the attribute to 'yes'.
   * <li> False will set the attribute to 'no'.
   * <li> Null will unset the attribute.
   * </ul>
   *
   * @param bool|null $value The attribute value.
   *
   * @return $this
   *
   * @since 1.0.0
   * @api
   */
  public function setAttrTranslate(?bool $value): self
  {
    $this->attributes['translate'] = $value;

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Removes all classes for the list of classes.
   *
   * @return $this
   *
   * @since 1.0.0
   * @api
   */
  public function unsetClass(): self
  {
    unset($this->attributes['class']);

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
