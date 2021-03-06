<?php

namespace Deployer;

use SourceBroker\DeployerExtended\Utility\ArrayUtility;
use SourceBroker\DeployerExtended\Utility\DatabaseUtility;

task('db:truncate_table', function () {

    $databasesEnvConfigs = get('database_env_config');
    foreach ($databasesEnvConfigs as $databaseCode => $databasesEnvConfig) {
        $cachingTablesWithPatterns = $databasesEnvConfig['caching_tables'];
        $cachingTables = ArrayUtility::filterWithRegexp($cachingTablesWithPatterns, DatabaseUtility::getTables($databasesEnvConfig));
        foreach ($cachingTables as $cachingTable) {
            runLocally(sprintf(
                'export MYSQL_PWD="%s" && %s --default-character-set=utf8 -h%s -P%s -u%s -D%s -e \'%s\' ',
                $databasesEnvConfig['password'],
                get('db_settings_mysql_path'),
                $databasesEnvConfig['host'],
                (isset($databasesEnvConfig['port']) && $databasesEnvConfig['port']) ? $databasesEnvConfig['port'] : 3306,
                $databasesEnvConfig['user'],
                $databasesEnvConfig['dbname'],
                'TRUNCATE ' . $cachingTable), 0);
        }
        writeln('<info>Truncated tables: ' . implode(',', $cachingTables) . '</info>');
    };

})->desc('Truncate tables defined as caching tables on local instance.');