<?php


namespace App\Form\Type;

use App\Entity\Currency;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotNull;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'constraints' => [
                    new NotNull(),
                    new Length([
                        'max' => 100,
                        'min' => 2,
                    ]),
                ],
            ])
            ->add('description', TextType::class, [])
            ->add('currency', EntityType::class, [
                'class'       => Currency::class,
                'constraints' => [
                    new NotNull(),
                ],
            ])
            ->add('price', NumberType::class, [
                'constraints' => [
                    new NotNull(),
                    new GreaterThan([
                        'value' => 0,
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'allow_extra_fields' => true,
            'data_class' => Product::class,
        ]);
    }

}