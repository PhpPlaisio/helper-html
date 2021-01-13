<?php
declare(strict_types=1);

namespace Plaisio\Helper\Test;

use PHPUnit\Framework\TestCase;
use Plaisio\Helper\RenderWalker;

/**
 * Unit tests for class RenderWalker.
 */
class RenderWalkerTest extends TestCase
{
  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test getClasses without ub-module class.
   */
  public function testGetClasses1(): void
  {
    $walker = new RenderWalker('foo');

    self::assertSame(['foo'], $walker->getClasses());
    self::assertSame(['foo', 'foo-eggs'], $walker->getClasses('eggs'));
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test getClasses without ub-module class.
   */
  public function testGetClasses2(): void
  {
    $walker = new RenderWalker('foo', 'bar');

    self::assertSame(['foo', 'bar'], $walker->getClasses());
    self::assertSame(['foo', 'bar', 'foo-eggs'], $walker->getClasses('eggs'));
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test for setter and getters.
   */
  public function testSettersAndGetters(): void
  {
    $walker = new RenderWalker('old', 'sub-old');

    $walker->setModuleClass('foo');
    $walker->setSubModuleClass('bar');

    self::assertSame('foo', $walker->getModuleClass());
    self::assertSame('bar', $walker->getSubModuleClass());
  }

  //--------------------------------------------------------------------------------------------------------------------
}

//----------------------------------------------------------------------------------------------------------------------
