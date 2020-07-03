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

namespace Plugin\NoteForShopping\Controller;

use Eccube\Controller\AbstractController;
use Eccube\Repository\BaseInfoRepository;
use Eccube\Repository\ProductRepository;
use Plugin\NoteForShopping\Repository\ConfigRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class RssController extends AbstractController
{
    /**
     * @var ProductRepository
     */
    protected $productRepository;

    /**
     * @var BaseInfoRepository
     */
    protected $baseInfoRepository;

    /**
     * RssController constructor.
     */
    public function __construct(
        ProductRepository $productRepository,
        BaseInfoRepository $baseInfoRepository
    ) {
        $this->productRepository = $productRepository;
        $this->baseInfoRepository = $baseInfoRepository;
    }

    /**
     * @Route("/note_store/rss", name="note_for_shopping_rss")
     * @Template("@NoteForShopping/rss.twig")
     */
    public function index(Request $request)
    {
        $qb = $this->productRepository->getQueryBuilderBySearchData([]);
        $qb->andWhere('p.note_store_enable = true');

        // 在庫なし商品の非表示
        if ($this->baseInfoRepository->get()->isOptionNostockHidden()) {
            $this->entityManager->getFilters()->enable('option_nostock_hidden');
            $qb->innerJoin('p.ProductClasses', 'pc');
            $qb->andWhere('pc.visible = true');
        }

        $Products = $qb->getQuery()->getResult();

        $response = $this->render('@NoteForShopping/rss.twig', ['Products' => $Products]);
        $response->headers->set('Content-Type', 'text/xml');

        return $response;
    }
}
