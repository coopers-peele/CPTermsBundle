<?php

namespace CP\Bundle\TermsBundle\Form\Type;

use Propel\PropelBundle\Form\BaseAbstractType;

use Symfony\Component\Form\FormBuilderInterface;

class TermsFormType extends BaseAbstractType
{
    protected $options = array(
        'data_class' => 'CP\Bundle\TermsBundle\Propel\Terms',
        'translation_domain' => 'CPTermsBundle',
        'name' => 'terms',
        'intention' => 'terms'
    );

    /**
     *  {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('version', 'text', array(
            'label' => 'terms.form.version.label',
            'required' => true
        ));

        $builder->add('description', 'textarea', array(
            'label' => 'terms.form.description.label',
            'required' => false,
            'attr' => array(
                'rows' => 15
            )
        ));

        $builder->add('save', 'submit', array(
            'label' => 'terms.form.submit',
        ));
    }
}
