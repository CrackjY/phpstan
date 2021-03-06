<?php declare(strict_types = 1);

namespace PHPStan\Rules\Arrays;

class AppendedArrayKeyTypeRuleTest extends \PHPStan\Testing\RuleTestCase
{

	protected function getRule(): \PHPStan\Rules\Rule
	{
		return new AppendedArrayKeyTypeRule(true);
	}

	public function testRule(): void
	{
		$this->analyse([__DIR__ . '/data/appended-array-key.php'], [
			[
				'Array (array<int, mixed>) does not accept key int|string.',
				28,
			],
			[
				'Array (array<int, mixed>) does not accept key string.',
				34,
			],
			[
				'Array (array<string, mixed>) does not accept key int.',
				37,
			],
			[
				'Array (array<string, mixed>) does not accept key int|string.',
				43,
			],
			[
				'Array (array<string, mixed>) does not accept key 0.',
				58,
			],
		]);
	}

}
