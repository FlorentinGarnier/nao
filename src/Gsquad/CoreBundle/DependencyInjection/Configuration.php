<?php
/**
 * Created by PhpStorm.
 * User: aigie
 * Date: 23/01/2017
 * Time: 10:53
 */

namespace Gsquad\CoreBundle\DependencyInjection;


use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('gsquad.aws_s3.client');

        $rootNode
            ->children()
                ->arrayNode('aws_s3')
                    ->children()
                        ->scalarNode('aws_key')->end()
                        ->scalarNode('aws_secret')->end()
                        ->scalarNode('base_url')->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}