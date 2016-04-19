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
	private $allowCache;

	/** @var bool */
	private $productionMode = false;

	/** @var array */
	private $files;

	/**
	 * @param Cache $cache
	 * @param bool $allowCache
	 */
	public function setCache(Cache $cache, $allowCache = true)
	{
		$this->cache = $cache;
		$this->allowCache = $allowCache;
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
		$files = $this->allowCache ? $this->cache->load('files') : null;
		if ($files === null) {
			$files = [];
			foreach (Finder::findFiles($filesMask)->from($searchDir) as $key => $file) {
				$files[] = $key;
			}

			if ($this->allowCache) {
				$this->cache->save('files', $files, [
					Cache::FILES => $files
				]);
			}
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