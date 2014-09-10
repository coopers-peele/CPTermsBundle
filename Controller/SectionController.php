<?php

namespace CP\Bundle\TermsBundle\Controller;

use CP\Bundle\TermsBundle\Form\Type\SectionFormType;
use CP\Bundle\TermsBundle\Propel\SectionQuery;
use CP\Bundle\TermsBundle\Propel\Section;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Security("has_role('ROLE_CP_TERMS')")
 */
class SectionController extends Controller
{
    /**
     * Show section
     *
     * @Route("/", name="cp_terms_admin_section_show")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $section = $this->getSection($id, false);

        return array(
            'section' => $section
        );
    }

    /**
     * Edit section
     *
     * @Route("/{id}/edit", name="cp_terms_admin_section_edit", requirements={"id": "\d+"})
     * @Template()
     */
    public function editAction(Request $request, $id)
    {
        $section = $this->getSection($id, true);

        $prefix = $request->query->get('prefix');

        $form = $this->createForm(
            new SectionFormType(),
            $section,
            array(
                'action' => $this->generateUrl(
                    'cp_terms_admin_section_edit',
                    array(
                        'id' => $id,
                        'prefix' => $prefix
                    )
                )
            )
        );

        $form->handleRequest($request);

        if ($form->isValid()) {
            $section->save();

            $this->get('session')->getFlashBag()->add(
                'success',
                $this->get('translator')->trans(
                    'section.edit.success',
                    array(
                        '%section%' => $section->getTitle(),
                        '%prefix%' => $prefix ? $prefix . ' ' : ''
                    ),
                    'CPTermsBundle',
                    $request->getLocale()
                )
            );

            return $this->redirect(
                $this->generateUrl('cp_terms_admin_show', array('id' => $section->getTermsId()))
            );
        }

        return array(
            'section' => $section,
            'form' => $form->createView()
        );
    }

    /**
     * Add section
     *
     * @Route("/{id}/child", name="cp_terms_admin_section_add_child", requirements={"id": "\d+"})
     * @Template("CPTermsBundle:Section:new.html.twig")
     */
    public function addChildAction(Request $request, $id)
    {
        $section = $this->getSection($id, true);

        $count = $section->countChildren();

        $child = new Section();

        $child->insertAsLastChildOf($section);

        $form = $this->createForm(
            new SectionFormType(),
            $child,
            array(
                'action' => $this->generateUrl('cp_terms_admin_section_add_child', array('id' => $id))
            )
        );

        $form->handleRequest($request);

        if ($form->isValid()) {
            $child->save();

            $this->get('session')->getFlashBag()->add(
                'success',
                $this->get('translator')->trans(
                    'section.add_child.success',
                    array(
                        '%section%' => $section->getTitle(),
                        '%child%' => $child->getTitle()
                    ),
                    'CPTermsBundle',
                    $request->getLocale()
                )
            );

            return $this->redirect(
                $this->generateUrl('cp_terms_admin_show', array('id' => $section->getTermsId()))
            );
        }

        return array(
            'form' => $form->createView(),
            'level' => $child->getLevel(),
            'prefix' => sprintf(
                '%s%d.',
                $request->query->get('prefix' ),
                $count + 1
            )
        );
    }

    /**
     * @Route("/{id}/sibling", name="cp_terms_admin_section_add_sibling", requirements={"id": "\d+"})
     * @Template("CPTermsBundle:Section:new.html.twig")
     */
    public function addSiblingAction(Request $request, $id)
    {
        $section = $this->getSection($id, true);

        $sibling = new Section();

        $sibling->insertAsNextSiblingOf($section);

        $form = $this->createForm(
            new SectionFormType(),
            $sibling,
            array(
                'action' => $this->generateUrl('cp_terms_admin_section_add_sibling', array('id' => $id))
            )
        );

        $form->handleRequest($request);

        if ($form->isValid()) {
            $sibling->save();

            $this->get('session')->getFlashBag()->add(
                'success',
                $this->get('translator')->trans(
                    'section.add_sibling.success',
                    array(
                        '%section%' => $section->getTitle(),
                        '%sibling%' => $sibling->getTitle()
                    ),
                    'CPTermsBundle',
                    $request->getLocale()
                )
            );

            return $this->redirect(
                $this->generateUrl('cp_terms_admin_show', array('id' => $section->getTermsId()))
            );
        }

        return array(
            'form' => $form->createView(),
            'level' => $sibling->getLevel(),
            'prefix' => $request->query->get('prefix' )
        );

        return $this->redirect($this->generateUrl('cp_terms_admin_show', array('id' => $terms->getId())));
    }

    /**
     * Delete section
     *
     * @Route("/{id}/delete", name="cp_terms_admin_section_delete", requirements={"id": "\d+"})
     */
    public function deleteAction(Request $request, $id)
    {
        $section = $this->getSection($id, true);

        $section->delete();

        $prefix = $request->query->get('prefix');

        $this->get('session')->getFlashBag()->add(
            'success',
            $this->get('translator')->trans(
                'section.delete.success',
                array(
                    '%section%' => $section->getTitle(),
                    '%prefix%' => $prefix ? $prefix . ' ' : ''
                ),
                'CPTermsBundle',
                $request->getLocale()
            )
        );

        return $this->redirect($this->generateUrl('cp_terms_admin_show', array('id' => $section->getTermsId())));
    }

    /**
     * Move section
     *
     * @Route("/move", name="cp_terms_admin_section_move")
     */
    public function moveAction(Request $request)
    {
        $section = $this->getSection($request->query->get('id'), true);

        $terms = $section->getTerms();

        // [OP 2014-09-07] Veiryf that the target section is part of the same terms!
        $dest = $this->getQuery()
            ->filterByTermsId($terms->getId())
            ->findPk($request->query->get('dest_id'));

        if (!$request->isXmlHttpRequest() || !$dest) {
            throw $this->createNotFoundException('Section not found');
        }

        $data = array();

        try {
            switch ($request->request->get('position')) {
                case 'inside':
                    $section->moveToFirstChildOf($dest);
                    break;
                case 'first':
                    $section->moveToFirstChildOf($dest);
                    break;
                case 'last':
                    $section->moveToLastChildOf($dest);
                    break;
                case 'before':
                    $section->moveToPrevSiblingOf($dest);
                    break;
                case 'after':
                    $section->moveToNextSiblingOf($dest);
                    break;
                default:
                    $section->moveToLastChildOf($dest);
                    break;
            }

            $data['success'] = true;
            $this->get('session')->getFlashBag()->add(
                'notice',
                $this->get('translator')->trans('section.move.success', array(), 'CPTermsBundle', $request->getLocale())
            );

        } catch (Exception $e) {
            $data['success'] = false;
            $this->get('session')->getFlashBag()->add(
                'notice',
                $this->get('translator')->trans('section.move.error', array(), 'CPTermsBundle', $request->getLocale())
            );

        }

        return new Response(json_encode($data), 200, array('Content-Type' => 'application/json'));
    }

    protected function getQuery($editable = true)
    {
        return SectionQuery::create()
            ->useTermsQuery()
                ->_if($editable)
                    ->editable()
                ->_endIf()
            ->endUse();
    }

    protected function getSection($id, $editable = true)
    {
        $section = $this->getQuery()
            ->findPk($id);

        if (!$section) {
            throw $this->createNotFoundException('Section not found');
        }

        return $section;
    }
}
