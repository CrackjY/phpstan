<?php declare(strict_types = 1);

namespace PHPStan\Type;

use PHPStan\TrinaryLogic;
use PHPStan\Type\Constant\ConstantArrayType;
use PHPStan\Type\Constant\ConstantIntegerType;

class ArrayTypeTest extends \PHPStan\Testing\TestCase
{

	public function dataIsSuperTypeOf(): array
	{
		return [
			[
				new ArrayType(new MixedType(), new StringType()),
				new ArrayType(new MixedType(), new StringType()),
				TrinaryLogic::createYes(),
			],
			[
				new ArrayType(new MixedType(), new StringType()),
				new ArrayType(new MixedType(), new IntegerType()),
				TrinaryLogic::createNo(),
			],
			[
				new ArrayType(new MixedType(), new StringType()),
				new ArrayType(new IntegerType(), new StringType()),
				TrinaryLogic::createYes(),
			],
			[
				new ArrayType(new IntegerType(), new StringType()),
				new ArrayType(new MixedType(), new StringType()),
				TrinaryLogic::createMaybe(),
			],
			[
				new ArrayType(new IntegerType(), new StringType()),
				new ArrayType(new StringType(), new StringType()),
				TrinaryLogic::createNo(),
			],
			[
				new ArrayType(new MixedType(), new MixedType(), false),
				new CallableType(),
				TrinaryLogic::createMaybe(),
			],
			[
				new ArrayType(new IntegerType(), new StringType()),
				new ConstantArrayType([], []),
				TrinaryLogic::createYes(),
			],
		];
	}

	/**
	 * @dataProvider dataIsSuperTypeOf
	 * @param ArrayType $type
	 * @param Type $otherType
	 * @param TrinaryLogic $expectedResult
	 */
	public function testIsSuperTypeOf(ArrayType $type, Type $otherType, TrinaryLogic $expectedResult): void
	{
		$actualResult = $type->isSuperTypeOf($otherType);
		$this->assertSame(
			$expectedResult->describe(),
			$actualResult->describe(),
			sprintf('%s -> isSuperTypeOf(%s)', $type->describe(VerbosityLevel::value()), $otherType->describe(VerbosityLevel::value()))
		);
	}

	public function dataAccepts(): array
	{
		return [
			[
				new ArrayType(new MixedType(), new StringType()),
				new UnionType([
					new ConstantArrayType([], []),
					new ConstantArrayType(
						[new ConstantIntegerType(0)],
						[new MixedType()]
					),
					new ConstantArrayType([
						new ConstantIntegerType(0),
						new ConstantIntegerType(1),
					], [
						new StringType(),
						new MixedType(),
					]),
				]),
				true,
			],
		];
	}

	/**
	 * @dataProvider dataAccepts
	 * @param ArrayType $acceptingType
	 * @param Type $acceptedType
	 * @param bool $expectedResult
	 */
	public function testAccepts(
		ArrayType $acceptingType,
		Type $acceptedType,
		bool $expectedResult
	): void
	{
		$actualResult = $acceptingType->accepts($acceptedType);
		$this->assertSame(
			$expectedResult,
			$actualResult,
			sprintf('%s -> accepts(%s)', $acceptingType->describe(VerbosityLevel::value()), $acceptedType->describe(VerbosityLevel::value()))
		);
	}

}
