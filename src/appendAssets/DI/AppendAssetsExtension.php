<?php
/**
 * AppendAssetsExtension.php
 *
 * @author Michal Pospiech <michal@pospiech.cz>
 */

namespace appendAssets\DI;


use appendAssets\AppendAssets;
use Nette\Caching\Cache;
use Nette\Caching\Storages\FileStorage;
use Nette\DI\CompilerExtension;
use Nette\Utils\FileSystem;

class AppendAssetsExtension extends CompilerExtension
{

	/**
	 * @return array
	 */
	private function getDefaultConfig()
	{
		$container = $this->getContainerBuilder();

		return [
			'cacheDir' => $container->parameters['tempDir'] . '/cache',
			'filesMask' => ['assets/*.js', 'assets/*.css', 'assets/*.less'],
			'searchDir' => __DIR__ . '/../../../',
			'cache' => true
		];
	}

	public function loadConfiguration()
	{
		$config = $this->getConfig($this->getDefaultConfig());
		$builder = $this->getContainerBuilder();

		if (!is_dir($config['cacheDir'])) {
			FileSystem::createDir($config['cacheDir']);
		}
		$cacheStorage = new FileStorage($config['cacheDir']);

		$builder->addDefinition($this->prefix('appendAssets'))
			->setClass(AppendAssets::class)
			->addSetup('setProductionMode', [$builder->parameters['productionMode']])
			->addSetup('setCache', [new Cache($cacheStorage, 'appendAssets'), $config['cache']])
			->addSetup('searchFiles', [$config['filesMask'], $config['searchDir']]);
	}

}