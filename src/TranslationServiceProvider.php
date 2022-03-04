<?php namespace Barryvdh\TranslationManager;

use Illuminate\Support\ServiceProvider;
use Illuminate\Translation\TranslationServiceProvider as BaseTranslationServiceProvider;
use Statamic\Extensions\Translation\Loader;

//class TranslationServiceProvider extends BaseTranslationServiceProvider {
class TranslationServiceProvider extends ServiceProvider {


    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {

        $this->app->extend('translation.loader', function ($loader, $app) {
            return new Loader($loader, $app['path.lang']);
        });
//
//        dd(app('translation.loader'));
        $this->app->extend('translator', function ($translator, $app) {
//            dd('statamiiccccxxx');
            $loader = $app['translation.loader'];

            // When registering the translator component, we'll need to set the default
            // locale as well as the fallback locale. So, we'll grab the application
            // configuration so we can easily get both of these values from there.
            $locale = $app['config']['app.locale'];

            $trans = new TranslatorDecorator($loader, $locale, $translator);

            $trans->setFallback($app['config']['app.fallback_locale']);

            if($app->bound('translation-manager')){
                $trans->setTranslationManager($app['translation-manager']);
            }
            return $translator;
        });
//
////        $this->registerLoader();
//
////        $this->app->singletor('translator', function($translator, $app) {
//        $this->app->extend('translator', function ($translator, $app) {
//            dd('barryyyy');
//            $loader = $app['translation.loader'];
//
//            // When registering the translator component, we'll need to set the default
//            // locale as well as the fallback locale. So, we'll grab the application
//            // configuration so we can easily get both of these values from there.
//            $locale = $app['config']['app.locale'];
//
//            $trans = new Translator($loader, $locale);
//
//            $trans->setFallback($app['config']['app.fallback_locale']);
//
//            if($app->bound('translation-manager')){
//                $trans->setTranslationManager($app['translation-manager']);
//            }
//
//            return $trans;
//        });

    }


}
