<?php

declare(strict_types=1);

namespace Tests;

use App\Controller\MessageParser;
use PHPUnit\Framework\TestCase;

include_once __DIR__ . '/../vendor/autoload.php';

class MessageParserTest extends TestCase {
	/** 
	 * @dataProvider validCommitMessageDataProvider
	 */
	public function testValidCommitMessage($message, $expectedTags, $expectedTaskId, $expectedTitle, $expectedDetails, $expectedBcBreaks, $expectedTodos): void {
		$parser = new MessageParser();
		$commitMessage = $parser->parse($message);
		$this->assertEqualsCanonicalizing($expectedTags, $commitMessage->getTags());
		$this->assertSame($expectedTitle, $commitMessage->getTitle());
		$this->assertSame($expectedTaskId, $commitMessage->getTaskId());
		$this->assertEqualsCanonicalizing($expectedDetails, $commitMessage->getDetails());
		$this->assertEqualsCanonicalizing($expectedBcBreaks, $commitMessage->getBCBreaks());
		$this->assertEqualsCanonicalizing($expectedTodos, $commitMessage->getTodos());
	}


	public static function validCommitMessageDataProvider(): array
	{
		return [
			[
				"[add] [feature] @core #123456 Integrovat Premier: export objednávek

				* Export objednávek cronem co hodinu.
				* Export probíhá v dávkách.
				
				BC: Refaktorovaný BaseImporter.
				
				Feature: Nový logger.
				
				TODO: Refactoring autoemail modulu.",
				['add', 'feature'],
				123456,
				"Integrovat Premier: export objednávek",
				['Export objednávek cronem co hodinu.', 'Export probíhá v dávkách.'],
				['Refaktorovaný BaseImporter.'],
				['Refactoring autoemail modulu.'],
			],
			[
				"[add] [feature] [git] Integrovat Premier: export objednávek
				
				BC: Refaktorovaný BaseImporter.
				BC: More bcBreaks.
				
				Feature: Nový logger.
				
				TODO: Refactoring autoemail modulu.
				TODO: Refactoring autoemail modulu.",
				['add', 'feature', 'git'],
				null,
				"Integrovat Premier: export objednávek",
				[],
				['Refaktorovaný BaseImporter.', 'More bcBreaks.'],
				['Refactoring autoemail modulu.', 'Refactoring autoemail modulu.'],
			]
		];
	}


}
