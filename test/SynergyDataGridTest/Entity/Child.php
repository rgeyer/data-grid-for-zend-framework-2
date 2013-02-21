<?php

namespace SynergyDataGridTest\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="child")
 * @author Ryan J. Geyer <me@ryangeyer.com>
 */
class Child
{
  /**
   * @Orm\Id
   * @ORM\GeneratedValue
   * @ORM\Column(type="integer")
   * @var integer
   */
  public $id;

  /**
   * @ORM\Column(type="string")
   * @var string
   */
  public $name;
}