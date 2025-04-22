<?php

namespace App\Form;

use App\Entity\ScrapeConfig;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ScrapeConfigType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('domain', TextType::class, ['label' => 'Domain'])
            ->add('overview_xpath', TextType::class, ['label' => 'Overview XPath', 'required' => false])
            ->add('detail_xpath', TextType::class, ['label' => 'Detail XPath', 'required' => false])
            ->add('title_xpath', TextType::class, ['label' => 'Title XPath', 'required' => false])
            ->add('price_xpath', TextType::class, ['label' => 'Price XPath', 'required' => false])
            ->add('description_xpath', TextType::class, ['label' => 'Description XPath', 'required' => false])
            ->add('surface_xpath', TextType::class, ['label' => 'Surface XPath', 'required' => false])
            ->add('bedrooms_xpath', TextType::class, ['label' => 'Bedrooms XPath', 'required' => false])
            ->add('photo_xpath', TextType::class, ['label' => 'Photo XPath', 'required' => false]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ScrapeConfig::class,
        ]);
    }
}
