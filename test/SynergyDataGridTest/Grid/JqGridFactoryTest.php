<?php

namespace SynergyDataGridTest;

use PHPUnit_Framework_TestCase;

class DataControllerTest extends PHPUnit_Framework_TestCase
{
  public function setUp()
  {

    $serviceManager = Bootstrap::getServiceManager();

    // Initialize the schema.. Maybe I should register a module for clearing the schema/data
    // and/or loading mock test data
    $em = $serviceManager->get('doctrine.entitymanager.orm_default');
    $cli = new \Symfony\Component\Console\Application("PHPUnit Bootstrap", 1);
    $cli->setAutoExit(false);
    $helperSet = $cli->getHelperSet();
    $helperSet->set(new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($em), 'em');
    $cli->addCommands(array(new \Doctrine\ORM\Tools\Console\Command\SchemaTool\CreateCommand()));
    $cli->run(
      new \Symfony\Component\Console\Input\ArrayInput(array('orm:schema-tool:create')),
      new \Symfony\Component\Console\Output\NullOutput()
    );

    // Create a couple records to mess with
    $child = new Entity\Child();
    $child->name = "name";
    $parent = new Entity\ParentEntity();

    $parent->child = $child;
    $em->persist($parent);
    $em->flush();
  }

  public function testUsesSelectForEditOnAssociationMappedColumns() {
    $jqgridfactory = Bootstrap::getServiceManager()->get('jqgrid');
    $jqgrid = $jqgridfactory->create('SynergyDataGridTest\Entity\ParentEntity');
    $columns = $jqgrid->getColumns();
    $this->assertGreaterThan(0, count($columns));
    $this->assertContains('child', array_keys($columns));
    $child_column = $columns['child'];
    $this->assertEquals(':select', $child_column->getEditOptions()->value);
  }

  public function testAssociationMappedColumnsViewOnlyWhenSpecified() {
    $options = array('association_mappings_type' => 'read_only');
    $jqgridfactory = Bootstrap::getServiceManager()->get('jqgrid');
    $jqgrid = $jqgridfactory->create('SynergyDataGridTest\Entity\ParentEntity', $options);
    $columns = $jqgrid->getColumns();
    $this->assertGreaterThan(0, count($columns));
    $this->assertContains('child', array_keys($columns));
    $child_column = $columns['child'];
    $child_options = $child_column->getOptions();
    $this->assertFalse($child_options['editable']);
    $this->assertFalse($child_options['required']);
  }

  public function testViewOnlyAssociationMappedColumnsDisplayClassNameByDefault() {
    $options = array('association_mappings_type' => 'read_only');
    $jqgridfactory = Bootstrap::getServiceManager()->get('jqgrid');
    $jqgrid = $jqgridfactory->create('SynergyDataGridTest\Entity\ParentEntity', $options);
    $paginate_adapter = new \SynergyDataGrid\Grid\PaginatorAdapter($jqgrid->getService(),false,false,$jqgrid);
    $paginator = new \Zend\Paginator\Paginator($paginate_adapter);
    $rows = $paginator->getCurrentItems();
    $child_column = $jqgrid->getColumn('child');
    $value = $child_column->cellValue($rows[0]);
    $this->assertEquals($value, "1:SynergyDataGridTest\Entity\Child");
  }

  public function testViewOnlyAssociationMappedColumnsCanDisplayValueFromAnonFunction() {
    $options = array('association_mappings_type' => 'read_only');
    $jqgridfactory = Bootstrap::getServiceManager()->get('jqgrid');
    $jqgrid = $jqgridfactory->create('SynergyDataGridTest\Entity\ParentEntity', $options);
    $paginate_adapter = new \SynergyDataGrid\Grid\PaginatorAdapter($jqgrid->getService(),false,false,$jqgrid);
    $paginator = new \Zend\Paginator\Paginator($paginate_adapter);
    $rows = $paginator->getCurrentItems();
    $child_column = $jqgrid->getColumn('child');
    $child_column->setAssociationMappingFetcher(function($entity) { return $entity->name; });
    // Paranoid way of making sure we assigned the association mapping fetcher to the reference of the column
    // that is still held by the grid
    $child_column = $jqgrid->getColumn('child');
    $value = $child_column->cellValue($rows[0]);
    $this->assertEquals($value, "name");
  }
}