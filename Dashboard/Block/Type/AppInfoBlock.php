<?php
/**
 * User: Marek Kebza <marek@kebza.cz>
 * Date: 20/06/2018
 * Time: 19:34
 */

namespace MKebza\SonataExt\Dashboard\Block\Type;


use Doctrine\DBAL\Connection;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Process\Process;

class AppInfoBlock extends AbstractBoxBlock implements ContainerAwareInterface
{
    use ContainerAwareTrait;
    
    public function execute(array $options = []): ?string
    {
        return $this->render('@SonataExt/block/app_info.html.twig', [
            'php' => PHP_VERSION,
            'project_dir' => $this->container->getParameter('kernel.project_dir'),
            'database_connections' => $this->getConnections(),
            'git' => $this->getGitInfo(),

        ], $options);
    }

    protected function getConnections(): array
    {
        $connections = [];
        /** @var Connection $connection */
        foreach ($this->container->get('doctrine')->getConnections() as $name => $connection) {
            $connections[] = [
                'name' => $name,
                'host' => $connection->getHost(),
                'database' => $connection->getDatabase(),
            ];
        }
        return $connections;
    }

    protected function getGitInfo(): ?string
    {
        $branchProcess = new Process(
            'git rev-parse --abbrev-ref HEAD',
            $this->container->getParameter('kernel.project_dir'));
        $branchProcess->run();
        $branch = trim($branchProcess->getOutput());

        $versionProcess = new Process(
            'git log -1 --format="%h"',
            $this->container->getParameter('kernel.project_dir'));
        $versionProcess->run();
        $version = trim($versionProcess->getOutput());

        return sprintf('%s/%s', $branch ?: '?', $version ?: '?');
    }


    protected function configure(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'block_type' => 'danger',
        ]);
    }

    public function getRoles(): array
    {
        return ['ROLE_DEVELOPER'];
    }


}