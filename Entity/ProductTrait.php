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
use Eccube\Annotation\EntityExtension;

/**
 * @EntityExtension("Eccube\Entity\Product")
 */
trait ProductTrait
{
    /**
     * @ORM\Column(type="boolean", nullable=true, options={"default":false})
     */
    private $note_store_enable = false;

    /**
     * @return bool
     */
    public function getNoteStoreEnable()
    {
        return $this->note_store_enable;
    }

    /**
     * @param bool $note_store_enable
     */
    public function setNoteStoreEnable($note_store_enable)
    {
        $this->note_store_enable = $note_store_enable;
    }
}
