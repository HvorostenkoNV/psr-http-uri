<?php
declare(strict_types=1);
/** ***********************************************************************************************
 * @package HNV\Psr\Http\Uri
 * @author  Hvorostenko
 *
 * Classes autoloader.
 *************************************************************************************************/
spl_autoload_register(function(string $className): void {
    $packageClassesPrefix   = 'HNV\\Http\\Uri\\';
    $classIsFromThisPackage = strpos($className, $packageClassesPrefix) === 0;

    if (!$classIsFromThisPackage) {
        return;
    }

    $classNameCut       = substr($className, strlen($packageClassesPrefix));
    $classFilePathPart  = (string) str_replace('\\', DIRECTORY_SEPARATOR, $classNameCut);
    $classFilePath      = __DIR__.DIRECTORY_SEPARATOR.$classFilePathPart.'.php';

    if (file_exists($classFilePath)) {
        require_once $classFilePath;
    }
});