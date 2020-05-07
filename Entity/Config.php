<?php

/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) EC-CUBE CO.,LTD. All Rights Reserved.
 *
 * http://www.ec-cube.co.jp/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plugin\NoteForShopping\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Config
 *
 * @ORM\Table(name="plg_note_for_shopping_config")
 * @ORM\Entity(repositoryClass="Plugin\NoteForShopping\Repository\ConfigRepository")
 */
class Config
{
    const ID = 1;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", options={"unsigned":true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="select_target_product", type="boolean", options={"default":false})
     */
    private $select_target_product = false;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return boolean
     */
    public function getSelectTargetProduct()
    {
        return $this->select_target_product;
    }

    /**
     * @param boolean $select_target_product
     *
     * @return $this;
     */
    public function setSelectTargetProduct($select_target_product)
    {
        $this->select_target_product = $select_target_product;

        return $this;
    }
}
