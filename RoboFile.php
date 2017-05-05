<?php

use Robo\Exception\TaskException;

if (!file_exists('robo')) {
    echo "Robo requirement missing. Downloading...\n\n";
    system('curl -o robo -s http://robo.li/robo.phar && chmod +x robo');
    echo "Run ./robo for further use.\n";
    exit;
} elseif (!class_exists('\Robo\Tasks')) {
    echo "Run ./robo for further use.\n";
    exit;
}

class RoboFile extends \Robo\Tasks
{
    
    /**
     * Setup all (containers, parameters, dependencies, configuration)
     */
    public function setupAll($opts = [])
    {
        $this->appBuild();
        $this->setupParameters();
        $this->dependenciesInstall();
        $this->configureApp();
    }

    /**
     * Build containers (Docker)
     */
    public function appBuild()
    {
        $this->getDockerContainer()
            ->exec('docker-compose up -d --remove-orphans --build')
            ->run()
        ;
    }

    /**
     * Initialize parameters.yml (Symfony)
     */
    public function setupParameters()
    {
        $this->taskFilesystemStack()
            ->copy('www/core/app/config/parameters.yml.dist', 'www/core/app/config/parameters.yml')
            ->run();
    }

    /**
     * Install Composer dependencies
     */
    public function dependenciesInstall()
    {
        $this->getDockerContainer('cg-php')
            ->composerInstall('core')
            ->run()
        ;
    }

    /**
     * Replace ip in app_dev and app_test files
     */
    public function configureApp()
    {
        $ip = $this->getDockerContainer()
            ->exec('docker inspect -f "{{range .NetworkSettings.Networks}}{{.IPAddress}}{{end}}" nginx')
            ->run()
            ->getMessage();

        $ip = substr(trim($ip), 0, 7);

        $this->taskReplaceInFile('www/core/web/app_dev.php')
            ->from(["'192.168'"])
            ->to(["'" . $ip . "'"])
            ->run();
    }

    /**
     * @param string $name
     *
     * @return DockerStack
     */
    private function getDockerContainer($name = '')
    {
        return new DockerStack($name);
    }
}


class DockerStack extends \Robo\Task\CommandStack
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @param $name
     */
    public function __construct($name = '')
    {
        $this->name = $name;
    }

    public function composerInstall($directory)
    {
        $this->command("cd $directory; composer install --no-interaction");

        return $this;
    }
    
    public function command($command)
    {
        $this->exec('docker exec -t ' . $this->name . ' bash -c "' . $command . '"');

        return $this;
    }
}




