<?php


namespace App\Factory;


use Doctrine\Common\Cache\MemcachedCache;
use Doctrine\Common\Cache\PhpFileCache;
use Doctrine\ORM\Cache\RegionsConfiguration;
use Doctrine\ORM\EntityManager;

class EntityManagerFactory
{
    /**
     * @param \Psr\Container\ContainerInterface $c
     * @return EntityManager
     * @throws \Doctrine\DBAL\DBALException
     * @throws \Doctrine\ORM\ORMException
     */
    public static function create(\Psr\Container\ContainerInterface $c): EntityManager
    {
        // Metadata setup and cache
        $config = \Doctrine\ORM\Tools\Setup::createAnnotationMetadataConfiguration(
            $c->get('settings')['doctrine']['metadata_dirs'],
            $c->get('settings')['doctrine']['dev_mode']
        );
        $config->setMetadataDriverImpl(
            new \Doctrine\ORM\Mapping\Driver\AnnotationDriver(
                new \Doctrine\Common\Annotations\AnnotationReader(),
                $c->get('settings')['doctrine']['metadata_dirs']
            )
        );
        $config->setMetadataCacheImpl(
            new \Doctrine\Common\Cache\FilesystemCache(
                $c->get('settings')['doctrine']['cache_dir']
            )
        );

        $config->setProxyDir($c->get('settings')['doctrine']['proxy_dir']);

        // Naming strategy
        $namingStrategy = new \Doctrine\ORM\Mapping\UnderscoreNamingStrategy(CASE_LOWER, true);
        $config->setNamingStrategy($namingStrategy);

        // Query cache
        $cacheDriver = new \Doctrine\Common\Cache\ArrayCache();
        $config->setQueryCacheImpl($cacheDriver);

        // Additional enum types
        \Doctrine\DBAL\Types\Type::addType(\App\DBAL\UserRole::NAME, \App\DBAL\UserRole::class);

        // Second level cache
        $regionsConfig = new RegionsConfiguration();
//        $fileCache = new PhpFileCache(ROOT_PATH . '/var/doctrine/file_cache');
//        $memcached = $c->get('memcached');
//        $memcacheDriver = new MemcachedCache();
//        $memcacheDriver->setMemcached($memcached);

        $factory = new \Doctrine\ORM\Cache\DefaultCacheFactory($regionsConfig, $cacheDriver);
        $config->setSecondLevelCacheEnabled(true);
        $config->getSecondLevelCacheConfiguration()->setCacheFactory($factory);

        $em = \Doctrine\ORM\EntityManager::create(
            $c->get('settings')['doctrine']['connection'],
            $config
        );

        $em->getConnection()->getDatabasePlatform()->registerDoctrineTypeMapping('enum', 'string');
        return $em;
    }
}