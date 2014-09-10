<?php

namespace CP\Bundle\TermsBundle\Form\Type;

use CP\Bundle\TermsBundle\Form\Extension\TermsChoiceList;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Translation\TranslatorInterface;

class TermsChoiceType extends AbstractType
{
    protected $locale;

    protected $translator;

    public function __construct(RequestStack $request_stack, TranslatorInterface $translator)
    {
        $this->locale = $request_stack->getCurrentRequest()->getLocale();

        $this->translator = $translator;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $translator = $this->getTranslator();

        $choice_list = function (Options $options) use ($translator) {
            return new TermsChoiceList(
                $translator,
                $options['locale'],
                $options['final_only'],
                $options['exclude'],
                $options['include_none'],
                $options['date_format']
            );
        };

        $resolver->setDefaults(array(
            'required' => false,
            'final_only' => false,
            'exclude' => array(),
            'locale' => $this->getLocale(),
            'date_format' => 'dd.MM.yyyy HH:mm',
            'choice_list' => $choice_list,
            'include_none' => false,
            'translation_domain' => 'CPTermsBundle'
        ));
    }

    public function getParent()
    {
        return 'choice';
    }

    public function getName()
    {
        return 'cp_terms_choice';
    }

    protected function getLocale()
    {
        return $this->locale;
    }

    protected function getTranslator()
    {
        return $this->translator;
    }
}
