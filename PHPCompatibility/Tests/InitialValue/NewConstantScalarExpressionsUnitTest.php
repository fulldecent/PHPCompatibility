<?php
/**
 * PHPCompatibility, an external standard for PHP_CodeSniffer.
 *
 * @package   PHPCompatibility
 * @copyright 2012-2019 PHPCompatibility Contributors
 * @license   https://opensource.org/licenses/LGPL-3.0 LGPL3
 * @link      https://github.com/PHPCompatibility/PHPCompatibility
 */

namespace PHPCompatibility\Tests\InitialValue;

use PHPCompatibility\Tests\BaseSniffTest;

/**
 * New constant scalar expressions in PHP 5.6 sniff test file.
 *
 * @group newConstantScalarExpressions
 * @group initialValue
 *
 * @covers \PHPCompatibility\Sniffs\InitialValue\NewConstantScalarExpressionsSniff
 */
class NewConstantScalarExpressionsUnitTest extends BaseSniffTest
{

    /**
     * Error phrases.
     *
     * @var array
     */
    private $errorPhrases = array(
        'const'    => 'when defining constants using the const keyword',
        'property' => 'in property declarations',
        'static'   => 'in static variable declarations',
        'default'  => 'in default function arguments',
    );


    /**
     * testNewConstantScalarExpressions
     *
     * @dataProvider dataNewConstantScalarExpressions
     *
     * @param int    $line  The line number.
     * @param string $type  Error type.
     * @param string $extra Extra snippet which will be part of the error message.
     *                      Only needed when testing several errors on the same line.
     *
     * @return void
     */
    public function testNewConstantScalarExpressions($line, $type, $extra = '')
    {
        $file    = $this->sniffFile(__FILE__, '5.5');
        $snippet = '';
        if (isset($this->errorPhrases[$type]) === true) {
            $snippet = $this->errorPhrases[$type];
        }

        $error = "Constant scalar expressions are not allowed {$snippet} in PHP 5.5 or earlier.";
        if ($extra !== '') {
            $error .= ' Found: ' . $extra;
        }

        $this->assertError($file, $line, $error);
    }

    /**
     * Data provider dataNewConstantScalarExpressions.
     *
     * @see testNewConstantScalarExpressions()
     *
     * @return array
     */
    public function dataNewConstantScalarExpressions()
    {
        return array(
            array(122, 'const'),
            array(123, 'const'),
            array(124, 'const'),
            array(125, 'const'),
            array(126, 'const'),
            array(127, 'const'),
            array(128, 'const'),
            array(129, 'const'),
            array(130, 'const'),
            array(131, 'const'),
            array(132, 'const'),
            array(133, 'const'),
            array(134, 'const'),
            array(135, 'const'),
            array(136, 'const'),
            array(137, 'const'),
            array(138, 'const'),
            array(139, 'const'),
            array(140, 'const'),
            array(141, 'const'),
            array(142, 'const'),
            array(143, 'const'),
            array(144, 'const'),
            array(145, 'const'),
            array(146, 'const'),
            array(147, 'const'),
            array(148, 'const'),
            array(149, 'const'),
            array(150, 'const'),

            array(153, 'const'),

            array(156, 'const'),
            array(157, 'const'),

            array(161, 'const'),
            array(162, 'const'),
            array(163, 'const'),
            array(165, 'property'),
            array(166, 'property'),
            array(171, 'property'),
            array(173, 'default'),

            array(180, 'const'),
            array(181, 'const'),
            array(182, 'const'),
            array(184, 'property'),
            array(185, 'property'),
            array(193, 'property'),
            array(195, 'default'),

            array(202, 'property'),
            array(203, 'property'),
            array(208, 'property'),
            array(210, 'default'),

            array(216, 'default', '$a = 5 * MINUTEINSECONDS'),
            array(216, 'default', '$b = [ \'a\', 1 + 2 ]'),
            array(220, 'default', '$a = 30 / HALF'),
            array(220, 'default', '$b = array( 1, THREE, \'string\'.\'concat\')'),
            array(224, 'default', '$a = (1 + 1)'),
            array(224, 'default', '$b = 2 << 3'),
            array(224, 'default', '$c = ((BAR)?10:100)'),
            array(224, 'default', '$f = 10 * 5'),

            array(227, 'default'),
            array(228, 'default'),
            array(229, 'default'),

            array(233, 'static'),
            array(234, 'static'),
            array(235, 'static'),
            array(236, 'static', '$h = (24 and 2)'),
            array(236, 'static', '$i = ONE * 2'),
            array(236, 'static', '$j = \'a\' . \'b\''),
            array(238, 'static'),

            array(241, 'static'),
            array(242, 'const'),
            array(244, 'property'),
            array(246, 'const'),
            array(247, 'const'),
            array(250, 'property'),

            array(258, 'const'),
            array(259, 'const'),

            array(262, 'static'),
            array(263, 'static'),
            array(264, 'static'),
            array(265, 'static'),
        );
    }


    /**
     * testNoFalsePositives
     *
     * @return void
     */
    public function testNoFalsePositives()
    {
        $file = $this->sniffFile(__FILE__, '5.5');

        // No errors expected on the first 120 lines.
        for ($line = 1; $line <= 120; $line++) {
            $this->assertNoViolation($file, $line);
        }
    }


    /**
     * Verify no notices are thrown at all.
     *
     * @return void
     */
    public function testNoViolationsInFileOnValidVersion()
    {
        $file = $this->sniffFile(__FILE__, '5.6');
        $this->assertNoViolation($file);
    }
}
