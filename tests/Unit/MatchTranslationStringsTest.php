<?php

namespace Tests\Unit;


use Barryvdh\TranslationManager\Manager;
use Tests\TestCase;

class MatchTranslationStringsTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_can_match()
    {

        $manager = app(Manager::class);


        $html = <<<EOT
       <div>{{ _t('abb.mykeyyy333aa') }}</div>
       <div>{{ _t('abb.mykeyyy11', ['aaa' => 'bb'], dv: 'one one 11my default value') }}</div>
       <div>{{ _t('abb.mykeyyy222', dv: "22222my default value") }}</div>
       <div>{{ _t('abb.mykeyyy222bb', dv: "333 3 my default value hana's church") }}</div>
       <div>{{ _t('abb.mykeyyy333bb', 'en_US') }}</div>
       <div>{{ _t('abb.mykeyyy4444') }}</div>
EOT;


        $groupPattern = $manager->getGroupPattern(['_t'], ['api']);

        if (preg_match_all("/$groupPattern/siU", $html, $matches)) {
//             Get all matches
            foreach ($matches[2] as $key) {
                $groupKeys[] = $key;
            }

            $groupDefaultValues = [];

            foreach($matches[4] as $optionalArguments){
                //example matches
//                array:6 [
//                    0 => ", ['aaa' => 'bb'], dv: 'my default value'"
//                    1 => ", dv: "my default value""
//                    2 => ", dv: "my default value hana's church""
//                    3 => ""
//                    4 => ", 'en_US'"
//                    5 => ""
//                  ]
                $matchDefaultValue =
                    ".*dv:[^\"\']*[\"\'](.*)[\"\']";
                if(preg_match_all("/$matchDefaultValue/siU", $optionalArguments, $dvMatches)){
                    $groupDefaultValues[] = $dvMatches[1][0];
                }else{
                    $groupDefaultValues[] = null;
                }
            }
        }
        self::assertNull($groupDefaultValues[0]);

        self::assertEquals("one one 11my default value", $groupDefaultValues[1]);
        self::assertEquals("22222my default value", $groupDefaultValues[2]);
        self::assertEquals("333 3 my default value hana", $groupDefaultValues[3]);

        self::assertEquals('abb.mykeyyy333aa', $groupKeys[0]);
    }

}
