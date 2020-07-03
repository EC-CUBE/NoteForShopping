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

namespace Plugin\NoteForShopping\Tests\Web;

use Eccube\Entity\Master\ProductStatus;
use Eccube\Entity\Product;
use Eccube\Tests\Web\Admin\AbstractAdminWebTestCase;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use function simplexml_load_string;

class RssControllerTest extends AbstractAdminWebTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->hideAllProduct();
    }

    /**
     * 対象商品設定時のRSS出力の確認.
     */
    public function testRss()
    {
        $Product = $this->createTestProduct(true);

        $rss = $this->getRss();
        self::assertSame('EC-CUBE SHOP', (string)$rss->channel->title);
        self::assertSame('http://localhost/', (string)$rss->channel->link);
        self::assertSame('', (string)$rss->channel->description);
        self::assertSame($this->eccubeConfig->get('locale'), (string)$rss->channel->language);

        self::assertSame($Product->getName(), (string)$rss->channel->item[0]->title);
        self::assertSame($this->generateUrl('product_detail', ['id' => $Product->getId()],
            UrlGeneratorInterface::ABSOLUTE_URL), (string)$rss->channel->item[0]->link);
    }

    /**
     * 対象商品未設定時のRSS出力の確認
     */
    public function testRssWithNoProduct()
    {
        $this->createTestProduct(false);

        $rss = $this->getRss();
        self::assertSame(0, $rss->channel->item->count());
    }

    private function hideAllProduct()
    {
        $this->entityManager->getRepository(Product::class)
            ->createQueryBuilder('p')
            ->update()
            ->set('p.Status', ProductStatus::DISPLAY_HIDE)
            ->getQuery()
            ->execute();
    }

    private function createTestProduct($noteStoreEnable = false)
    {
        $Product = $this->createProduct();
        $Status = $this->entityManager->find(ProductStatus::class, ProductStatus::DISPLAY_SHOW);
        $Product->setStatus($Status);
        $Product->setNoteStoreEnable($noteStoreEnable);
        $this->entityManager->flush();

        return $Product;
    }

    private function getRss()
    {
        $this->client->request('GET', $this->generateUrl('note_for_shopping_rss'));
        self::assertTrue($this->client->getResponse()->isSuccessful());

        return simplexml_load_string($this->client->getResponse()->getContent());
    }
}
