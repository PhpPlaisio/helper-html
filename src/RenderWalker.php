<?php
declare(strict_types=1);

namespace Plaisio\Helper;

/**
 * Class for generating CSS class names when walking your representation of your HTMl elements.
 */
class RenderWalker
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The CSS module class.
   *
   * @var string
   */
  private string $moduleClass;

  /**
   * The CSS sub-module class.
   *
   * @var string|null
   */
  private ?string $subModuleClass;

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
   * @param string[]|string|null $subClasses      The CSS sub-classes with the CSS module class.
   * @param string[]|string|null $additionClasses Additional CSS classes.
   *
   * @return string[]
   */
  public function getClasses($subClasses = null, $additionClasses = null): array
  {
    $classes = [$this->moduleClass];
    if ($this->subModuleClass!==null)
    {
      $classes[] = $this->subModuleClass;
    }

    if ($subClasses!==null)
    {
      if (is_string($subClasses))
      {
        $classes[] = $this->moduleClass.'-'.$subClasses;
      }
      elseif (is_array($subClasses))
      {
        foreach ($subClasses as $subClass)
        {
          $classes[] = $this->moduleClass.'-'.$subClass;
        }
      }
      else
      {
        throw new \InvalidArgumentException(sprintf('Argument $subClasses must be string[]|string|null, got %s',
                                                    gettype($subClasses)));
      }
    }

    if ($additionClasses!==null)
    {
      if (is_string($additionClasses))
      {
        $classes[] = $additionClasses;
      }
      elseif (is_array($additionClasses))
      {
        foreach ($additionClasses as $additionClass)
        {
          $classes[] = $additionClass;
        }
      }
      else
      {
        throw new \InvalidArgumentException(sprintf('Argument $additionClasses must be string[]|string|null, got %s',
                                                    gettype($additionClasses)));
      }
    }

    return $classes;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the CSS module class.
   *
   * @return string
   */
  public function getModuleClass(): string
  {
    return $this->moduleClass;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns the CSS sub-module class.
   *
   * @return string|null
   */
  public function getSubModuleClass(): ?string
  {
    return $this->subModuleClass;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets CSS module class.
   *
   * @param string $moduleClass The CSS module class.
   *
   * @return $this
   */
  public function setModuleClass(string $moduleClass): self
  {
    $this->moduleClass = $moduleClass;

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Sets CSS sub-module class.
   *
   * @param string|null $subModuleClass The CSS sub-module class.
   *
   * @return RenderWalker
   */
  public function setSubModuleClass(?string $subModuleClass): RenderWalker
  {
    $this->subModuleClass = $subModuleClass;

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
