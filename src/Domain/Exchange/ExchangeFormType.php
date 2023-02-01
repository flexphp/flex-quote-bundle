<?php declare(strict_types=1);
/*
 * This file is part of FlexPHP.
 *
 * (c) Freddie Gar <freddie.gar@outlook.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace FlexPHP\Bundle\QuoteBundle\Domain\Exchange;

use App\Form\Type\DatetimepickerType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type as InputType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class ExchangeFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('date', DatetimepickerType::class, [
            'label' => 'label.date',
            'required' => true,
        ]);

        $builder->add('currency', InputType\TextType::class, [
            'label' => 'label.currency',
            'required' => true,
            'attr' => [
                'maxlength' => 3,
            ],
        ]);

        $builder->add('quote', InputType\NumberType::class, [
            'label' => 'label.quote',
            'required' => true,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'translation_domain' => 'exchange',
        ]);
    }
}
