<?php

/*
 *
 *     _      ____    _  __     _      _   _   ___      _                 _   _   _____   _____  __        __   ___    ____    _  __
 *    / \    |  _ \  | |/ /    / \    | \ | | |_ _|    / \               | \ | | | ____| |_   _| \ \      / /  / _ \  |  _ \  | |/ /
 *   / _ \   | |_) | | ' /    / _ \   |  \| |  | |    / _ \     _____    |  \| | |  _|     | |    \ \ /\ / /  | | | | | |_) | | ' /
 *  / ___ \  |  _ <  | . \   / ___ \  | |\  |  | |   / ___ \   |_____|   | |\  | | |___    | |     \ V  V /   | |_| | |  _ <  | . \
 * /_/   \_\ |_| \_\ |_|\_\ /_/   \_\ |_| \_| |___| /_/   \_\            |_| \_| |_____|   |_|      \_/\_/     \___/  |_| \_\ |_|\_\
 *
 * Arkania is a Minecraft Bedrock server created in 2019,
 * we mainly use PocketMine-MP to create content for our server
 * but we use something else like WaterDog PE
 *
 * @author Arkania-Team
 * @link https://arkaniastudios.com
 *
 */

declare(strict_types=1);

if (count($argv) !== 2) {
	fwrite(STDERR, "Provide a path to process\n");
	exit(1);
}
/**
 * @param object[]|string[]|int[] $members
 */
function generateMethodAnnotations(string $namespaceName, array $members, string $file) : string {
	$selfName = basename(__FILE__);
	$lines = ["/**"];
	$lines[] = " * This doc-block is generated automatically, do not modify it manually.";
	$lines[] = " * This must be regenerated whenever registry members are added, removed or changed.";
	$lines[] = " * @see build/$selfName";
	$lines[] = " * @generate-registry-docblock";
	$lines[] = " *";
	static $lineTmpl = " * @method static %2\$s %s()";
	$memberLines = [];
	$uses = getUses($file);
	foreach ($members as $name => $member) {
		if (is_object($member)) {
			$reflect = new ReflectionClass($member);
			while ($reflect !== false && $reflect->isAnonymous()) {
				$reflect = $reflect->getParentClass();
			}
			if ($reflect === false) {
				$typehint = "object";
			} elseif ($reflect->getNamespaceName() === $namespaceName || in_array($reflect->getName(), $uses, true)) {
				$typehint = $reflect->getShortName();
			} else {
				$typehint = '\\' . $reflect->getName();
			}
			$accessor = mb_strtoupper($name);
			$memberLines[$accessor] = sprintf($lineTmpl, $accessor, $typehint);
		} else {
			//Add support for MixedRegistryTrait
			$memberLines[$name] = sprintf($lineTmpl, $name, gettype($member));
		}
	}
	ksort($memberLines, SORT_STRING);
	foreach ($memberLines as $line) {
		$lines[] = $line;
	}
	$lines[] = " */";

	return implode("\n", $lines);
}

/**
 * @return string[]
 */
function getUses(string $filePath) : array {
	$contents = file_get_contents($filePath);
	if ($contents === false) {
		die();
	}
	$useStatements = [];
	if (preg_match_all('/\buse\s+(.+?);/', $contents, $matches)) {
		foreach ($matches[1] as $match) {
			$match = trim($match);
			if (str_contains($match, ' as ')) {
				[$namespace, $alias] = explode(' as ', $match);
				$namespace = trim($namespace);
				$alias = trim($alias);
				$useStatements[$alias] = $namespace;
			} else {
				$useStatements[$match] = $match;
			}
		}
	}
	$uses = [];
	foreach ($useStatements as $alias => $namespace) {
		$uses[] = $namespace . ($alias !== $namespace? " as $alias" : "");
	}

	return $uses;
}

function processFile(string $file) : void {
	$contents = file_get_contents($file);
	if ($contents === false) {
		throw new RuntimeException("Failed to get contents of $file");
	}
	if (preg_match("/(*ANYCRLF)^namespace (.+);$/m", $contents, $matches) !== 1 || preg_match('/(*ANYCRLF)^((final|abstract)\s+)?class /m', $contents) !== 1) {
		return;
	}
	$shortClassName = basename($file, ".php");
	$className = $matches[1] . "\\" . $shortClassName;
	$path = dirname(__DIR__) . "\\src\\" . $className . ".php";
	include $path;
	if (!class_exists($className)) {
		return;
	}
	$reflect = new ReflectionClass($className);
	$docComment = $reflect->getDocComment();
	if ($docComment === false || preg_match("/(*ANYCRLF)^\s*\*\s*@generate-registry-docblock$/m", $docComment) !== 1) {
		return;
	}
	echo "Found registry in $file\n";
	$replacement = generateMethodAnnotations($matches[1], $className::getAll(), $file);
	$newContents = str_replace($docComment, $replacement, $contents);
	if ($newContents !== $contents) {
		echo "Writing changed file $file\n";
		file_put_contents($file, $newContents);
	} else {
		echo "No changes made to file $file\n";
	}
}

require dirname(__DIR__) . '/vendor/autoload.php';
if (is_dir($argv[1])) {
	/** @var string $file */
	foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($argv[1], FilesystemIterator::SKIP_DOTS | FilesystemIterator::CURRENT_AS_PATHNAME)) as $file) {
		if (!str_ends_with($file, ".php")) {
			continue;
		}
		processFile($file);
	}
} else {
	processFile($argv[1]);
}
