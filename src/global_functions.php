<?php


use Barryvdh\TranslationManager\Manager;

/**
 * @param ?string $mv default missing value
 */
function _t($key, array $replace = array(), $locale = null, $fallback = true, ?string $mv = null, $defaultMissingValueLocale = 'en'){
    /** @var \Statamic\Extensions\Translation\Translator $translator */
    $translator = app('translator');

    /** @var \Barryvdh\TranslationManager\Manager $tManager */
    $tManager = app('translation-manager');

    /** inspired by \Barryvdh\TranslationManager\Translator::get() */
    // Get without fallback
    $result = $translator->get($key, $replace, $locale, false);
    if($result === $key){
        list($namespace, $group, $item) = $translator->parseKey($key);
        if($tManager && $tManager->getConfig('learn_new_keys') && $namespace === '*' && $group && $item ){
            $tManager->missingKey($namespace, $group, $item, url: ! app()->runningInConsole() ? request()->path() : null, defaultMissingValue: $mv, defaultMissingValueLocale: $defaultMissingValueLocale);
        }

        // Reget with fallback
        $result = $translator->get($key, $replace, $locale, $fallback);

    }

    return $result;
}
