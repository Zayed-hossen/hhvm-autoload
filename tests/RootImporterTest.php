<?hh // strict
/*
 *  Copyright (c) 2015, Facebook, Inc.
 *  All rights reserved.
 *
 *  This source code is licensed under the BSD-style license found in the
 *  LICENSE file in the root directory of this source tree. An additional grant
 *  of patent rights can be found in the PATENTS file in the same directory.
 *
 */

namespace Facebook\AutoloadMap;

final class RootImporterTest extends \PHPUnit_Framework_TestCase {
  public function testFullImport(): void {
    $root = realpath(__DIR__.'/../');
    $importer = new RootImporter(
      $root,
      shape(
        'autoloadFilesBehavior' => AutoloadFilesBehavior::FIND_DEFINITIONS,
        'includeVendor' => true,
        'roots' => ImmVector { $root.'/src' },
      ),
    );
    $map = $importer->getAutoloadMap();
    $this->assertContains(
      'fredemmott\autoloadmap\exception',
      array_keys($map['class']),
    );

    $this->assertContains(
      'phpunit_framework_testcase',
      array_keys($map['class']),
    );
    $this->assertEmpty($importer->getFiles());
  }

  public function testImportWithoutVendor(): void {
    $root = realpath(__DIR__.'/../');
    $importer = new RootImporter(
      $root,
      shape(
        'autoloadFilesBehavior' => AutoloadFilesBehavior::FIND_DEFINITIONS,
        'includeVendor' => false,
        'roots' => ImmVector { $root.'/src' },
      ),
    );

    $map = $importer->getAutoloadMap();
    $this->assertContains(
      'fredemmott\autoloadmap\exception',
      array_keys($map['class']),
    );
    $this->assertNotContains(
      'phpunit_framework_testcase',
      array_keys($map['class']),
    );
    $this->assertEmpty($importer->getFiles());
  }
}