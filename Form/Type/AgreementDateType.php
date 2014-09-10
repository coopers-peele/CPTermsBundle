<?php

namespace CP\Bundle\TermsBundle\Form\Type;

use CP\Bundle\TermsBundle\Form\DataTransformer\DateToBooleanTransformer;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AgreementDateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer(new DateToBooleanTransformer());
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'translation_domain' => 'CPTermsBundle'
        ));
    }

    public function getParent()
    {
        return 'checkbox';
    }

    public function getName()
    {
        return 'cp_terms_agreement_date';
    }
}
