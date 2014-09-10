Add agreement widget on Sign up Form
====================================

User registration
-----------------

During registration (sign up), a user is typically required to agree to your application's terms of service. The CPTerms bundle makes it easy to implement this feature.

Ensure that doc on [frontend usage](Resources/doc/frontend.md) is followed, specially "Allow users to agree terms" part. Then,

Add a widget of type `cp_terms_agreement` to your signup form.

```php
class RegistrationFormType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // ...

        $builder->add(
            'agreement_for_latest_terms',
            'cp_terms_agreement',
            array(
                'label' => false
            )
        );
    }
}
```

To enable validation for the `cp_terms_agreement` widget, set `cascade_validation` to true:

```php
class RegistrationFormType
{
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            ...
            'cascade_validation' => true
        ));
    }
```

**Note:** Currently this works only when `User` class has the tos behavior.
