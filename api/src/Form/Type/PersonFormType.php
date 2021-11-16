<?php

namespace App\Form\Type;

use App\Form\Model\PersonDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PersonFormType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('id', IntegerType::class)
			->add('club', IntegerType::class)
			->add('name', TextType::class)
			->add('profile', TextType::class)
			->add('salary', IntegerType::class)
			->add('position', TextType::class)
			->add('birthDate', DateType::class, [
				'widget' => 'single_text',
				'format' => 'yyyy-MM-dd'
			]);
	}

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'data_class' => PersonDto::class
		]);
	}

	public function getBlockPrefix()
	{
		return '';
	}

	public function getName()
	{
		return '';
	}
}