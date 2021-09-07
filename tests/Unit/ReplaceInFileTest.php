<?php

namespace Luisprmat\Spanish\Tests\Unit;

use Luisprmat\Spanish\Tests\TestCase;

class ReplaceInFileTest extends TestCase
{
    /** @test */
    function replace_a_string_using_a_regexp()
    {
        /**
         * This regular expression must be replaced in the first argument of the method call
         *     pregReplaceInFile($regex, _ , _ )
         * of the class
         * @see    \Luisprmat\Spanish\Console\InstallCommand::class
         */

        $regex = "/('locale')\s{0,}(=>)\s{0,}(')[A-Za-z_-]+(')\s{0,}(,)/";

        $this->assertEquals(
            "'locale' => 'es',",
            preg_replace($regex, "'locale' => 'es',", "'locale' => 'en',")
        );

        $this->assertEquals(
            "'locale' => 'es',",
            preg_replace($regex, "'locale' => 'es',", "'locale' => 'en-GB',")
        );

        $this->assertEquals(
            "'locale' => 'es',",
            preg_replace($regex, "'locale' => 'es',", "'locale' => 'pt-BR',")
        );

        $this->assertEquals(
            "'locale' => 'es',",
            preg_replace($regex, "'locale' => 'es',", "'locale'=>   'pt-BR'   ,")
        );

        $this->assertEquals(
            "'locale' => 'es',",
            preg_replace($regex, "'locale' => 'es',", "'locale'   =>'pt-BR'  ,")
        );
    }
}
