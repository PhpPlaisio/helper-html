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
   *
   * @since 1.0.0
   * @api
   */
  public static $encoding = 'UTF-8';

  /**
   * Counter for generating unique element IDs.
   *
   * @var int
   */
  private static $autoId = 0;

  /**
   * Map from (some) unicode characters to ASCII characters.
   *
   * @var array
   */
  private static $trans = ['ß' => 'sz',
                           'à' => 'a',
                           'á' => 'a',
                           'â' => 'a',
                           'ã' => 'a',
                           'ä' => 'a',
                           'å' => 'a',
                           'æ' => 'ae',
                           'ç' => 'c',
                           'è' => 'e',
                           'é' => 'e',
                           'ê' => 'e',
                           'ë' => 'e',
                           'ì' => 'i',
                           'í' => 'i',
                           'î' => 'i',
                           'ï' => 'i',
                           'ð' => 'e',
                           'ñ' => 'n',
                           'ò' => 'o',
                           'ó' => 'o',
                           'ô' => 'o',
                           'õ' => 'o',
                           'ö' => 'o',
                           '÷' => 'x',
                           'ø' => 'o',
                           'ù' => 'u',
                           'ú' => 'u',
                           'û' => 'u',
                           'ü' => 'u',
                           'ý' => 'y',
                           'þ' => 'b',
                           'ÿ' => 'y',
                           'č' => 'c',
                           'ł' => 'l',
                           'š' => 's',
                           'ž' => 'z',
                           'а' => 'a',
                           'б' => 'b',
                           'в' => 'v',
                           'г' => 'g',
                           'д' => 'd',
                           'е' => 'e',
                           'ж' => 'zh',
                           'з' => 'z',
                           'и' => 'i',
                           'й' => 'i',
                           'к' => 'k',
                           'л' => 'l',
                           'м' => 'm',
                           'н' => 'n',
                           'о' => 'o',
                           'п' => 'p',
                           'р' => 'r',
                           'с' => 's',
                           'т' => 't',
                           'у' => 'u',
                           'ф' => 'f',
                           'х' => 'kh',
                           'ц' => 'ts',
                           'ч' => 'ch',
                           'ш' => 'sh',
                           'щ' => 'shch',
                           'ъ' => '',
                           'ы' => 'y',
                           'ь' => '',
                           'э' => 'e',
                           'ю' => 'iu',
                           'я' => 'ia',
                           'ё' => 'e'];

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
   *
   * @since 1.0.0
   * @api
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
   *
   * @since 1.0.0
   * @api
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
   *
   * @since 1.0.0
   * @api
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
   *
   * @since 1.0.0
   * @api
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
   *
   * @since 1.0.0
   * @api
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
   *
   * @since 1.0.0
   * @api
   */
  public static function txt2Html($string)
  {
    return htmlspecialchars($string, ENT_QUOTES, self::$encoding);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the slug of a string that can be safely used in an URL.
   *
   * @param string $string The string.
   *
   * @return string
   *
   * @since 1.0.0
   * @api
   */
  public static function txt2Slug($string)
  {
    return trim(preg_replace('/[^0-9a-z]+/', '-', strtr(mb_strtolower($string), self::$trans)), '-');
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
