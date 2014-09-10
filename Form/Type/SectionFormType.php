<?php

namespace CP\Bundle\TermsBundle\Form\Type;

use Propel\PropelBundle\Form\BaseAbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class SectionFormType extends BaseAbstractType
{
    protected $options = array(
        'data_class' => 'CP\Bundle\TermsBundle\Propel\Section',
        'translation_domain' => 'CPTermsBundle',
        'name' => 'section',
        'intention' => 'section'
    );

    /**
     *  {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('title', 'text', array(
            'label' => 'section.form.title.label',
            'required' => true
        ));

        $builder->add('content', 'textarea', array(
            'label' => 'section.form.content.label',
            'required' => false,
            'attr' => array(
                'rows' => 15
            )
        ));

        $builder->add('save', 'submit', array(
            'label' => 'section.form.submit',
        ));

        $builder->add('cancel', 'button', array(
            'label' => 'section.form.cancel',
            'attr' => array(
                'class' => 'cancel'
            )
        ));
}

}
