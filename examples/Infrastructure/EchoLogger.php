<?php

declare(strict_types=1);

// phpcs:disable FundrikStandard.Commenting.SinceTagRequired.MissingSince
// phpcs:disable Squiz.Commenting.ClassComment.Missing
// phpcs:disable Squiz.Commenting.FunctionComment.Missing
// phpcs:disable FundrikStandard.Classes.FinalClassMustBeReadonly.FinalClassNotReadonly
// phpcs:disable SlevomatCodingStandard.TypeHints.ParameterTypeHint.MissingTraversableTypeHintSpecification
// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
// phpcs:disable WordPress.PHP.DevelopmentFunctions.error_log_print_r

namespace Fundrik\Core\Examples\Infrastructure;

use Psr\Log\AbstractLogger;

final class EchoLogger extends AbstractLogger {

	public function log( mixed $level, string|\Stringable $message, array $context = [] ): void {

		echo '<pre>';
		echo "{$level}: {$message}\n";
		print_r( $context );
		echo '</pre>';
	}
}

// phpcs:enable