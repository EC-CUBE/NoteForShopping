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
use Plugin\NoteForShopping\Entity\Config;
use function simplexml_load_string;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class RssControllerTest extends AbstractAdminWebTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->hideAllProduct();
    }

    public function testRss()
    {
        $this->createConfig(false);
        $Product = $this->createTestProduct(false);

        $rss = $this->getRss();
        self::assertSame('EC-CUBE SHOP', (string) $rss->channel->title);
        self::assertSame('http://localhost/', (string) $rss->channel->link);
        self::assertSame('', (string) $rss->channel->description);
        self::assertSame($this->eccubeConfig->get('env(eccube_locale)'), (string) $rss->channel->language);

        self::assertSame($Product->getName(), (string) $rss->channel->item[0]->title);
        self::assertSame($this->generateUrl('product_detail', ['id' => $Product->getId()],
            UrlGeneratorInterface::ABSOLUTE_URL), (string) $rss->channel->item[0]->link);
    }

    public function testRssWithSelectTargetProduct()
    {
        $this->createConfig(true);
        $this->createTestProduct(true);

        $rss = $this->getRss();
        self::assertSame(1, $rss->channel->item->count());
    }

    public function testRssWithSelectTargetProductNoTargetProduct()
    {
        $this->createConfig(true);
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

    private function createConfig($selectTargetProduct = false)
    {
        /** @var Config $Config */
        $Config = $this->entityManager->getRepository(Config::class)->get();
        if (null === $Config) {
            $Config = new Config();
            $Config->setId(Config::ID);
        }
        $Config->setSelectTargetProduct($selectTargetProduct);

        $this->entityManager->persist($Config);
        $this->entityManager->flush();
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
