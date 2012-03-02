<?php



namespace Iphp\ContentBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Definition\Processor;
//use Symfony\Component\DependencyInjection\Definition;


class IphpContentExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

       // $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
       // $loader->load('services.xml');


        if (substr ($container->getParameter('kernel.environment'),0,5) == 'admin')
        {
            $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
            $loader->load('admin.xml');
        }



      //  $loader->load('orm.xml');
      //  $loader->load('twig.xml');
      //  $loader->load('form.xml');

      //  $blog = new Definition('Sonata\NewsBundle\Model\Blog', array($config['title'], $config['link'], $config['description']));
      //  $container->setDefinition('sonata.news.blog', $blog);
    }

}