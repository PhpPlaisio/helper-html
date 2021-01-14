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
   * @param string      $moduleClass    The CSS module class.
   * @param string|null $subModuleClass The CSS sub-module class.
   */
  public function __construct(string $moduleClass, ?string $subModuleClass = null)
  {
    $this->moduleClass    = $moduleClass;
    $this->subModuleClass = $subModuleClass;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the module, sub-module and sub-classes for an HTML element.
   *
   * @param string|null $subClass The CSS sub-class with the CSS module class.
   *
   * @return string[]
   */
  public function getClasses(?string $subClass = null): array
  {
    $classes = [$this->moduleClass];
    if ($this->subModuleClass!==null)
    {
      $classes[] = $this->subModuleClass;
    }
    if ($subClass!==null)
    {
      $classes[] = $this->moduleClass.'-'.$subClass;
    }

    return $classes;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
