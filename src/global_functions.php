<?php


use Barryvdh\TranslationManager\Manager;

/**
 * @param ?string $dv default missing value
 * @param ?string $dl default missing locale
 */
function _t($key, array $replace = array(), $locale = null, $fallback = true, ?string $dv = null, $dl = 'en')
{
    /** @var \Statamic\Extensions\Translation\Translator $translator */
    $translator = app('translator');

    /** @var \Barryvdh\TranslationManager\Manager $tManager */
    $tManager         = app('translation-manager');
    $forceDisplayKeys = $tManager->getConfig()['force_display_keys'];
    if ($forceDisplayKeys) {
        return $key;
    }

    /** inspired by \Barryvdh\TranslationManager\Translator::get() */
    // Get without fallback
    $result = $translator->get($key, $replace, $locale, false);
    if ($result === $key) {
        list($namespace, $group, $item) = $translator->parseKey($key);

        // Reget with fallback
        $result = $translator->get($key, $replace, $locale, $fallback);

        if ($result === $key && $tManager && $tManager->getConfig('learn_new_keys') && $namespace === '*' && $group && $item) {
            $tManager->missingKey(
                $namespace,
                $group,
                $item,
                url: !app()->runningInConsole() ? request()->path() : null,
                defaultValue: $dv,
                defaultValueLocale: $dl
            );
        }


        if ($result === $key && $dv) {
            $result = $dv;
        }

    }

    return $result;
}
