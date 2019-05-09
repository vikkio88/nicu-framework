<?php


namespace Nicu\Console;


use DateTime;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class BuildCommand extends ConsoleCommand
{
    protected $acceptedArgs = ['-v', '-d'];

    const DIST_FOLDER = './dist/';
    const COMPOSER_INSTALL = 'composer install --no-ansi --no-dev --no-interaction --no-progress --no-scripts --optimize-autoloader';
    const CLEANUP = './vendor/bin/cleanup';
    const DIST_VENDOR = './dist/vendor/';

    protected $projectFolders = [
        'config'
    ];
    protected $mainFiles = [
        'composer.json',
        '.env',
        '.htaccess',
        'index.php'
    ];

    public function __construct(array $args, array $projectFolders = null, array $mainFiles = null)
    {
        parent::__construct($args);

        if ($projectFolders) {
            $this->projectFolders = $projectFolders;
        }

        if ($mainFiles) {
            $this->mainFiles = $mainFiles;
        }
    }

    public function run()
    {
        $vendorUpdated = true;
        $forceCleanup = $this->getArg('-d', false);

        if (!$forceCleanup) {
            $this->logInfo('checking previous build');
            $vendorUpdated = $this->areVendorUpdated();
            $this->logInfo('are vendor updated? ' . ($vendorUpdated ? 'yes' : 'no'));
            $this->logInfo('skipping composer install step');
        }

        if ($vendorUpdated || $forceCleanup) {
            $this->logInfo('Cleaning dist Folder');
            $this->refreshDistDir();
        }

        $this->logInfo('Copying Project files');
        $this->copyProjectStructure();

        if ($vendorUpdated || $forceCleanup) {
            $this->logInfo('Running composer');
            $this->runInstall();
            $this->logInfo('Cleaning vendor/');
            $this->cleanVendor();
        }


        $this->logInfo("******************************************");
        $this->logInfo("Done, don't forget to check the .env file");
        if(!$vendorUpdated){
            $this->logInfo('you dont need to push the vendor/ folder');
        }
        $this->logInfo("******************************************");
    }

    private function refreshDistDir()
    {
        if (is_dir(self::DIST_FOLDER)) {
            $this->rmrdir(self::DIST_FOLDER);
        }
        mkdir(self::DIST_FOLDER);
    }

    private function rmrdir($folderName)
    {
        if (file_exists($folderName)) {
            exec('rm -rf ' . $folderName);
        }
    }

    private function copyProjectStructure()
    {
        foreach ($this->mainFiles as $file) {
            if (file_exists($file)) {
                copy($file, self::DIST_FOLDER . $file);
            }
        }

        foreach ($this->projectFolders as $folder) {
            $this->rmrdir(self::DIST_FOLDER . $folder);
            $this->dircopy($folder, self::DIST_FOLDER . $folder);
        }
    }

    private function dircopy($target, $destination)
    {
        if (!is_dir($destination)) {
            mkdir($destination, 0755);
        }

        foreach (
            $iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($target, RecursiveDirectoryIterator::SKIP_DOTS),
                RecursiveIteratorIterator::SELF_FIRST) as $item
        ) {
            $this->logInfo('copying item: ' . $item);
            $fullPath = $destination . DIRECTORY_SEPARATOR . $iterator->getSubPathName();
            if ($item->isDir() && !file_exists($fullPath)) {
                mkdir($fullPath);
            } elseif (!$item->isDir()) {
                copy($item, $fullPath);
            }
        }
    }

    private function runInstall()
    {
        exec('cd ' . self::DIST_FOLDER . ' && ' . self::COMPOSER_INSTALL);
        exec('cd ..');
    }

    private function logInfo($message)
    {
        if ($this->getArg('-v')) {
            echo (new DateTime())->format('Y-m-d H:i:s') . ' --- ' . $message . PHP_EOL;
        }
    }

    private function cleanVendor()
    {
        $this->logInfo('file count before cleanup');
        $filesBefore = shell_exec('find ./dist -type f | wc -l');
        $this->logInfo($filesBefore);
        $result = shell_exec('cd ' . self::DIST_FOLDER . ' && ' . self::CLEANUP);
        $this->logInfo($result);
        $this->logInfo('file count after cleanup');
        $filesAfter = shell_exec('find ./dist -type f | wc -l');
        $this->logInfo($filesAfter);
    }

    protected function areVendorUpdated(): bool
    {
        if (is_dir(self::DIST_FOLDER) && is_dir(self::DIST_VENDOR) && file_exists(self::DIST_FOLDER . 'composer.json')) {
            return md5(file_get_contents('./composer.json')) !== md5(file_get_contents(self::DIST_FOLDER . 'composer.json'));
        }

        return false;
    }
}
