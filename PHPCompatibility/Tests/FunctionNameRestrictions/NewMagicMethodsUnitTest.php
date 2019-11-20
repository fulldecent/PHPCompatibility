<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2019 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Tests\FunctionNameRestrictions;

use PHPCompatibility\Tests\BaseSniffTest;

/**
 * New Magic Methods Sniff tests.
 *
 * @group newMagicMethods
 * @group functionNameRestrictions
 * @group magicMethods
 *
 * @covers \PHPCompatibility\Sniffs\FunctionNameRestrictions\NewMagicMethodsSniff
 */
class NewMagicMethodsUnitTest extends BaseSniffTest
{

    /**
     * testNewMagicMethod
     *
     * @dataProvider dataNewMagicMethod
     *
     * @param string $methodName        Name of the method.
     * @param string $lastVersionBefore The PHP version just *before* the method became magic.
     * @param array  $lines             The line numbers in the test file which apply to this method.
     * @param string $okVersion         A PHP version in which the method was magic.
     *
     * @return void
     */
    public function testNewMagicMethod($methodName, $lastVersionBefore, $lines, $okVersion)
    {
        $file  = $this->sniffFile(__FILE__, $lastVersionBefore);
        $error = "The method {$methodName}() was not magical in PHP version {$lastVersionBefore} and earlier. The associated magic functionality will not be invoked.";
        foreach ($lines as $line) {
            $this->assertWarning($file, $line, $error);
        }

        $file = $this->sniffFile(__FILE__, $okVersion);
        foreach ($lines as $line) {
            $this->assertNoViolation($file, $line);
        }
    }

    /**
     * Data provider.
     *
     * @see testNewMagicMethod()
     *
     * @return array
     */
    public function dataNewMagicMethod()
    {
        return array(
            array('__construct', '4.4', array(20), '5.0'),
            array('__destruct', '4.4', array(21), '5.0'),
            array('__get', '4.4', array(22, 34, 61), '5.0'),
            array('__isset', '5.0', array(23, 35, 62), '5.1'),
            array('__unset', '5.0', array(24, 36, 63), '5.1'),
            array('__set_state', '5.0', array(25, 37, 64), '5.1'),
            array('__callStatic', '5.2', array(27, 39, 66), '5.3'),
            array('__invoke', '5.2', array(28, 40, 67), '5.3'),
            array('__debugInfo', '5.5', array(29, 41, 68), '5.6'),
            array('__serialize', '7.3', array(78), '7.4'),
            array('__unserialize', '7.3', array(79), '7.4'),

            // Traits.
            array('__get', '4.4', array(87), '5.0'),
            array('__isset', '5.0', array(88), '5.1'),
            array('__unset', '5.0', array(89), '5.1'),
            array('__set_state', '5.0', array(90), '5.1'),
            array('__callStatic', '5.2', array(92), '5.3'),
            array('__invoke', '5.2', array(93), '5.3'),
            array('__debugInfo', '5.5', array(94), '5.6'),
            array('__serialize', '7.3', array(95), '7.4'),
            array('__unserialize', '7.3', array(96), '7.4'),
            array('__construct', '4.4', array(97), '5.0'),
            array('__destruct', '4.4', array(98), '5.0'),
        );
    }


    /**
     * testChangedToStringMethod
     *
     * @dataProvider dataChangedToStringMethod
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testChangedToStringMethod($line)
    {
        $file = $this->sniffFile(__FILE__, '5.1');
        $this->assertWarning($file, $line, 'The method __toString() was not truly magical in PHP version 5.1 and earlier. The associated magic functionality will only be called when directly combined with echo or print.');

        $file = $this->sniffFile(__FILE__, '5.2');
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data provider.
     *
     * @see testChangedToStringMethod()
     *
     * @return array
     */
    public function dataChangedToStringMethod()
    {
        return array(
            array(26),
            array(38),
            array(65),
            array(91),
        );
    }


    /**
     * Test magic methods that shouldn't be flagged by this sniff.
     *
     * @dataProvider dataMagicMethodsThatShouldntBeFlagged
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testMagicMethodsThatShouldntBeFlagged($line)
    {
        $file = $this->sniffFile(__FILE__, '4.4'); // Low version below the first addition.
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data provider.
     *
     * @see testMagicMethodsThatShouldntBeFlagged()
     *
     * @return array
     */
    public function dataMagicMethodsThatShouldntBeFlagged()
    {
        return array(
            array(8),
            array(9),
            array(10),
            array(11),
            array(12),
        );
    }


    /**
     * testNoFalsePositives
     *
     * @dataProvider dataNoFalsePositives
     *
     * @param int $line The line number.
     *
     * @return void
     */
    public function testNoFalsePositives($line)
    {
        $file = $this->sniffFile(__FILE__, '4.4'); // Low version below the first addition.
        $this->assertNoViolation($file, $line);
    }

    /**
     * Data provider.
     *
     * @see testNoFalsePositives()
     *
     * @return array
     */
    public function dataNoFalsePositives()
    {
        return array(
            // Functions of same name outside class context.
            array(47),
            array(48),
            array(49),
            array(50),
            array(51),
            array(52),
            array(53),
            array(54),
            array(74),
            array(75),
        );
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(__FILE__, '99.0'); // High version beyond newest addition.
        $this->assertNoViolation($file);
    }
}
