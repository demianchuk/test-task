<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * @author Sergii Demianchuk <demianchuk.sergii@gmail.com>
 */
class GitHubUrlCompareType extends AbstractType
{
    /**
     * Builds form.
     *
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('urlFirst', TextType::class, array(
                'label' => 'compare_form.url_label_first',
                'required' => false,
                'attr' => [
                    'placeholder' => 'compare_form.url_placeholder_first',
                ],
            ))
            ->add('urlSecond', TextType::class, array(
                'label' => 'compare_form.url_label_second',
                'required' => false,
                'attr' => [
                    'placeholder' => 'compare_form.url_placeholder_second',
                ],
            ))
            ->add('submit', SubmitType::class, array(
                'label' => 'compare_form.submit_button',
                'attr' => array(
                    'class' => 'ui submit primary large button',
                ),
            ));
    }

    /**
     * Sets default options.
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => '\AppBundle\Entity\Statistic\RepoScoreStatistic',
            'translation_domain' => 'forms',
            'attr' => array(
                'class' => 'ui form',
            ),

        ));
    }

    /**
     * Returns a name of the form.
     *
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'git_hub_compare_url';
    }
}
