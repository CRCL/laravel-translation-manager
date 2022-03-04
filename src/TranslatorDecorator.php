<?php namespace Barryvdh\TranslationManager;

use Illuminate\Contracts\Translation\Loader;
use Illuminate\Translation\Translator as LaravelTranslator;
use Illuminate\Events\Dispatcher;

class TranslatorDecorator {

    /** @var  Dispatcher */
    protected $events;
    protected   $translator;

    /**
     * Create a new translator instance.
     *
     * @param  \Illuminate\Contracts\Translation\Loader  $loader
     * @param  string  $locale
     * @return void
     */
    public function __construct(Loader $loader, $locale, $translator)
    {
        $translator->loader = $loader;

        $translator->setLocale($locale);
        $this->translator = $translator;
    }

    /**
     * Get the translation for the given key.
     *
     * @param  string  $key
     * @param  array   $replace
     * @param  string  $locale
     * @return string
     */
    public function get($key, array $replace = array(), $locale = null, $fallback = true)
    {
        // Get without fallback
        $result = $this->translator->get($key, $replace, $locale, false);
        if($result === $key){
            $this->notifyMissingKey($key);

            // Reget with fallback
            $result = $this->translator->get($key, $replace, $locale, $fallback);

        }

        return $result;
    }

    public function setTranslationManager(Manager $manager)
    {
        $this->manager = $manager;
    }

    protected function notifyMissingKey($key)
    {
        list($namespace, $group, $item) = $this->parseKey($key);
        if($this->manager && $namespace === '*' && $group && $item ){
            $this->manager->missingKey($namespace, $group, $item);
        }
    }

    public function __set($name, $value)
    {
        $this->translator->{$name} = $value;
    }

    public function __isset($name)
    {
        return isset($this->translator->{$name});
    }

    public function __unset($name)
    {
        unset($this->translator->{$name});
    }

    public function __get($name)
    {
        return $this->translator->{$name};
    }

    public function __call($name, $arguments)
    {
        $this->translator->{$name}(...$arguments);
    }

//    public static function __callStatic($name, $arguments)
//    {
//        $this->translator::{$name}(...$arguments);
//    }

}
