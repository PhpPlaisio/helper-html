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
   * Test getClasses without submodule class and without prevailing module classes.
   */
  public function testGetClasses1(): void
  {
    $walker = new RenderWalker('foo');

    self::assertSame([], $walker->getClasses());
    self::assertSame(['foo-eggs'], $walker->getClasses('eggs'));
    self::assertSame(['foo-eggs', 'foo-spam'], $walker->getClasses(['eggs', 'spam']));
    self::assertSame(['foo-eggs', 'is-test'], $walker->getClasses('eggs', 'is-test'));
    self::assertSame(['foo-eggs', 'is-test', 'is-unit'], $walker->getClasses('eggs', ['is-test', 'is-unit']));
    self::assertSame(['foo-eggs', 'foo-spam', 'is-test', 'is-unit'],
                     $walker->getClasses(['eggs', 'spam'], ['is-test', 'is-unit']));
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test getClasses without submodule class and with prevailing module classes.
   */
  public function testGetClasses2(): void
  {
    $walker = new RenderWalker('foo');
    $walker->setIncludeModuleClass(true);

    self::assertSame(['foo'], $walker->getClasses());
    self::assertSame(['foo', 'foo-eggs'], $walker->getClasses('eggs'));
    self::assertSame(['foo', 'foo-eggs', 'foo-spam'], $walker->getClasses(['eggs', 'spam']));
    self::assertSame(['foo', 'foo-eggs', 'is-test'], $walker->getClasses('eggs', 'is-test'));
    self::assertSame(['foo', 'foo-eggs', 'is-test', 'is-unit'], $walker->getClasses('eggs', ['is-test', 'is-unit']));
    self::assertSame(['foo', 'foo-eggs', 'foo-spam', 'is-test', 'is-unit'],
                     $walker->getClasses(['eggs', 'spam'], ['is-test', 'is-unit']));
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test getClasses with submodule class and without prevailing module classes.
   */
  public function testGetClasses3(): void
  {
    $walker = new RenderWalker('foo', 'bar');

    self::assertSame(['bar'], $walker->getClasses());
    self::assertSame(['bar', 'foo-eggs'], $walker->getClasses('eggs'));
    self::assertSame(['bar', 'foo-eggs', 'foo-spam'], $walker->getClasses(['eggs', 'spam']));
    self::assertSame(['bar', 'foo-eggs', 'is-test'], $walker->getClasses('eggs', 'is-test'));
    self::assertSame(['bar', 'foo-eggs', 'is-test', 'is-unit'], $walker->getClasses('eggs', ['is-test', 'is-unit']));
    self::assertSame(['bar', 'foo-eggs', 'foo-spam', 'is-test', 'is-unit'], $walker->getClasses(['eggs', 'spam'],
                                                                                                ['is-test',
                                                                                                 'is-unit']));
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test getClasses with submodule class and with prevailing module classes.
   */
  public function testGetClasses4(): void
  {
    $walker = new RenderWalker('foo', 'bar');
    $walker->setIncludeModuleClass(true);

    self::assertSame(['foo', 'bar'], $walker->getClasses());
    self::assertSame(['foo', 'bar', 'foo-eggs'], $walker->getClasses('eggs'));
    self::assertSame(['foo', 'bar', 'foo-eggs', 'foo-spam'], $walker->getClasses(['eggs', 'spam']));
    self::assertSame(['foo', 'bar', 'foo-eggs', 'is-test'], $walker->getClasses('eggs', 'is-test'));
    self::assertSame(['foo', 'bar', 'foo-eggs', 'is-test', 'is-unit'], $walker->getClasses('eggs', ['is-test',
                                                                                                    'is-unit']));
    self::assertSame(['foo', 'bar', 'foo-eggs', 'foo-spam', 'is-test', 'is-unit'], $walker->getClasses(['eggs', 'spam'],
                                                                                                       ['is-test',
                                                                                                        'is-unit']));
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test getClasses with invalid argument.
   */
  public function testGetClassesInvalid1(): void
  {
    $walker = new RenderWalker('foo');

    $this->expectException(\InvalidArgumentException::class);
    $walker->getClasses(123);
  }

  //--------------------------------------------------------------------------------------------------------------------
  /**
   * Test getClasses with invalid argument.
   */
  public function testGetClassesInvalid2(): void
  {
    $walker = new RenderWalker('foo');

    $this->expectException(\InvalidArgumentException::class);
    $walker->getClasses(null, 123);
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
