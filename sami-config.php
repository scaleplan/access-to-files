<?php

require dirname(__DIR__, 3) . '/vendor/autoload.php';
//require __DIR__ . '/vendor/avtomon/sami-markdown/src/SamiTwigExtension.php';

use Sami\{
    Parser\Filter\TrueFilter,
    Sami
};

$sami = new Sami(__DIR__ . '/src', array(
    'theme'                => 'github',
    //'theme'                => 'markdown',
    'title'                => 'AccessToFiles API',
    'build_dir'            => __DIR__.'/docs_ru',
    'cache_dir'            => __DIR__.'/cache',
    'template_dirs'        => [dirname(__DIR__, 3) . '/vendor/avtomon/sami-github'],
    //'template_dirs'        => array(__DIR__.'/vendor/avtomon/sami-markdown/src'),
));
// document all methods and properties
$sami['filter'] = function () {
    return new TrueFilter();
};

//$sami["twig"]->addExtension(new Markdown\SamiTwigExtension());

return $sami;