<?php
/**
 * This file is part of the Global Trading Technologies Ltd ad-poller package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * (c) fduch <alex.medwedew@gmail.com>
 *
 * Date: 11.08.17
 */

use Doctrine\ORM\Tools\Setup;

require_once "vendor/autoload.php";

// Create a simple "default" Doctrine ORM configuration for annotations mapping
$isDevMode = true;
$config    = Setup::createAnnotationMetadataConfiguration([__DIR__ . "/src/Entity"], $isDevMode, null, null, false);

// Configure database parameters
$conn = [
    'driver'   => 'mysqli',
    'dbname'   => 'adpoller',
    'user'     => 'YOURUSER',
    'password' => 'YOURPASSWORD',
    'host'     => 'localhost',
];

// obtaining the entity manager
$entityManager = \Doctrine\ORM\EntityManager::create($conn, $config);

$helperSet = new \Symfony\Component\Console\Helper\HelperSet([
    'em' => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($entityManager)
]);

return $helperSet;