<?php

namespace CP\Bundle\TermsBundle\Controller;

use IntlDateFormatter;

use CP\Bundle\TermsBundle\Form\Type\DiffFormType;
use CP\Bundle\TermsBundle\Form\Type\TermsFormType;
use CP\Bundle\TermsBundle\Propel\Terms;
use CP\Bundle\TermsBundle\Propel\TermsQuery;
use CP\Bundle\TermsBundle\Propel\Section;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Security("has_role('ROLE_CP_TERMS')")
 */
class AdminController extends Controller
{
    /**
     * @Route("/", name="cp_terms_admin")
     * @Template("CPTermsBundle:Admin:index.html.twig")
     */
    public function indexAction(Request $request)
    {
        $terms = TermsQuery::create()
            ->orderByUpdatedAt('desc')
            ->find();

        return array(
            'terms' => $terms,
            'date_format' => $this->container->getParameter('cp_terms.date_format')
        );
    }

    /**
     * @Route("/create", name="cp_terms_admin_create")
     * @Template("CPTermsBundle:Admin:create.html.twig")
     */
    public function createAction(Request $request)
    {
        $term = new Terms();

        $form = $this->createForm(
            new TermsFormType(),
            $term,
            array(
                'action' => $this->generateUrl('cp_terms_admin_create')
            )
        );

        $form->handleRequest($request);

        if ($form->isValid()) {
            $tos_section = new Section();
            $tos_section->makeRoot();
//            $tos_section->setTitle('Terms of service');

            $term->addSection($tos_section);
            $term->save();

            return $this->redirect($this->generateUrl(
                'cp_terms_admin_show',
                array(
                    'id' => $term->getId()
                )
            ));
        }

        return array(
            'form' => $form->createView()
        );
    }

    /**
     * @Route("/{id}/diff", name="cp_terms_admin_diff", requirements={"id": "\d+"})
     * @Template("CPTermsBundle:Admin:diff.html.twig")
     */
    public function diffAction(Request $request, $id)
    {
        $terms = $this->getTerms($id, false);

        $form = $this->createForm(
            new DiffFormType(),
            array('terms' => $terms->getDefaultDiffTarget()),
            array(
                'action' => $this->generateUrl(
                    'cp_terms_admin_diff', array('id' => $id)
                ),
                'exclude' => array($id)
            )
        );

        $form->handleRequest($request);

        $with_terms = $form['terms']->getData();

        if (!$with_terms) {
            $with_terms = $terms->getDefaultDiffTarget();
        }

        $terms_count = TermsQuery::create()
            ->count();

        $theme = $this->container->getParameter('cp_terms.diff.theme');

        return array(
            'form' => $form->createView(),
            'terms' => $terms,
            'theme' => $theme,
            'with_terms' => $with_terms,
            'terms_count' => $terms_count,
            'date_format' => $this->container->getParameter('cp_terms.date_format')
        );
    }

    /**
     * show terms
     *
     * @Route("/{id}", name="cp_terms_admin_show", requirements={"id": "\d+"})
     * @Template("CPTermsBundle:Admin:show.html.twig")
     */
    public function showAction(Request $request, $id)
    {
        $terms = $this->getTerms($id, false);

        return array(
            'terms' => $terms,
            'root' => $terms->getRoot(),
            'date_format' => $this->container->getParameter('cp_terms.date_format')
        );
    }

    /**
     * show terms
     *
     * @Route("/{id}/edit", name="cp_terms_admin_edit", requirements={"id": "\d+"})
     * @Template("CPTermsBundle:Admin:edit.html.twig")
     */
    public function editAction(Request $request, $id)
    {
        $terms = $this->getTerms($id, false);

        $form = $this->createForm(
            new TermsFormType(),
            $terms,
            array(
                'action' => $this->generateUrl('cp_terms_admin_edit', array('id' => $id))
            )
        );

        $form->handleRequest($request);

        if ($form->isValid()) {
            $terms->save();

            $this->get('session')->getFlashBag()->add(
                'success',
                $this->get('translator')->trans(
                    'terms.edit.success',
                    array(
                        '%terms%' => $terms->getVersion()
                    ),
                    'CPTermsBundle',
                    $request->getLocale()
                )
            );

            return $this->redirect(
                $this->generateUrl('cp_terms_admin_show', array('id' => $id))
            );
        }

        return array(
            'terms' => $terms,
            'form' => $form->createView()
        );
    }

    /**
     * @Route("/{id}/clone", name="cp_terms_admin_clone")
     */
    public function cloneAction(Request $request, $id)
    {
        $terms = $this->getTerms($id, false);

        $clone = $terms->getClone();

        return $this->redirect($this->generateUrl('cp_terms_admin_show', array('id' => $clone->getId())));
    }

    /**
     * Delete terms
     *
     * @Route("/{id}/delete", name="cp_terms_admin_delete", requirements={"id": "\d+"})
     */
    public function deleteAction(Request $request, $id)
    {
        $terms = $this->getTerms($id, true);

        $terms->delete();

        $this->get('session')->getFlashBag()->add(
            'success',
            $this->get('translator')->trans(
                'terms.delete.success',
                array(
                    '%terms%' => $terms->getVersion()
                ),
                'CPTermsBundle',
                $request->getLocale()
            )
        );

        return $this->redirect($this->generateUrl('cp_terms_admin'));
    }

    /**
     * Finalize terms
     *
     * @Route("/{id}/finalize", name="cp_terms_admin_finalize", requirements={"id": "\d+"})
     */
    public function finalizeAction(Request $request, $id)
    {
        $terms = $this->getTerms($id, true);

        $terms->finalize();

        $terms->save();

        $date_formatter = new IntlDateFormatter(
            $request->getLocale(),
            IntlDateFormatter::LONG,
            IntlDateFormatter::SHORT
        );

        $this->get('session')->getFlashBag()->add(
            'success',
            $this->get('translator')->trans(
                'terms.finalize.success',
                array(
                    '%finalized_at%' => $date_formatter->format($terms->getFinalizedAt(null))
                ),
                'CPTermsBundle',
                $request->getLocale()
            )
        );

        return $this->redirect(
            $this->generateUrl('cp_terms_admin_show', array('id' => $id))
        );
    }

    protected function getTerms($id, $editable = true)
    {
        $terms = TermsQuery::create()
            ->_if($editable)
                ->editable()
            ->_endIf()
            ->findPk($id);

        if (!$terms) {
            throw $this->createNotFoundException('Terms not found');
        }

        return $terms;
    }

}
