<?php
//----------------------------------------------------------------------------------------------------------------------
namespace SetBased\Abc\Helper;

//----------------------------------------------------------------------------------------------------------------------
/**
 * A utility class for generating HTML elements, tags, and attributes.
 */
class Html
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The encoding of the generated HTML code.
   *
   * @var string
   */
  public static $encoding = 'UTF-8';

  /**
   * Counter for generating unique element IDs.
   *
   * @var int
   */
  private static $autoId = 0;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns a string with proper conversion of special characters to HTML entities of an attribute of a HTML tag.
   *
   * Boolean attributes (e.g. checked, disabled and draggable, autocomplete also) are set when the value is none empty.
   *
   * @param string $name  The name of the attribute.
   * @param mixed  $value The value of the attribute.
   *
   * @return string
   */
  public static function generateAttribute($name, $value)
  {
    $html = '';

    switch ($name)
    {
      // Boolean attributes.
      case 'autofocus':
      case 'checked':
      case 'disabled':
      case 'hidden':
      case 'ismap':
      case 'multiple':
      case 'novalidate':
      case 'readonly':
      case 'required':
      case 'selected':
      case 'spellcheck':
        if (!empty($value))
        {
          $html = ' ';
          $html .= $name;
          $html .= '="';
          $html .= $name;
          $html .= '"';
        }
        break;

      // Annoying boolean attribute exceptions.
      case 'draggable':
      case 'contenteditable':
        if (isset($value))
        {
          $html = ' ';
          $html .= $name;
          $html .= ($value) ? '="true"' : '="false"';
        }
        break;

      case 'autocomplete':
        if (isset($value))
        {
          $html = ' ';
          $html .= $name;
          $html .= ($value) ? '="on"' : '="off"';
        }
        break;

      case 'translate':
        if (isset($value))
        {
          $html = ' ';
          $html .= $name;
          $html .= ($value) ? '="yes"' : '="no"';
        }
        break;

      default:
        if ($value!==null && $value!==false && $value!=='')
        {
          $html = ' ';
          $html .= htmlspecialchars($name, ENT_QUOTES, self::$encoding);
          $html .= '="';
          $html .= htmlspecialchars($value, ENT_QUOTES, self::$encoding);
          $html .= '"';
        }
        break;
    }

    return $html;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Generates HTML code for an element.
   *
   * Note: tags for void elements such as '<br/>' are not supported.
   *
   * @param string $tagName    The name of the tag, e.g. a, form.
   * @param array  $attributes The attributes of the tag. Special characters in the attributes will be replaced with
   *                           HTML entities.
   * @param string $innerText  The inner text of the tag.
   * @param bool   $isHtml     If set the inner text is a HTML snippet, otherwise special characters in the inner
   *                           text will be replaced with HTML entities.
   *
   * @return string
   */
  public static function generateElement($tagName, $attributes = [], $innerText = '', $isHtml = false)
  {
    $html = self::generateTag($tagName, $attributes);
    $html .= ($isHtml) ? $innerText : self::txt2Html($innerText);
    $html .= '</';
    $html .= $tagName;
    $html .= '>';

    return $html;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Generates HTML code for a start tag of an element.
   *
   * @param string $tagName    The name of the tag, e.g. a, form.
   * @param array  $attributes The attributes of the tag. Special characters in the attributes will be replaced with
   *                           HTML entities.
   *
   * @return string
   */
  public static function generateTag($tagName, $attributes = [])
  {
    $html = '<';
    $html .= $tagName;
    foreach ($attributes as $name => $value)
    {
      // Ignore attributes with leading underscore.
      if (strpos($name, '_')!==0) $html .= self::generateAttribute($name, $value);
    }
    $html .= '>';

    return $html;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Generates HTML code for a void element.
   *
   * Void elements are: area, base, br, col, embed, hr, img, input, keygen, link, menuitem, meta, param, source, track,
   * wbr. See <http://www.w3.org/html/wg/drafts/html/master/syntax.html#void-elements>
   *
   * @param string $tagName    The name of the tag, e.g. img, link.
   * @param array  $attributes The attributes of the tag. Special characters in the attributes will be replaced with
   *                           HTML entities.
   *
   * @return string
   */
  public static function generateVoidElement($tagName, $attributes = [])
  {
    $html = '<';
    $html .= $tagName;
    foreach ($attributes as $name => $value)
    {
      // Ignore attributes with leading underscore.
      if (strpos($name, '_')!==0) $html .= self::generateAttribute($name, $value);
    }
    $html .= '/>';

    return $html;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns a string that can be safely used as an ID for an element. The format of the id is 'abc_<n>' where n is
   * incremented with each call of this method.
   *
   * @return string
   */
  public static function getAutoId()
  {
    self::$autoId++;

    return 'abc_'.self::$autoId;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns a string with special characters converted to HTML entities.
   * This method is a wrapper around [htmlspecialchars](http://php.net/manual/en/function.htmlspecialchars.php).
   *
   * @param string $string The string with optionally special characters.
   *
   * @return string
   */
  public static function txt2Html($string)
  {
    return htmlspecialchars($string, ENT_QUOTES, self::$encoding);
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
