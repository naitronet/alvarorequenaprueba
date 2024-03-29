<?php

namespace App\Form\Type;

use App\Form\Model\ClubDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClubFormType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('name', TextType::class)
			->add('base64Image', TextType::class)
			->add('salary_limit', IntegerType::class)
			->add('persons', CollectionType::class, [
				'allow_add' => true,
				'allow_delete' => true,
				'entry_type' => PersonFormType::class
			]);
	}

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'data_class' => ClubDto::class
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