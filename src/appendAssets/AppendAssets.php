<?php
/**
 * AppendAssets.php
 *
 * @author Michal Pospiech <michal@pospiech.cz>
 */

namespace appendAssets;


use Nette\Caching\Cache;
use Nette\Object;
use Nette\Utils\Finder;

class AppendAssets extends Object
{

	/** @var Cache */
	private $cache;

	/** @var bool */
	private $productionMode = false;

	/** @var array */
	private $files;

	/**
	 * @param Cache $cache
	 */
	public function setCache(Cache $cache)
	{
		$this->cache = $cache;
	}

	/**
	 * @param bool $isProductionMode
	 */
	public function setProductionMode($isProductionMode = false)
	{
		$this->productionMode = $isProductionMode;
	}

	/**
	 * @return bool
	 */
	public function isProductionMode()
	{
		return $this->productionMode;
	}

	/**
	 * @param array $filesMask
	 * @param string $searchDir
	 * @throws \Exception
	 * @throws \Throwable
	 */
	public function searchFiles(array $filesMask, $searchDir)
	{
		$files = $this->cache->load('files');
		if ($files === null) {
			$files = [];
			foreach (Finder::findFiles($filesMask)->from($searchDir) as $key => $file) {
				$files[] = $key;
			}

			$this->cache->save('files', $files, [
				Cache::FILES => $files
			]);
		}

		$this->files = $files;
	}

	/**
	 * @return array
	 */
	public function getFiles()
	{
		return $this->files;
	}

}