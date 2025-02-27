<?php
declare(strict_types=1);

/**
 * Pimcore
 *
 * This source file is available under two different licenses:
 * - GNU General Public License version 3 (GPLv3)
 * - Pimcore Commercial License (PCL)
 * Full copyright and license information is available in
 * LICENSE.md which is distributed with this source code.
 *
 *  @copyright  Copyright (c) Pimcore GmbH (http://www.pimcore.org)
 *  @license    http://www.pimcore.org/license     GPLv3 and PCL
 */

namespace Pimcore\Bundle\GlossaryBundle\Controller;

use Pimcore\Bundle\AdminBundle\Controller\AdminController;
use Pimcore\Bundle\GlossaryBundle\Model\Glossary;
use Pimcore\Cache;
use Pimcore\Model\Document;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/settings")
 *
 * @internal
 */
class SettingsController extends AdminController
{
    /**
     * @Route("/glossary", name="pimcore_glossary_settings_glossary", methods={"POST"})
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function glossaryAction(Request $request): JsonResponse
    {
        if ($request->get('data')) {
            $this->checkPermission('glossary');

            Cache::clearTag('glossary');

            if ($request->get('xaction') === 'destroy') {
                $data = $this->decodeJson($request->get('data'));
                $id = $data['id'];
                $glossary = Glossary::getById($id);
                $glossary->delete();

                return $this->adminJson(['success' => true, 'data' => []]);
            } elseif ($request->get('xaction') === 'update') {
                $data = $this->decodeJson($request->get('data'));

                // save glossary
                $glossary = Glossary::getById($data['id']);

                if (!empty($data['link'])) {
                    if ($doc = Document::getByPath($data['link'])) {
                        $data['link'] = $doc->getId();
                    }
                }

                $glossary->setValues($data);

                $glossary->save();

                if ($link = $glossary->getLink()) {
                    if ((int)$link > 0) {
                        if ($doc = Document::getById((int)$link)) {
                            $glossary->setLink($doc->getRealFullPath());
                        }
                    }
                }

                return $this->adminJson(['data' => $glossary, 'success' => true]);
            } elseif ($request->get('xaction') == 'create') {
                $data = $this->decodeJson($request->get('data'));
                unset($data['id']);

                // save glossary
                $glossary = new Glossary();

                if (!empty($data['link'])) {
                    if ($doc = Document::getByPath($data['link'])) {
                        $data['link'] = $doc->getId();
                    }
                }

                $glossary->setValues($data);

                $glossary->save();

                if ($link = $glossary->getLink()) {
                    if ((int)$link > 0) {
                        if ($doc = Document::getById((int)$link)) {
                            $glossary->setLink($doc->getRealFullPath());
                        }
                    }
                }

                return $this->adminJson(['data' => $glossary->getObjectVars(), 'success' => true]);
            }
        } else {
            // get list of glossaries

            $list = new Glossary\Listing();
            $list->setLimit((int) $request->get('limit', 50));
            $list->setOffset((int) $request->get('start', 0));

            $sortingSettings = \Pimcore\Bundle\AdminBundle\Helper\QueryParams::extractSortingSettings(array_merge($request->request->all(), $request->query->all()));
            if ($sortingSettings['orderKey']) {
                $list->setOrderKey($sortingSettings['orderKey']);
                $list->setOrder($sortingSettings['order']);
            }

            if ($request->get('filter')) {
                $list->setCondition('`text` LIKE ' . $list->quote('%'.$request->get('filter').'%'));
            }

            $list->load();

            $glossaries = [];
            foreach ($list->getGlossary() as $glossary) {
                if ($link = $glossary->getLink()) {
                    if ((int)$link > 0) {
                        if ($doc = Document::getById((int)$link)) {
                            $glossary->setLink($doc->getRealFullPath());
                        }
                    }
                }

                $glossaries[] = $glossary->getObjectVars();
            }

            return $this->adminJson(['data' => $glossaries, 'success' => true, 'total' => $list->getTotalCount()]);
        }

        return $this->adminJson(['success' => false]);
    }
}
