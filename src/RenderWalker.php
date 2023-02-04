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
   * Whether to always include the module class individually in the list of classes for an HTMl element.
   *
   * @var bool
   */
  private bool $includeModuleClass = false;

  /**
   * The CSS module class.
   *
   * @var string
   */
  private string $moduleClass;

  /**
   * The CSS submodule class.
   *
   * @var string|null
   */
  private ?string $subModuleClass;

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Object constructor.
   *
   * @param string      $moduleClass    The CSS module class.
   * @param string|null $subModuleClass The CSS submodule class.
   */
  public function __construct(string $moduleClass, ?string $subModuleClass = null)
  {
    $this->moduleClass    = $moduleClass;
    $this->subModuleClass = $subModuleClass;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Returns all applicable classes for an HTML element.
   *
   * @param string[]|string|null $subClasses      The CSS subclasses with the CSS module class.
   * @param string[]|string|null $additionClasses Additional CSS classes.
   *
   * @return string[]
   */
  public function getClasses(mixed $subClasses = null, mixed $additionClasses = null): array
  {
    if ($this->includeModuleClass)
    {
      $classes = [$this->moduleClass];
    }
    else
    {
      $classes = [];
    }

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
   * Returns the CSS submodule class.
   *
   * @return string|null
   */
  public function getSubModuleClass(): ?string
  {
    return $this->subModuleClass;
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Set whether to always include the module and submodule class individually in the list of classes for an HTMl
   * element.
   *
   * @param bool $includeModuleClass Whether to always include the module class individually in the list of  classes
   *                                 for an HTMl element.
   *
   * @return $this
   */
  public function setIncludeModuleClass(bool $includeModuleClass): RenderWalker
  {
    $this->includeModuleClass = $includeModuleClass;

    return $this;
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
   * Sets CSS submodule class.
   *
   * @param string|null $subModuleClass The CSS submodule class.
   *
   * @return $this
   */
  public function setSubModuleClass(?string $subModuleClass): RenderWalker
  {
    $this->subModuleClass = $subModuleClass;

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
