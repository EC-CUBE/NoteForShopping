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
use Plugin\NoteForShopping\Entity\Config;
use Plugin\NoteForShopping\Form\Type\Admin\ConfigType;
use Plugin\NoteForShopping\Repository\ConfigRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ConfigController extends AbstractController
{
    /**
     * @var ConfigRepository
     */
    protected $configRepository;

    /**
     * ConfigController constructor.
     */
    public function __construct(ConfigRepository $configRepository)
    {
        $this->configRepository = $configRepository;
    }

    /**
     * @Route("/%eccube_admin_route%/note_for_shopping/config", name="note_for_shopping_admin_config")
     * @Template("@NoteForShopping/admin/config.twig")
     */
    public function index(Request $request)
    {
        $Config = $this->configRepository->get();
        $form = $this->createForm(ConfigType::class, $Config);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $Config = $form->getData();
            $Config->setId(Config::ID);
            $this->entityManager->persist($Config);
            $this->entityManager->flush();
            $this->addSuccess('admin.common.save_complete', 'admin');

            return $this->redirectToRoute('note_for_shopping_admin_config');
        }

        return [
            'form' => $form->createView(),
        ];
    }
}
