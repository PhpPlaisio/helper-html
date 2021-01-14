<?php
declare(strict_types=1);

namespace Plaisio\Helper;

/**
 * Class for generating CSS class names when walking your representation of your HTMl elements.
 */
class RenderWalker
{
  //--------------------------------------------------------------------------------------------------------------------
  use CssModule;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param string      $cssModuleClass    The CSS module class.
   * @param string|null $cssSubModuleClass The CSS sub-module class.
   */
  public function __construct(string $cssModuleClass, ?string $cssSubModuleClass = null)
  {
    $this->moduleClass    = $cssModuleClass;
    $this->subModuleClass = $cssSubModuleClass;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the module, sub-module and sub-classes for an HTML element.
   *
   * @param string|null $cssSubClass The CSS sub-class with the CSS module class.
   *
   * @return string[]
   */
  public function getClasses(?string $cssSubClass = null): array
  {
    $classes = [$this->moduleClass];
    if ($this->subModuleClass!==null)
    {
      $classes[] = $this->cssSubModuleClass;
    }
    if ($cssSubClass!==null)
    {
      $classes[] = $this->moduleClass.'-'.$cssSubClass;
    }

    return $classes;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
