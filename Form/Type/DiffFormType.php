<?php

namespace CP\Bundle\TermsBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class DiffFormType extends AbstractType
{
    /**
     *  {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'translation_domain' => 'CPTermsBundle',
            'intention' => 'terms_diff',
            'exclude' => array()
        ));
    }

    /**
     *  {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('terms', 'cp_terms_choice', array(
            'label' => 'diff.form.terms.label',
            'mapped' => false,
            'empty_value' => $options['data']['terms'] ? false : 'diff.form.terms.no_target',
            'required' => true,
            'final_only' => false,
            'exclude' => $options['exclude'],
            'include_none' => false,
            'attr' => array(
                'class' => 'form-control'
            )
        ));

        $builder->add('submit', 'submit', array(
            'label' => 'diff.form.submit',
            'attr' => array(
                'style' => 'width: 100%'
            )
        ));
    }

    /**
     *  {@inheritdoc}
     */
    public function getName()
    {
        return 'cp_terms_diff';
    }
}
