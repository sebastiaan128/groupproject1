<?php

namespace App\Form;

use App\Entity\Profiel;
use App\Entity\Tags;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, ['required' => false])
            ->add('email', EmailType::class, ['required' => false])
            ->add('studie', TextType::class, ['required' => false])
            ->add('jaar', IntegerType::class, ['required' => false])
            ->add('bio', TextType::class, ['required' => false])
            ->add('tags', EntityType::class, [
                'class' => Tags::class,
                'choice_label' => 'naam',
                'multiple' => true,
                'expanded' => true,
                'required' => false,
                'attr' => ['class' => 'tags-checkboxes'],
            ]);
    }
    

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Profiel::class,
        ]);
    }
}
