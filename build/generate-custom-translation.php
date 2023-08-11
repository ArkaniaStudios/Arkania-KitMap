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

use pocketmine\lang\Translatable;
use Symfony\Component\Filesystem\Path;

require dirname(__DIR__) . '/vendor/autoload.php';

function constantify(string $permissionName) : string {
	return strtoupper(str_replace([".", "-"], "_", $permissionName));
}

function functionify(string $permissionName) : string {
	return strtoupper(str_replace([".", "-"], "_", $permissionName));
}

const SHARED_HEADER = <<<'HEADER'
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

namespace arkania\language;

HEADER;

function stringifyKey(array $array) : Generator {
	foreach ($array as $key => $value) {
		yield (string) $key => $value;
	}
}

/**
 * @param string[] $languageDefinitions
 * @phpstan-param array<string, string> $languageDefinitions
 */
function generate_known_translation_keys(array $languageDefinitions) : void {
	ob_start();
	echo SHARED_HEADER;
	echo <<<'HEADER'
/**
 * This class contains constants for all the translations known to PocketMine-MP as per the used version of pmmp/Language.
 * This class is generated automatically, do NOT modify it by hand.
 */
final class CustomTranslationKeys{

HEADER;
	ksort($languageDefinitions, SORT_STRING);
	foreach (stringifyKey($languageDefinitions) as $k => $_) {
		echo "\tpublic const ";
		echo constantify($k);
		echo " = \"" . $k . "\";\n";
	}
	echo "}\n";
	file_put_contents(dirname(__DIR__) . '/src/arkania/language/CustomTranslationKeys.php', ob_get_clean());
	echo "Done generating CustomTranslationKeys.\n";
}

/**
 * @param string[] $languageDefinitions
 * @phpstan-param array<string, string> $languageDefinitions
 */
function generate_known_translation_factory(array $languageDefinitions) : void {
	ob_start();
	echo SHARED_HEADER;
	echo <<<'HEADER'


use pocketmine\lang\Translatable;

/**
 * This class contains constants for all the translations known to PocketMine-MP as per the used version of pmmp/Language.
 * This class is generated automatically, do NOT modify it by hand.
 */
final class CustomTranslationFactory{

HEADER;
	ksort($languageDefinitions, SORT_STRING);
	$parameterRegex = '/{%(.+?)}/';
	$translationContainerClass = (new ReflectionClass(Translatable::class))->getShortName();
	foreach (stringifyKey($languageDefinitions) as $key => $value) {
		$parameters = [];
		$allParametersPositional = true;
		if (preg_match_all($parameterRegex, $value, $matches) > 0) {
			foreach ($matches[1] as $parameterName) {
				if (is_numeric($parameterName)) {
					$parameters[$parameterName] = "param$parameterName";
				} else {
					$parameters[$parameterName] = $parameterName;
					$allParametersPositional = false;
				}
			}
		}
		if ($allParametersPositional) {
			ksort($parameters, SORT_NUMERIC);
		}
		echo "\tpublic static function " .
			strtolower(functionify($key)) .
			"(" . implode(", ", array_map(fn (string $paramName) => "$translationContainerClass|string \$$paramName", $parameters)) . ") : $translationContainerClass{\n";
		echo "\t\treturn new $translationContainerClass(CustomTranslationKeys::" . constantify($key) . ", [";
		foreach ($parameters as $parameterKey => $parameterName) {
			echo "\n\t\t\t";
			if (!is_numeric($parameterKey)) {
				echo "\"$parameterKey\"";
			} else {
				echo $parameterKey;
			}
			echo " => \$$parameterName,";
		}
		if (count($parameters) !== 0) {
			echo "\n\t\t";
		}
		echo "]);\n\t}\n\n";
	}
	echo "}\n";
	file_put_contents(dirname(__DIR__) . '/src/arkania/language/CustomTranslationFactory.php', ob_get_clean());
	echo "Done generating CustomTranslationFactory.\n";
}
$lang = parse_ini_file(Path::join('resources/languages', 'fr_FR.lang'), false, INI_SCANNER_RAW);
if ($lang === false) {
	fwrite(STDERR, "Missing language files!\n");
	exit(1);
}
generate_known_translation_keys($lang);
generate_known_translation_factory($lang);
