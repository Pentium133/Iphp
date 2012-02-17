<?php

namespace Iphp\CoreBundle\Model;


use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

class RubricFactory
{
    protected $em;
    protected $request;


    protected $currentRubric = -1;

    public function __construct(EntityManager $em, ContainerInterface $container)
    {
      $this->em = $em;
      $this->request = $container->hasScope('request') && $container->isScopeActive('request') ?
                $container->get('request') : null;
    }


    public function getByPath ($rubricPath)
    {
        return $this->getRepository()->findOneByFullPath ($rubricPath);
    }


    protected function getRepository()
    {
        return $this->em->getRepository('ApplicationIphpCoreBundle:Rubric');
    }

    public function getCurrent()
    {
        if ($this->currentRubric === -1)
        $this->currentRubric = $this->request && $this->request->get('_rubric') ?
                $this->getByPath($this->request->get('_rubric')) :  null;
        return $this->currentRubric;
    }


    public function generatePath($rubric, $absolute = false)
    {
        return $this->request->getBaseUrl().$rubric->getFullPath();
    }



}