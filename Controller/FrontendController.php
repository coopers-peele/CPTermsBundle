<?php

namespace CP\Bundle\TermsBundle\Controller;

use CP\Bundle\TermsBundle\Form\Type\AgreementFormType;
use CP\Bundle\TermsBundle\Propel\Agreement;
use CP\Bundle\TermsBundle\Propel\Terms;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class FrontendController extends Controller
{
    /**
     * @Route("/", name="cp_terms")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $terms = Terms::getLatest();

        return array(
            'terms' => $terms,
            'root' => $terms->getRoot()
        );
    }

    /**
     * @Route("/agree", name="cp_terms_agree")
     * @Template()
     */
    public function agreeAction(Request $request)
    {
        if ($this->container->has('cp_terms.entity_finder')) {
            $entity_finder = $this->container->get('cp_terms.entity_finder');

            $entity = $entity_finder->findEntity();
        } else {
            throw $this->createNotFoundException('Page not found.');
        }

        $agreement_diff_enabled = $this->container->getParameter('cp_terms.agreement.show_diff');

        $agreement = new Agreement();
        $agreement->setEntity($entity);
        $agreement->setTerms(Terms::getLatest());

        $form = $this->createForm(
            new AgreementFormType(),
            $agreement,
            array(
                'action' => $this->generateUrl('cp_terms_agree')
            )
        );

        $form->handleRequest($request);

        if ($form->isValid()) {

            if (!$entity->hasAgreedToLatestTerms()) {
                $agreement->save();
            }

            $this->get('session')->getFlashBag()->add(
                'success',
                $this->get('translator')->trans('agreement.success', array(), 'CPTermsBundle', $request->getLocale())
            );

            return $this->redirect($this->generateUrl('cp_terms_agree'));
        }

        return array(
            'agreement' => $agreement,
            'agreement_diff_enabled' => $agreement_diff_enabled,
            'entity' => $entity,
            'form' => $form->createView()
        );
    }

    /**
     * @Route("/diff", name="cp_terms_diff")
     * @Template()
     */
    public function diffAction(Request $request)
    {

        if ($this->container->has('cp_terms.entity_finder')) {
            $entity_finder = $this->container->get('cp_terms.entity_finder');

            $entity = $entity_finder->findEntity();
        } else {
            throw $this->createNotFoundException('Page not found.');
        }

        $terms_last_agreed = $entity->getLastAgreedTerms();

        $terms_latest = Terms::getLatest();

        if (!$terms_last_agreed || ($terms_last_agreed == $terms_latest)) {
            // users don't get here unless direct url to this page is entered
            return $this->redirect(
                $this->generateUrl('cp_terms')
            );
        }

        $theme = $this->container->getParameter('cp_terms.diff.theme');

        return array(
            'terms' => $terms_last_agreed,
            'terms_latest' => $terms_latest,
            'theme' => $theme
        );
    }
}
