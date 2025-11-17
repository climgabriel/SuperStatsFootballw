<?php
/**
 * Cache Manager - Simple file-based caching for API responses
 *
 * Features:
 * - File-based cache storage
 * - Configurable TTL (Time To Live)
 * - Automatic cache invalidation
 * - Cache key generation
 */

class CacheManager {

    private $cacheDir;
    private $defaultTTL;
    private $enabled;

    /**
     * Constructor
     *
     * @param string $cacheDir Cache directory path
     * @param int $defaultTTL Default cache TTL in seconds
     * @param bool $enabled Enable/disable caching
     */
    public function __construct($cacheDir = null, $defaultTTL = 300, $enabled = true) {
        $this->cacheDir = $cacheDir ?? __DIR__ . '/../cache';
        $this->defaultTTL = $defaultTTL;
        $this->enabled = $enabled;

        // Create cache directory if it doesn't exist
        if ($this->enabled && !file_exists($this->cacheDir)) {
            @mkdir($this->cacheDir, 0755, true);
        }
    }

    /**
     * Generate cache key from parameters
     *
     * @param string $prefix Cache key prefix
     * @param array $params Parameters to include in key
     * @return string Cache key
     */
    private function generateKey($prefix, $params = []) {
        ksort($params); // Sort for consistent keys
        $paramString = http_build_query($params);
        return $prefix . '_' . md5($paramString);
    }

    /**
     * Get cache file path
     *
     * @param string $key Cache key
     * @return string File path
     */
    private function getCacheFilePath($key) {
        return $this->cacheDir . '/' . $key . '.cache';
    }

    /**
     * Get cached data
     *
     * @param string $key Cache key
     * @return mixed|null Cached data or null if not found/expired
     */
    public function get($key) {
        if (!$this->enabled) {
            return null;
        }

        $filePath = $this->getCacheFilePath($key);

        // Check if cache file exists
        if (!file_exists($filePath)) {
            return null;
        }

        // Read cache file
        $cacheData = @file_get_contents($filePath);
        if ($cacheData === false) {
            return null;
        }

        $cache = @unserialize($cacheData);
        if ($cache === false || !isset($cache['expires']) || !isset($cache['data'])) {
            return null;
        }

        // Check if cache has expired
        if (time() > $cache['expires']) {
            @unlink($filePath);
            return null;
        }

        return $cache['data'];
    }

    /**
     * Store data in cache
     *
     * @param string $key Cache key
     * @param mixed $data Data to cache
     * @param int|null $ttl Time to live in seconds (null = use default)
     * @return bool Success status
     */
    public function set($key, $data, $ttl = null) {
        if (!$this->enabled) {
            return false;
        }

        $ttl = $ttl ?? $this->defaultTTL;
        $filePath = $this->getCacheFilePath($key);

        $cache = [
            'expires' => time() + $ttl,
            'data' => $data
        ];

        $result = @file_put_contents($filePath, serialize($cache), LOCK_EX);
        return $result !== false;
    }

    /**
     * Delete cached data
     *
     * @param string $key Cache key
     * @return bool Success status
     */
    public function delete($key) {
        if (!$this->enabled) {
            return false;
        }

        $filePath = $this->getCacheFilePath($key);
        if (file_exists($filePath)) {
            return @unlink($filePath);
        }

        return true;
    }

    /**
     * Clear all cache
     *
     * @return bool Success status
     */
    public function clear() {
        if (!$this->enabled) {
            return false;
        }

        $files = glob($this->cacheDir . '/*.cache');
        if ($files === false) {
            return false;
        }

        foreach ($files as $file) {
            @unlink($file);
        }

        return true;
    }

    /**
     * Remove expired cache files
     *
     * @return int Number of files removed
     */
    public function cleanExpired() {
        if (!$this->enabled) {
            return 0;
        }

        $removed = 0;
        $files = glob($this->cacheDir . '/*.cache');

        if ($files === false) {
            return 0;
        }

        foreach ($files as $file) {
            $cacheData = @file_get_contents($file);
            if ($cacheData === false) {
                continue;
            }

            $cache = @unserialize($cacheData);
            if ($cache !== false && isset($cache['expires'])) {
                if (time() > $cache['expires']) {
                    @unlink($file);
                    $removed++;
                }
            }
        }

        return $removed;
    }

    /**
     * Get or set cache with callback
     *
     * @param string $prefix Cache key prefix
     * @param array $params Cache key parameters
     * @param callable $callback Function to generate data if cache miss
     * @param int|null $ttl Cache TTL
     * @return mixed Cached or freshly generated data
     */
    public function remember($prefix, $params, $callback, $ttl = null) {
        $key = $this->generateKey($prefix, $params);
        $cached = $this->get($key);

        if ($cached !== null) {
            return $cached;
        }

        $data = $callback();
        $this->set($key, $data, $ttl);

        return $data;
    }

    /**
     * Get cache statistics
     *
     * @return array Cache stats
     */
    public function getStats() {
        if (!$this->enabled) {
            return ['enabled' => false];
        }

        $files = glob($this->cacheDir . '/*.cache');
        $totalSize = 0;
        $expiredCount = 0;

        if ($files !== false) {
            foreach ($files as $file) {
                $totalSize += filesize($file);

                $cacheData = @file_get_contents($file);
                if ($cacheData !== false) {
                    $cache = @unserialize($cacheData);
                    if ($cache !== false && isset($cache['expires'])) {
                        if (time() > $cache['expires']) {
                            $expiredCount++;
                        }
                    }
                }
            }
        }

        return [
            'enabled' => true,
            'total_files' => count($files),
            'total_size_bytes' => $totalSize,
            'total_size_mb' => round($totalSize / 1024 / 1024, 2),
            'expired_files' => $expiredCount,
            'cache_dir' => $this->cacheDir
        ];
    }
}
