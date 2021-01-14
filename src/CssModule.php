<?php
declare(strict_types=1);

namespace Plaisio\Helper;

/**
 * Trait for CSS modules with CSS module and sub-module classes.
 */
trait CssModule
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * The CSS module class.
   *
   * @var string
   */
  protected string $moduleClass;

  /**
   * The CSS sub-module class.
   *
   * @var string|null
   */
  protected ?string $subModuleClass;

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
   * @return $this
   */
  public function setSubModuleClass(?string $subModuleClass): self
  {
    $this->subModuleClass = $subModuleClass;

    return $this;
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
