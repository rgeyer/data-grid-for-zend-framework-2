<?php
// From within a configuration file
return array(
    'view_manager' => array(
        'helper_map' => array(
            'displayGrid' => 'SynergyDataGrid\View\Helper\DisplayGrid',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),

    'service_manager' => array(
        'factories' => array(
            'jqgrid' => 'SynergyDataGrid\Grid\JqGridFactory',
        ),
    ),
    'jqgrid' => array(
        'excluded_columns' => array(

        ),
        'non_editable_columns' => array(

        )
    ),
  // Doctrine config only for tests, could/should find a better place to put
  // this and still get it loaded for tests.
    'doctrine' => array(
      'driver' => array(
        'SynergyDataGridTest_driver' => array(
          'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
          'cache' => 'array',
          'paths' => array(__DIR__ . '/../test/SynergyDataGridTest/Entity')
        ),
        'orm_default' => array(
          'drivers' => array(
            'SynergyDataGridTest\Entity' => 'SynergyDataGridTest_driver'
          )
        )
      ),
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