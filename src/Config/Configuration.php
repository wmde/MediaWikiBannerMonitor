<?php

namespace BannerMonitor\Config;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

/**
 * @licence GNU GPL v2+
 * @author Christoph Fischer
 */
class Configuration implements ConfigurationInterface {

	public function getConfigTreeBuilder() {
		$treeBuilder = new TreeBuilder();
		$rootNode = $treeBuilder->root( 'centralnoticeallocations' );

		$rootNode
			->children()
			->scalarNode( 'project' )
			->defaultValue( '' )
			->end()
			->scalarNode( 'country' )
			->defaultValue( '' )
			->end()
			->scalarNode( 'language' )
			->defaultValue( '' )
			->end()
			->end();

		return $treeBuilder;
	}
} 