<?php

namespace AppBundle\Form;

use Faker\Provider\zh_TW\Text;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


class UsersType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nickname' , TextType::class, 
            array(
                'label' => "Prenom",
                'constraints' => array(
                    new NotBlank(  ['message' => "Veuillez entrez votre prénom"]),
                    new Length(array(
                        'max'        => 100,
                        'maxMessage' => 'Votre prénom ne peut pas faire plus de 100 caracteres',
                        )
                    ),
                    new Regex(array(
                        'pattern' => '/^/',
                        'message' => ""
                        )
                    )
                )
            )
        )
        ->add('password', TextType::class,
            array(
                'label' => "Password",
                'constraints' => array(
                    new NotBlank(['message' => "Veullez entrez votre mots de passe"]),
                    new Length(
                        array(
                            'min' => 8,
                            'max' => 100,
                            'maxMessage' => 'mots de passe a la bonne longueurs',
                        )
                    ),
                    new Regex(
                        array(
                            'pattern' => '/^\w{8,}/',
                            'message' => "au moins 8 caractéres alphanumerique"
                        )
                    )
                )
            )
        
        )
        ->add('email', EmailType::class,
                array(
                    'constraints' =>
                    [
                        new Email(
                            array(
                                'message' => 'Votre email "{{ value }}" n\'est pas valide.',
                                'checkMX' => true,
                            )
                        )
                    ]
                )
            )
            // array(
            //     'label' => "Email",
            //     'constraints' => array(
            //         new NotBlank(['message' => "Veuillez renseigner votre email"]),
            //         new Length(
            //             array(
            //                 'max'        => 100,
            //                 'maxMessage' => '',
            //             )
            //         ),
            //         new Regex(
            //             array(
            //                 'pattern' => '/^/',
            //                 'message' => "au moins 8 caractéres alphanumerique"
            //             )
            //         )
            //     )
            // ) 
                  
        
        ->add('zipcode', TextType::class,
            array(
                'label' => "Code postal",
                'constraints' => array(
                    new NotBlank(['message' => "Veuillez renseigner votre code postal"]),
                    new Length(
                        array(
                            'max'        => 15,
                            'maxMessage' => '',
                        )
                    ),
                    new Regex(
                        array(
                            'pattern' => '/^\d{5}|(A,B){2}\d{3}/',
                            'message' => "5 chiffres ou 2( A ou B) 3chiffres"
                        )
                    )
                )
            )           
        )
        ->add('phone', TextType::class,
            array(
                'label' => "Phone",
                'constraints' => array(
                    new NotBlank(['message' => "Numéro de téléphone"]),
                    new Length(
                        array(
                            'min' => 8,
                            'max' => 15,
                            'maxMessage' => '',
                        )
                    ),
                    new Regex(
                        array(
                            'pattern' => '/^\+?\d{8,15}/',
                            'message' => "Entre 8 et 10 chiffres s.v.p"
                        )
                    )
                )
            )           
        )
        ->add('city', TextType::class,
            array(
                'label' => "City",
                'constraints' => array(
                    new NotBlank(['message' => "Votre City"]),
                    new Length(
                        array(
                            'max'        => 150,
                            'maxMessage' => '',
                        )
                    ),
                    new Regex(
                        array(
                            'pattern' => '/^/',
                            'message' => ""
                        )
                    )
                )
            )        
        )
        ->add('photo', FileType::class, array('required' => false));
    }
    
    
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {

        //  EMAIL DOIT ETRE UNIQUE
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Users',

            // verifie que l'email est unique

            'constraints' => array(new UniqueEntity(array(
                'fields' => array('email'),
                'message' => 'Cet email est déja utilisé'
            )))
        ));
    }



    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_users';
    }


}
