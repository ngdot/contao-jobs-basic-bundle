<?php

declare(strict_types=1);

/**
 * Plenta Jobs Basic Bundle for Contao Open Source CMS
 *
 * @copyright     Copyright (c) 2022, Plenta.io
 * @author        Plenta.io <https://plenta.io>
 * @link          https://github.com/plenta/
 */

namespace Plenta\ContaoJobsBasic\Controller\Contao\ContentElement;

use Contao\ContentModel;
use Contao\CoreBundle\Controller\ContentElement\AbstractContentElementController;
use Contao\CoreBundle\DependencyInjection\Attribute\AsContentElement;
use Contao\CoreBundle\Routing\ScopeMatcher;
use Contao\CoreBundle\ServiceAnnotation\ContentElement;
use Contao\CoreBundle\Twig\FragmentTemplate;
use Contao\StringUtil;
use Contao\Template;
use Plenta\ContaoJobsBasic\Contao\Model\PlentaJobsBasicOfferModel;
use Plenta\ContaoJobsBasic\Helper\MetaFieldsHelper;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;


#[AsContentElement(category: 'plentaJobsBasic')]
class PlentaJobsBasicJobOfferTeaserController extends AbstractContentElementController
{
    protected $metaFields;

    public function __construct(
        protected MetaFieldsHelper $metaFieldsHelper,
    ) {
    }

    public function getMetaFields(ContentModel $model, PlentaJobsBasicOfferModel $jobOffer): array
    {
        if (null !== $this->metaFields) {
            return $this->metaFields;
        }

        $this->metaFields = $this->metaFieldsHelper->getMetaFields($jobOffer, $model->size);

        return $this->metaFields;
    }

    public function getResponse(FragmentTemplate $template, ContentModel $model, Request $request): Response
    {
        $jobOffer = PlentaJobsBasicOfferModel::findByIdOrAlias($model->plentaJobsBasicJobOffer);
        if (!$jobOffer) {
            return new Response();
        }
        $template->jobOffer = $jobOffer;
        $parts = StringUtil::deserialize($model->plentaJobsBasicJobOfferTeaserParts);
        if (!\is_array($parts)) {
            $parts = [];
        }
        $template->parts = $parts;
        $template->jobOfferMeta = $this->getMetaFields($model, $jobOffer);
        $template->link = $jobOffer->getFrontendUrl($request->getLocale());

        return $template->getResponse();
    }
}
