<?php

namespace BannerMonitor\Config;

use DateTime;
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
				->arrayNode( 'banners' )
					->useAttributeAsKey( 'name' )
					->prototype( 'array' )
						->children()
							->scalarNode( 'name' )->end()
							->scalarNode( 'project' )->end()
							->scalarNode( 'country' )->end()
							->scalarNode( 'language' )->end()
							->booleanNode( 'anonymous' )->end()
							->scalarNode( 'device' )->end()
							->integerNode( 'bucket' )->end()
							->scalarNode( 'start' )
								->validate()
									->ifTrue( function ( $s ) {
										return !$this->validateDate( $s, 'Y-m-d H:i' );
									} )
									->thenInvalid( 'Invalid Date' )
								->end()
							->end()
							->scalarNode( 'end' )
								->validate()
									->ifTrue( function ( $s ) {
										return !$this->validateDate( $s, 'Y-m-d H:i' );
									} )
									->thenInvalid( 'Invalid Date' )
								->end()
							->end()
								->end()
								->end()
								->end()

								->end();

		return $treeBuilder;
	}

	private function validateDate( $date, $format ) {
		$d = DateTime::createFromFormat( $format, $date );
		return $d && $d->format( $format ) == $date;
	}
} 