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

namespace Plugin\NoteForShopping\Form\Form\Extension;

use Eccube\Form\Type\Admin\ProductType;
use Eccube\Form\Type\ToggleSwitchType;
use Plugin\NoteForShopping\Repository\ConfigRepository;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;

class ProductTypeExtension extends AbstractTypeExtension
{
    /**
     * @var ConfigRepository
     */
    protected $configRepository;

    public function __construct(ConfigRepository $configRepository)
    {
        $this->configRepository = $configRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $Config = $this->configRepository->get();
        if ($Config && $Config->getSelectTargetProduct()) {
            $builder->add('note_store_enable', ToggleSwitchType::class, [
                'label' => 'note_for_shopping.product_note_store_link',
                'eccube_form_options' => [
                    'auto_render' => true,
                ],
            ]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return ProductType::class;
    }
}
