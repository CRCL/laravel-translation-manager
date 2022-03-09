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


        //using api as group so it gets ignored by translations:find
        $html = <<<EOT
       <div>{{ _t('api.mykeyyy333aa') }}</div>
       <div>{{ _t('api.mykeyyy11', ['aaa' => 'bb'], dv: 'one one 11my default value') }}</div>
       <div>{{ _t('api.mykeyyy222', dv: "22222my default value") }}</div>
       <div>{{ _t('api.mykeyyy222bb', dv: "333 3 my default value hana's church") }}</div>
       <div>{{ _t('api.mykeyyy333bb', 'en_US') }}</div>
       <div>{{ _t('api.mykeyyy4444') }}</div>
EOT;


        $groupPattern = $manager->getGroupPattern(['_t'], ['api']);

        if (preg_match_all("/$groupPattern/siU", $html, $matches)) {
//             Get all matches
            foreach ($matches[2] as $key) {
                $groupKeys[] = $key;
            }

            $groupDefaultValues = [];

            foreach($matches[4] as $optionalArguments){
                $groupDefaultValues[] = $manager->getDefaultValues(
                    $optionalArguments,
                );
            }
        }
        self::assertNull($groupDefaultValues[0]);

        self::assertEquals("one one 11my default value", $groupDefaultValues[1]);
        self::assertEquals("22222my default value", $groupDefaultValues[2]);
        self::assertEquals("333 3 my default value hana", $groupDefaultValues[3]);

        self::assertEquals('abb.mykeyyy333aa', $groupKeys[0]);
    }

}
