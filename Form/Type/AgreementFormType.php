<?php

namespace CP\Bundle\TermsBundle\Form\Type;

use Propel\PropelBundle\Form\BaseAbstractType;

use Symfony\Component\Form\FormBuilderInterface;

class AgreementFormType extends BaseAbstractType
{
    protected $options = array(
        'data_class' => 'CP\Bundle\TermsBundle\Propel\Agreement',
        'translation_domain' => 'CPTermsBundle',
        'name' => 'cp_terms_agreement',
        'intention' => 'cp_terms_agreement',
        'validation_groups' => array('CP_TERMS')
    );

    /**
     *  {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('agreed_at', 'cp_terms_agreement_date',
            array(
                'label' => false,
                'required' => true
            )
        );
    }
}
