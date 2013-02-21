<?php

namespace SynergyDataGridTest\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="parent")
 * @author Ryan J. Geyer <me@ryangeyer.com>
 */
class ParentEntity
{
  /**
   * @ORM\Id
   * @ORM\GeneratedValue
   * @ORM\Column(type="integer")
   * @var integer
   */
  public $id;

  /**
   * @ORM\OneToOne(targetEntity="Child", cascade={"persist"})
   * @ORM\JoinColumn(name="child_id", referencedColumnName="id")
   * @var SynergyDataGridTest\Entity\ParentEntity
   */
  public $child;
}