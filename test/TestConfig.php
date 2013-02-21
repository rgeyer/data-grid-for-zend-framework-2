<?php
namespace SynergyDataGridTest;

return array(
    'modules' => array(
        'DoctrineModule',
        'DoctrineORMModule',
        'SynergyDataGrid',
    ),
    'module_listener_options' => array(
        'config_glob_paths'    => array(
            '../../../config/autoload/test.php',
        ),
        'module_paths' => array(
            'module',
            'vendor',
        ),
    ),
    // Doctrine config
    'doctrine' => array(
        'connection' => array(
            'orm_default' => array(
                'driverClass' => 'Doctrine\DBAL\Driver\PDOSqlite\Driver',
                'params' => array(
                    'dbname'   => 'synergydatagrid',
                )
            )
        )
    )
);
