<?php
declare(strict_types=1);

namespace Plaisio\Helper;

use SetBased\Exception\FallenException;

/**
 * A utility class for generating HTML elements, tags, and attributes.
 */
final class Html
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
  public static string $encoding = 'UTF-8';

  /**
   * Counter for generating unique element IDs.
   *
   * @var int
   */
  private static int $autoId = 0;

  /**
   * Map from (some) unicode characters to ASCII characters.
   *
   * @var array
   */
  private static array $trans = ['ß' => 'sz',
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
                                 'ů' => 'u',
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
   * Returns a string that can be safely used as an ID for an element. The format of the id is 'abc_<n>' where n is
   * incremented with each call of this method.
   *
   * @return string
   *
   * @since 1.0.0
   * @api
   */
  public static function getAutoId(): string
  {
    self::$autoId++;

    return 'plaisio-id-'.self::$autoId;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the HTML code of nested elements.
   *
   * Example:
   *
   * $html = Html::generateNested([['tag'   => 'table',
   *                                'attr'  => ['class' => 'test'],
   *                                'inner' => [['tag'   => 'tr',
   *                                             'attr'  => ['id' => 'first-row'],
   *                                             'inner' => [['tag'  => 'td',
   *                                                          'text' => 'hello'],
   *                                                         ['tag'  => 'td',
   *                                                          'attr' => ['class' => 'bold'],
   *                                                          'html' => '<b>world</b>']]],
   *                                            ['tag'   => 'tr',
   *                                             'inner' => [['tag'  => 'td',
   *                                                          'text' => 'foo'],
   *                                                         ['tag'  => 'td',
   *                                                          'text' => 'bar']]],
   *                                            ['tag'   => 'tr',
   *                                             'attr'  => ['id' => 'last-row'],
   *                                             'inner' => [['tag'  => 'td',
   *                                                          'text' => 'foo'],
   *                                                         ['tag'  => 'td',
   *                                                          'text' => 'bar']]]]],
   *                               ['text' => 'The End'],
   *                               ['html' => '!']]);
   *
   * @param array|null $struct The structure of the nested elements.
   *
   * @return string
   *
   * @since 3.2.0
   * @api
   */
  public static function htmlNested(?array $struct): string
  {
    $html = '';
    self::htmlNestedHelper($struct, $html);

    return $html;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns a string with special characters converted to HTML entities.
   * This method is a wrapper around [htmlspecialchars](http://php.net/manual/en/function.htmlspecialchars.php).
   *
   * @param bool|int|float|string|null $value The string with optionally special characters.
   *
   * @return string
   *
   * @since 1.0.0
   * @api
   */
  public static function txt2Html(mixed $value): string
  {
    switch (true)
    {
      case is_string($value):
        return htmlspecialchars($value, ENT_QUOTES, self::$encoding);

      case is_int($value):
      case is_float($value):
      case $value===null:
        return (string)$value;

      case $value===true:
        return '1';

      case $value===false:
        return '0';

      default:
        throw new FallenException('type', is_object($value) ? get_class($value) : gettype($value));
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the slug of a string that can be safely used in a URL.
   *
   * @param string|null $string The string.
   *
   * @return string
   *
   * @since 1.1.0
   * @api
   */
  public static function txt2Slug(?string $string): string
  {
    if ($string===null) return '';

    return trim(preg_replace('/[^0-9a-z]+/', '-', strtr(mb_strtolower($string), self::$trans)), '-');
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Removes empty and duplicate classes from an array with classes.
   *
   * @param array $classes The classes.
   *
   * @return array
   */
  private static function cleanClasses(array $classes): array
  {
    $clean = [];

    foreach ($classes as $class)
    {
      $tmp = Html::txt2Html($class);
      if ($tmp!=='')
      {
        $clean[] = $tmp;
      }
    }

    $clean = array_unique($clean);
    sort($clean);

    return $clean;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns a string with proper conversion of special characters to HTML entities of an attribute of an HTML tag.
   *
   * Boolean attributes (e.g. checked, disabled and draggable, autocomplete also) are set when the value is none empty.
   *
   * @param string $name  The name of the attribute.
   * @param mixed  $value The value of the attribute.
   * @param string $html  The generated HTML code.
   */
  private static function htmlAttributeHelper(string $name, mixed $value, string &$html): void
  {
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
          $html .= ' ';
          $html .= $name;
          $html .= '="';
          $html .= $name;
          $html .= '"';
        }
        break;

      // Annoying boolean attribute exceptions.
      case 'draggable':
        if ($value!==null)
        {
          if ($value==='auto')
          {
            $html .= ' draggable="auto"';
          }
          elseif (empty($value) || $value==='false')
          {
            $html .= ' draggable="false"';
          }
          else
          {
            $html .= ' draggable="true"';
          }
        }
        break;

      case 'contenteditable':
        if ($value!==null)
        {
          $html .= ' ';
          $html .= $name;
          $html .= (!empty($value)) ? '="true"' : '="false"';
        }
        break;

      case 'autocomplete':
        if ($value!==null)
        {
          $html .= ' ';
          $html .= $name;
          $html .= (!empty($value)) ? '="on"' : '="off"';
        }
        break;

      case 'translate':
        if ($value!==null)
        {
          $html .= ' ';
          $html .= $name;
          $html .= (!empty($value)) ? '="yes"' : '="no"';
        }
        break;

      case 'class' and is_array($value):
        $classes = implode(' ', self::cleanClasses($value));
        if ($classes!=='')
        {
          $html .= ' class="';
          $html .= htmlspecialchars($classes, ENT_QUOTES, self::$encoding);
          $html .= '"';
        }
        break;

      default:
        if ($value!==null && $value!=='')
        {
          $html .= ' ';
          $html .= htmlspecialchars($name, ENT_QUOTES, self::$encoding);
          $html .= '="';
          $html .= self::txt2Html($value);
          $html .= '"';
        }
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Helper method for method generateNested().
   *
   * @param array|null $struct The (nested) structure of the HTML code.
   * @param string     $html   The generated HTML code.
   */
  private static function htmlNestedHelper(?array $struct, string &$html): void
  {
    if ($struct!==null)
    {
      $key = array_key_first($struct);
      if (is_int($key))
      {
        // Structure is a list of elements.
        foreach ($struct as $element)
        {
          self::htmlNestedHelper($element, $html);
        }
      }
      elseif ($key!==null)
      {
        // Structure is an associative array.
        if (isset($struct['tag']))
        {
          // Element with content.
          if (array_key_exists('inner', $struct))
          {
            self::htmlTagHelper($struct['tag'], $struct['attr'] ?? [], $html);
            self::htmlNestedHelper($struct['inner'], $html);
            $html .= '</';
            $html .= $struct['tag'];
            $html .= '>';
          }
          elseif (array_key_exists('text', $struct))
          {
            self::htmlTagHelper($struct['tag'], $struct['attr'] ?? [], $html);
            $html .= self::txt2Html($struct['text']);
            $html .= '</';
            $html .= $struct['tag'];
            $html .= '>';
          }
          elseif (array_key_exists('html', $struct))
          {
            self::htmlTagHelper($struct['tag'], $struct['attr'] ?? [], $html);
            $html .= $struct['html'];
            $html .= '</';
            $html .= $struct['tag'];
            $html .= '>';
          }
          else
          {
            self::htmlVoidElementHelper($struct['tag'], $struct['attr'] ?? [], $html);
          }
        }
        elseif (array_key_exists('text', $struct))
        {
          $html .= self::txt2Html($struct['text']);
        }
        elseif (array_key_exists('html', $struct))
        {
          $html .= $struct['html'];
        }
        else
        {
          throw new \LogicException("Expected key 'tag', 'text', or 'html'");
        }
      }
    }
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Generates the HTML code for a start tag of an element.
   *
   * @param string $tagName    The name of the tag, e.g. a, form.
   * @param array  $attributes The attributes of the tag. Special characters in the attributes will be replaced with
   *                           HTML entities.
   * @param string $html       The generated HTML code.
   */
  private static function htmlTagHelper(string $tagName, array $attributes, string &$html): void
  {
    $html .= '<';
    $html .= $tagName;
    foreach ($attributes as $name => $value)
    {
      self::htmlAttributeHelper($name, $value, $html);
    }
    $html .= '>';
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Generates the HTML code for a void element.
   *
   * Void elements are: area, base, br, col, embed, hr, img, input, keygen, link, menuitem, meta, param, source, track,
   * wbr. See <http://www.w3.org/html/wg/drafts/html/master/syntax.html#void-elements>
   *
   * @param string $tagName    The name of the tag, e.g. img, link.
   * @param array  $attributes The attributes of the tag. Special characters in the attributes will be replaced with
   *                           HTML entities.
   * @param string $html       The generated HTML code.
   */
  private static function htmlVoidElementHelper(string $tagName, array $attributes, string &$html): void
  {
    $html .= '<';
    $html .= $tagName;
    foreach ($attributes as $name => $value)
    {
      self::htmlAttributeHelper($name, $value, $html);
    }
    $html .= '/>';
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
