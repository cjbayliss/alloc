<?php

error_reporting(E_ALL);

require_once 'shared/util.inc.php'; // IMPORTANT!!
require_once 'shared/lib/DatabaseEntity.inc.php';
require_once 'client/lib/client.inc.php';

use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
final class clientTest extends TestCase
{
    // Your test methods
    public function testSimpleTest()
    {
        // these two functions only *ever* return true, so this is pointless.
        // but is it a starting point for the new unit tests
        $this->assertEquals(true, client::has_attachment_permission(false));
        $this->assertEquals(true, client::has_attachment_permission_delete(false));
    }
}
