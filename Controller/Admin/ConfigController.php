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

namespace Plugin\NoteForShopping\Controller\Admin;

use Eccube\Controller\AbstractController;
use Eccube\Repository\ProductRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ConfigController extends AbstractController
{
    /**
     * @var ProductRepository
     */
    protected $productRepository;

    /**
     * ConfigController constructor.
     */
    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * @Route("/%eccube_admin_route%/note_for_shopping/config", name="note_for_shopping_admin_config")
     * @Template("@NoteForShopping/admin/config.twig")
     */
    public function index(Request $request)
    {
        return [
            'Products' => $this->productRepository->findBy(['note_store_enable' => true]),
        ];
    }
}
