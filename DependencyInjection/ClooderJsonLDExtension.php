<?php

namespace Piktalent\Bundle\JsonLDBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Yaml\Yaml;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class PiktalentJsonLDExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));



        $schema=   $this->getContentsSchemaFile($config);
        $definition = new Definition('Piktalent\Bundle\JsonLDBundle\Service\AggregateSchemaRoute', [
            $schema
        ]);

        $container->setDefinition('json_ld.aggregate', $definition);

        $loader->load('services.xml');
    }


    public function getContentsSchemaFile(array $config = []){
        $content = file_get_contents($config['schemaFile']);
        $arraySchema = Yaml::parse($content);
        if(!array_key_exists('routes', $arraySchema)){
            throw new \RuntimeException('You schema config must begin by routes key');
        }
        return $arraySchema;
    }
}
