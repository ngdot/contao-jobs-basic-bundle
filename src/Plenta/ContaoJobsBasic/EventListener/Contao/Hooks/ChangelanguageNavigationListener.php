<?php

declare(strict_types=1);

/**
 * Plenta Jobs Basic Bundle for Contao Open Source CMS
 *
 * @copyright     Copyright (c) 2022, Plenta.io
 * @author        Plenta.io <https://plenta.io>
 * @link          https://github.com/plenta/
 */

namespace Plenta\ContaoJobsBasic\EventListener\Contao\Hooks;

use Contao\Config;
use Contao\Input;
use Plenta\ContaoJobsBasic\Contao\Model\PlentaJobsBasicOfferModel;
use Symfony\Component\HttpFoundation\RequestStack;
use Terminal42\ChangeLanguage\Event\ChangelanguageNavigationEvent;

class ChangelanguageNavigationListener
{
    protected RequestStack $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function onChangelanguageNavigation(ChangelanguageNavigationEvent $event): void
    {
        $targetRoot = $event->getNavigationItem()->getRootPage();
        $language = $targetRoot->language;
        if (!isset($_GET['items']) && isset($_GET['auto_item']) && Config::get('useAutoItem')) {
            Input::setGet('items', Input::get('auto_item'));
        }
        $alias = Input::get('items');

        if ($alias) {
            $jobOffer = PlentaJobsBasicOfferModel::findPublishedByIdOrAlias($alias);

            if (null !== $jobOffer) {
                if ($targetRoot->rootIsFallback) {
                    $newAlias = $jobOffer->alias;
                } else {
                    $translation = $jobOffer->getTranslation($language);
                    if (!$translation) {
                        $event->skipInNavigation();

                        return;
                    }
                    $newAlias = $translation['alias'];
                }

                $event->getUrlParameterBag()->setUrlAttribute('items', $newAlias);
            }
        }
    }
}
