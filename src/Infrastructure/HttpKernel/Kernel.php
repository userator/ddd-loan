<?php

namespace App\Infrastructure\HttpKernel;

use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    use MicroKernelTrait;


    private function getRepositoryDir(): string
    {
        return $this->getProjectDir() . '/var/repository';
    }

    private function getSmsDir(): string
    {
        return $this->getProjectDir() . '/var/sms';
    }

    private function getEmailDir(): string
    {
        return $this->getProjectDir() . '/var/email';
    }

    protected function buildContainer(): ContainerBuilder
    {
        foreach (
            [
                'repository' => $this->getRepositoryDir(),
                'sms' => $this->getSmsDir(),
                'email' => $this->getEmailDir()
            ] as $name => $dir
        ) {
            if (!is_dir($dir)) {
                if (false === @mkdir($dir, 0777, true) && !is_dir($dir)) {
                    throw new RuntimeException(sprintf('Unable to create the "%s" directory (%s).', $name, $dir));
                }
            } elseif (!is_writable($dir)) {
                throw new RuntimeException(sprintf('Unable to write in the "%s" directory (%s).', $name, $dir));
            }
        }

        return parent::buildContainer();
    }
}
