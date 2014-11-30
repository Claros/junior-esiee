<?php

namespace JuniorEsiee\BusinessBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use JMS\DiExtraBundle\Annotation as DI;
use JuniorEsiee\BusinessBundle\Entity\Project;

/**
 * @DI\FormType
 */
class ProjectType extends AbstractType
{
    /**
     * @DI\Inject("security.context")
     */
    public $securityContext;

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('clientLastName', null, array(
                'label' => 'Nom de famille'
            ))
            ->add('clientFirstName', null, array(
                'label' => 'Prénom'
            ))
            ->add('clientCompany', null, array(
                'required' => false,
                'label'    => 'Entreprise'
            ))
            ->add('clientPhone', null, array(
                'label' => 'Téléphone'
            ))
            ->add('clientEmail', null, array(
                'label' => 'Email'
            ))
            ->add('depositDate', 'date', array(
                'required' => false,
                'label'    => 'Date de dépôt'
            ))
            ->add('title', null, array(
                'required' => false,
                'label'    => 'Titre'
            ))
            ->add('description', null, array(
                'required' => false,
                'label'    => 'Description'
            ))
            ->add('delay', 'date', array(
                'required' => false,
                'label'    => 'Délais'
            ))
        ;

        if ($this->securityContext->isGranted('ROLE_ADMIN'))
        {
            $builder
                ->add('commercial', 'association_list', array(
                    'required'   => false,
                    'class'      => 'ApplicationSonataUserBundle:User',
                    'properties' => array('id', 'username'),
                ))
                ->add('rbu', 'association_list', array(
                    'required'   => false,
                    'class'      => 'ApplicationSonataUserBundle:User',
                    'properties' => array('id', 'username'),
                ))
                ->add('students', 'association_list', array(
                    'required'   => false,
                    'class'      => 'ApplicationSonataUserBundle:User',
                    'properties' => array('id', 'username'),
                    'multiple'   => true,
                ))
            ;
        }

        if ($this->securityContext->isGranted('ROLE_COMMERCIAL'))
        {
            $builder
                ->add('skillCategories', null, array(
                    'required' => false,
                    'multiple' => true,
                    'expanded' => true,
                    'label'    => 'Majeur(s)'
                ))
                ->add('skills', null, array(
                    'required' => false,
                    'multiple' => true,
                    'expanded' => true,
                    'label'    => 'Compétence(s)'
                ))
                ->add('state', 'choice', array(
                    'required'           => false,
                    'expanded'           => true,
                    'choices'            => array_combine(Project::getStates(), Project::getStates()),
                    'translation_domain' => 'JuniorEsieeBusinessBundle',
                    'label'              => 'Statut'
                ))
            ;
        }
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'JuniorEsiee\BusinessBundle\Entity\Project'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'junioresiee_businessbundle_project';
    }
}