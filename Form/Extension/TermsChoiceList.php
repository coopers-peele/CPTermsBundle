<?php

namespace CP\Bundle\TermsBundle\Form\Extension;

use Criteria;
use IntlDateFormatter;

use CP\Bundle\TermsBundle\Propel\Terms;
use CP\Bundle\TermsBundle\Propel\TermsQuery;

use Symfony\Component\Form\Extension\Core\ChoiceList\ChoiceListInterface;
use Symfony\Component\Form\FormConfigBuilder;
use Symfony\Component\Form\Exception\InvalidConfigurationException;
use Symfony\Component\Form\Extension\Core\View\ChoiceView;
use Symfony\Component\Translation\TranslatorInterface;

class TermsChoiceList implements ChoiceListInterface
{
    protected $translator;

    protected $locale;

    protected $final_only;

    protected $exclude = array();

    protected $include_none;

    protected $date_format = 'dd/MM/yyyy HH:mm';

    protected $date_formatter;

   /**
     * The choices with their indices as keys.
     *
     * @var array
     */
    protected $choices = array();

    /**
     * The choice values with the indices of the matching choices as keys.
     *
     * @var array
     */
    protected $values = array();

    protected $none;

    private $final = array();

    private $drafts = array();

    public function __construct(
        TranslatorInterface $translator,
        $locale,
        $final_only = false,
        $exclude = array(),
        $include_none = false,
        $date_format = 'dd/MM/yyyy HH:mm')
    {
        $this->translator = $translator;
        $this->locale = $locale;
        $this->final_only = $final_only;
        $this->exclude = $exclude;
        $this->include_none = $include_none;
        $this->date_format = $date_format;

        if ($this->include_none) {
            $this->addNoneChoiceView();
        }

        foreach ($this->getTerms($final_only, $exclude) as $terms) {
            $this->addTerms($terms);
        }
    }

   /**
     * {@inheritdoc}
     */
    public function getChoices()
    {
        return $this->choices;
    }

    /**
     * {@inheritdoc}
     */
    public function getValues()
    {
        return $this->values;
    }

    /**
     * {@inheritdoc}
     */
    public function getPreferredViews()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getRemainingViews()
    {
        $views = array();

        if ($this->include_none) {
            $views = $this->none;
        }

        if (count($this->final) > 0) {
            $views['form.type.terms_choice.final.legend'] = $this->final;
        }

        if (count($this->drafts) > 0) {
            $views['form.type.terms_choice.drafts.legend'] = $this->drafts;
        }

        return $views;
    }

    /**
     * {@inheritdoc}
     */
    public function getChoicesForValues(array $values)
    {
        $choices = array();

        foreach ($values as $i => $givenValue) {
            foreach ($this->values as $j => $value) {
                if ($value === $givenValue) {
                    $choices[$i] = $this->choices[$j];
                    unset($values[$i]);

                    if (0 === count($values)) {
                        break 2;
                    }
                }
            }
        }

        return $choices;
    }

    /**
     * {@inheritdoc}
     */
    public function getValuesForChoices(array $choices)
    {
        $values = array();

        foreach ($choices as $i => $givenChoice) {
            foreach ($this->choices as $j => $choice) {
                if ($choice === $givenChoice) {
                    $values[$i] = $this->values[$j];
                    unset($choices[$i]);

                    if (0 === count($choices)) {
                        break 2;
                    }
                }
            }
        }

        return $values;
    }

    /**
     * {@inheritdoc}
     *
     * @deprecated Deprecated since version 2.4, to be removed in 3.0.
     */
    public function getIndicesForChoices(array $choices)
    {
        $indices = array();

        foreach ($choices as $i => $givenChoice) {
            foreach ($this->choices as $j => $choice) {
                if ($choice === $givenChoice) {
                    $indices[$i] = $j;
                    unset($choices[$i]);

                    if (0 === count($choices)) {
                        break 2;
                    }
                }
            }
        }

        return $indices;
    }

    /**
     * {@inheritdoc}
     *
     * @deprecated Deprecated since version 2.4, to be removed in 3.0.
     */
    public function getIndicesForValues(array $values)
    {
        $indices = array();

        foreach ($values as $i => $givenValue) {
            foreach ($this->values as $j => $value) {
                if ($value === $givenValue) {
                    $indices[$i] = $j;
                    unset($values[$i]);

                    if (0 === count($values)) {
                        break 2;
                    }
                }
            }
        }

        return $indices;
    }

    protected function getTerms($final_only = false, $exclude = array())
    {
        return TermsQuery::create()
            ->_if($final_only)
                ->finalized()
            ->_endIf()
            ->_if(count($exclude) > 0)
                ->filterById($exclude, Criteria::NOT_IN)
            ->_endIf()
            ->orderByFinalizedAt('desc')
            ->orderByVersion('asc')
            ->find();
    }

    protected function addNoneChoiceView()
    {
        $index = $this->createIndex();

        $value = 0;

        $label = 'form.type.terms_choice.none';

        $view  = new ChoiceView(null, $value, $label);

        $this->choices[$index] = null;
        $this->values[$index] = $value;
        $this->none[$index] = $view;
    }

    protected function addTerms(Terms $terms)
    {
        $index = $this->createIndex($terms);

        if ('' === $index || null === $index || !FormConfigBuilder::isValidName((string) $index)) {
            throw new InvalidConfigurationException(sprintf('The index "%s" created by the choice list is invalid. It should be a valid, non-empty Form name.', $index));
        }

        $value = $this->createValue($terms);

        $label = $this->createLabel($terms);

        $view = new ChoiceView($terms, $value, $label);

        $this->choices[$index] = $terms;
        $this->values[$index] = $value;

        if ($terms->isFinal()) {
            $this->final[$index] = $view;
        } else {
            $this->drafts[$index] = $view;
        }
    }

   /**
     * Creates a new unique index for this choice.
     *
     * Extension point to change the indexing strategy.
     *
     * @param mixed $choice The choice to create an index for
     *
     * @return int|string A unique index containing only ASCII letters,
     *                    digits and underscores.
     */
    protected function createIndex($choice = null)
    {
        return count($this->choices);
    }

    protected function createValue(Terms $terms)
    {
        return (string) $terms->getId();
    }

    protected function createLabel(Terms $terms)
    {
        $translator = $this->getTranslator();

        if ($terms->isFinal()) {
            $formatter = new IntlDateFormatter(
                $this->getLocale(),
                IntlDateFormatter::LONG,
                IntlDateFormatter::LONG
            );

            $formatter->setPattern($this->getDateFormat());

            $finalized_at = $formatter->format($terms->getFinalizedAt(null));

            return $translator->trans(
                'form.type.terms_choice.final.label',
                array(
                    '%version%' => $terms->getVersion(),
                    '%finalized_at%' => $finalized_at
                ),
                'CPTermsBundle',
                $this->getLocale()
            );
        } else {
            return $translator->trans(
                'form.type.terms_choice.drafts.label',
                array(
                    '%version%' => $terms->getVersion()
                ),
                'CPTermsBundle',
                $this->getLocale()
            );
        }
    }

    protected function getLocale()
    {
        return $this->locale;
    }

    protected function getDateFormat()
    {
        return $this->date_format;
    }

    protected function getTranslator()
    {
        return $this->translator;
    }

    protected function getTranslationDomain()
    {
        return $this->translation_domain;
    }
}
