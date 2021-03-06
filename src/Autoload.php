<?php

// run only if called from deployer.phar context
if (PHP_SAPI === 'cli' && function_exists('\Deployer\set')) {

    require_once 'recipe/common.php';

    if ($_SERVER['_'] == $_SERVER['PHP_SELF']) {
        \Deployer\set('deployer_exec', $_SERVER['_']);
    } else {
        \Deployer\set('deployer_exec', $_SERVER['_'] . $_SERVER['PHP_SELF']);
    }

    if (is_dir(getcwd()) && file_exists(getcwd() . '/deploy.php')) {
        \Deployer\set('current_dir', getcwd());
    } else {
        throw new \RuntimeException('Can not set "current_dir" var.');
    }

    \SourceBroker\DeployerExtended\Utility\FileUtility::requireFilesFromDirectoryReqursively(__DIR__ . '/../deployer/');

}