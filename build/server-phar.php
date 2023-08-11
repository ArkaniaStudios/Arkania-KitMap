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

use arkania\webhook\Embed;
use arkania\webhook\Message;

require_once dirname(__DIR__) . '/vendor/autoload.php';

function preg_quote_array(array $strings, string $delim) : array {
	return array_map(function (mixed $str) use ($delim) {
		return preg_quote((string) $str, $delim);
	}, $strings);
}

function readChangelogs(string $version, string $subVersion = '0', string $path = 'changelogs\\') : array {
	$file = fopen($path . $version . '.md', 'r');
	$start = false;
	$end = false;
	$content = '';
	$title = '';
	while ($line = fgets($file)) {
		$line = trim($line);
		if ($start === true) {
			if ($end === false) {
				if ($line === '-----------------------------') {
					$end = true;
					break;
				} else {
					$content .= $line . "\n";
				}
			}
		} elseif ($line === '## ' . $version . '.' . $subVersion . ' :') {
			$title = $version . '.' . $subVersion . ' :';
			$start = true;
		}
	}
	fclose($file);

	return [$content, $title];
}

/**
 * @param string[] $includedPaths
 */
function buildPhar(string $pharName, string $basePath, array $includedPaths, string $stubs) : Generator {
	shell_exec('php ./build/generate-permissions.php');
	$basePath = rtrim(str_replace("/", DIRECTORY_SEPARATOR, $basePath), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
	$includedPaths = array_map(function (string $path) : string {
		return rtrim(str_replace("/", DIRECTORY_SEPARATOR, $path), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
	}, $includedPaths);
	yield "Creation du fichier $pharName";
	if (file_exists($pharName)) {
		yield "Le fichier phar existe déjà, replacement...";
		try {
			Phar::unlinkArchive($pharName);
		} catch (PharException $e) {
			unlink($pharName);
		}
	}
	yield "Ajout des fichiers...";
	$start = microtime(true);
	$phar = new Phar($pharName);
	$phar->setStub($stubs);
	$phar->setSignatureAlgorithm(Phar::SHA1);
	$phar->startBuffering();
	$excludedSubstrings = preg_quote_array([
		realpath($pharName),
	], '/');
	$folderPatterns = preg_quote_array([
		DIRECTORY_SEPARATOR . 'tests' . DIRECTORY_SEPARATOR,
		DIRECTORY_SEPARATOR . '.'
	], '/');
	$basePattern = preg_quote(rtrim($basePath, DIRECTORY_SEPARATOR), '/');
	foreach ($folderPatterns as $folderPattern) {
		$excludedSubstrings[] = $basePattern . '.*' . $folderPattern;
	}
	$regex = sprintf(
		'/^(?!.*(%s))^%s(%s).*/i',
		implode('|', $excludedSubstrings),
		preg_quote($basePath, '/'),
		implode('|', preg_quote_array($includedPaths, '/'))
	);
	$directory = new RecursiveDirectoryIterator($basePath, FilesystemIterator::SKIP_DOTS | FilesystemIterator::FOLLOW_SYMLINKS | FilesystemIterator::CURRENT_AS_PATHNAME); //can't use fileinfo because of symlinks
	$iterator = new RecursiveIteratorIterator($directory);
	$regexIterator = new RegexIterator($iterator, $regex);
	$phar->addFromString('plugin.yml', file_get_contents('plugin.yml'));
	$count = count($phar->buildFromIterator($regexIterator, $basePath));
	yield "Ajout de $count fichiers";
	$compression = Phar::GZ;
	yield "Compression des fichiers...";
	$phar->compressFiles($compression);
	yield "Compression fini !";
	$phar->stopBuffering();
	yield "Compression fait en " . round(microtime(true) - $start, 3) . "s";
}
function main() : void {
	foreach (buildPhar(
		'Arkania-LobbyCore.phar',
		dirname(__DIR__) . DIRECTORY_SEPARATOR,
		[
			'src',
			'vendor',
			'resources'
		],
		<<<'STUB'
<?php

$tmpDir = sys_get_temp_dir();
if(!is_readable($tmpDir) or !is_writable($tmpDir)){
	echo "ERROR: tmpdir $tmpDir is not accessible." . PHP_EOL;
	echo "Check that the directory exists, and that the current user has read/write permissions for it." . PHP_EOL;
	echo "Alternatively, set 'sys_temp_dir' to a different directory in your php.ini file." . PHP_EOL;
	exit(1);
}

require("phar://" . __FILE__ . "/src/arkania/Main.php");
__HALT_COMPILER();
STUB
	) as $line) {
		echo $line . PHP_EOL;
	}
}

main();
$message = new Message();
$embed = new Embed();
$version = file(dirname(__DIR__) . '/plugin.yml');
$version = str_replace('version: ', '', $version[1]);
$version = rtrim($version);
$version = explode('.', $version);
$changelogs = readChangelogs($version[0] . '.' . $version[1], $version[2]);
$embed->setTitle('**New Release**')
	->setContent("- Une nouvelle version du plugin a été compilé. Vous pouvez la télécharger [ici](https://github.com/ArkaniaStudios-Network/Arkania-LobbyCore/releases/latest/download/Arkania-LobbyCore.phar)\n\n**Changelogs $changelogs[1]**\n$changelogs[0]\n\n*⚠ La release peut ne pas être disponible. Merci de rester patient.*")
	->setFooter('Arkania-LobbyCore')
	->setColor(0xAB16E7);
$message->addEmbed($embed);
$ch = curl_init('https://discord.com/api/webhooks/1130088064706957435/9NPyl-6s5QzFSTob1spbiKjdLdIRsLPqlft4mbKYGfW2T2astrC25f_U-Coci1TzkRKf');
curl_setopt_array($ch, [
	CURLOPT_POST => true,
	CURLOPT_POSTFIELDS => json_encode($message->jsonSerialize()),
	CURLOPT_HTTPHEADER => [
		"Content-Type: application/json"
	],
	CURLOPT_RETURNTRANSFER => true,
	CURLOPT_SSL_VERIFYPEER => false,
	CURLOPT_SSL_VERIFYHOST => false
]);
curl_exec($ch);
curl_close($ch);
