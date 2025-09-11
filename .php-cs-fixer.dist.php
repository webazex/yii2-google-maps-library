<?php

use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = Finder::create()
    ->in(__DIR__) // Проверять файлы в корне проекта
    ->name('*.php')
    ->exclude('vendor') // Исключить папку vendor
    ->ignoreDotFiles(true)
    ->ignoreVCS(true);

return (new Config())
    ->setRules([
        '@PSR12' => true, // Стандарт PSR-12 для совместимости с Yii2
        'array_syntax' => ['syntax' => 'short'], // Короткий синтаксис массивов
        'strict_param' => true, // Строгие проверки параметров
        'ordered_imports' => ['sort_algorithm' => 'alpha'], // Сортировка импортов
        'no_unused_imports' => true, // Удаление неиспользуемых импортов
    ])
    ->setFinder($finder)
    ->setRiskyAllowed(true) // Разрешить "рискованные" правила
    ->setCacheFile(__DIR__ . '/.php-cs-fixer.cache'); // Кэш для ускорения
