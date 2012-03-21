<?php

namespace Iphp\CoreBundle\Manager;


use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAware;

class RubricManager extends ContainerAware
{
    protected $em;
    protected $request;


    protected $currentRubric = -1;

    public function __construct(EntityManager $em, ContainerInterface $container)
    {
        $this->em = $em;
        $this->request = $container->hasScope('request') && $container->isScopeActive('request') ?
                $container->get('request') : null;
        $this->setContainer($container);
    }


    public function getByPath($rubricPath)
    {
        return $this->getRepository()->findOneByFullPath($rubricPath);
    }


    /**
     * @return \Doctrine\ORM\EntityRepository
     */
    protected function getRepository()
    {
        return $this->em->getRepository('ApplicationIphpCoreBundle:Rubric');
    }

    /**
     * @return \Application\Iphp\CoreBundle\Entity\Rubric
     */
    public function getCurrent()
    {
        if ($this->currentRubric === -1)
            $this->currentRubric = $this->request && $this->request->get('_rubric') ?
                    $this->getByPath($this->request->get('_rubric')) : null;
        return $this->currentRubric;
    }


    public function generatePath($rubric, $absolute = false)
    {
        return $this->request->getBaseUrl() . (is_string($rubric) ? $rubric : $rubric->getFullPath());
    }


    /**
     * TODO : использовать стандартные методы очистки кэша
     * TODO: если большой поток запросов что будет
     */
    function clearCache()
    {
        $cacheDir = $this->container->getParameter('kernel.cache_dir');

        $kernel = $this->container->get('kernel');

        $cacheDir = realpath($cacheDir.'/../');


        foreach (array ('frontdev','frontprod')  as $env)
        {
            foreach (array ('UrlGenerator','UrlMatcher') as $file)
            {
                $cachedFile = $cacheDir.'/'.$env.'/app'.$env.$file.'.php';
                //print '<br>'.$cachedFile;
                if (file_exists($cachedFile)) unlink ($cachedFile);
            }
        }
        //exit();

    }

}