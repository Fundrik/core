<?php

declare(strict_types=1);

namespace Fundrik\Core\Tests;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

abstract class MockeryTestCase extends FundrikTestCase {

	// phpcs:ignore SlevomatCodingStandard.Classes.TraitUseSpacing.IncorrectLinesCountAfterLastUse
	use MockeryPHPUnitIntegration;

	protected function array_has( array $expected ): \Mockery\Matcher\Closure {

		return \Mockery::on(
			static function ( $data ) use ( $expected ) {

				if ( ! is_array( $data ) ) {
					return false;
				}

				foreach ( $expected as $k => $v ) {

					if ( ! array_key_exists( $k, $data ) ) {
						return false;
					}

					$actual = $data[ $k ];

					if ( $v instanceof \Closure ) {

						if ( ! $v( $actual ) ) {
							return false;
						}
					} elseif ( $v instanceof \Mockery\Matcher\MatcherInterface ) {

						if ( ! $v->match( $actual ) ) {
							return false;
						}
					} elseif ( $actual !== $v ) {
						return false;
					}
				}

				return true;
			},
		);
	}
}
