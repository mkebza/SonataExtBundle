<?php

/*
 * Author: (c) Marek Kebza <marek@kebza.cz>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace MKebza\SonataExt\Command;

use MKebza\SonataExt\Utils\CommandInfoInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class RoleExportCommand extends ContainerAwareCommand implements CommandInfoInterface
{
    protected const FORMAT_COMPACT = 'compact';
    protected const FORMAT_EXPANDED = 'expanded';
    /**
     * @var SymfonyStyle
     */
    private $style;

    private $formats = [self::FORMAT_COMPACT, self::FORMAT_EXPANDED];

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->style = new SymfonyStyle($input, $output);

        $map = $this->collectRoles();

        $rolesFormatted = $this->{'formatRoles'.$input->getOption('format')}($map);

        if ($input->getOption('dry-run')) {
            $this->style->write($rolesFormatted);
        } else {
            $securityFilePath = $input->getOption('security-file');
            if (!file_exists($securityFilePath)) {
                throw new \RuntimeException(sprintf("File '%s' doesn't exists", $securityFilePath));
            }

            $securityContents = file_get_contents($securityFilePath);
            if (empty($securityContents)) {
                throw new \RuntimeException(sprintf("Can't read file '%s'", $securityFilePath));
            }

            if (!preg_match('@##SONATA_ADMIN_ROLES_BEGIN##(.*)##SONATA_ADMIN_ROLES_END##@s', $securityContents)) {
                throw new \RuntimeException(sprintf("Can't found SONATA_ADMIN_ROLES_BEGIN block in %s", $securityFilePath));
            }

            $securityContents = preg_replace(
                '@##SONATA_ADMIN_ROLES_BEGIN##(.*)##SONATA_ADMIN_ROLES_END##@s',
                "##SONATA_ADMIN_ROLES_BEGIN##\n".$rolesFormatted.'        ##SONATA_ADMIN_ROLES_END##',
                $securityContents
            );

            if (!file_put_contents($securityFilePath, $securityContents)) {
                throw new \RuntimeException(sprintf("Can't update roles in '%s', error while writing new file", $securityFilePath));
            }

            $this->style->success(sprintf('Updated roles in %s', $securityFilePath));
        }

        return 0;
    }

    protected function configure()
    {
        $this
            ->setName('sonata-ext:export-roles')
            ->setDescription('Generates all roles for sonata admin classes and saves them in security.yml between tags ##SONATA_ADMIN_ROLES_BEGIN## and ##SONATA_ADMIN_ROLES_END##')
            ->addOption(
                'dry-run',
                'd',
                InputOption::VALUE_NONE,
                "Don't write to the file but output instead")
            ->addOption(
                'format',
                'f',
                InputOption::VALUE_REQUIRED,
                'Format of roles *compact* or *expanded*',
                self::FORMAT_EXPANDED)
            ->addOption(
                'security-file',
                null,
                InputOption::VALUE_REQUIRED,
                'Full path to security.yaml file',
                null)
        ;
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        if (!in_array($input->getOption('format'), $this->formats, true)) {
            throw new \InvalidArgumentException(sprintf('Invalid format parameters, allowed values are %s', implode(', ', $this->formats)));
        }

        if (null === $input->getOption('security-file')) {
            $input->setOption('security-file', realpath($this->getContainer()->getParameter('kernel.root_dir').'/../config/packages/security.yaml'));
        }
    }

    protected function formatRolesExpanded(array $map): string
    {
        $formatted = '';
        foreach ($map as $base => $roles) {
            $formatted .= sprintf("        %s:\n", $base);
            foreach ($roles as $role) {
                $formatted .= sprintf("             - %s\n", $role);
            }
        }

        return $formatted;
    }

    protected function formatRolesCompact(array $map): string
    {
        $formatted = '';
        foreach ($map as $base => $roles) {
            $formatted .= sprintf("        %s: [%s]\n", $base, implode(', ', $roles));
        }

        return $formatted;
    }

    protected function createRoleName(string $adminCode): string
    {
        return str_replace('ROLE_SONATA_ADMIN_', 'ROLE_ADMIN_', ('ROLE_'.str_replace('.', '_', strtoupper($adminCode)).'_%s'));
    }

    protected function collectRoles(): array
    {
        $pool = $this->getContainer()->get('sonata.admin.pool');

        $map = [];
        foreach ($pool->getAdminServiceIds() as $id) {
            /** @var \Sonata\AdminBundle\Admin\AdminInterface $instance */
            $instance = $this->getContainer()->get($id);

            $actions = array_keys($instance->getRoutes()->getElements());

            $roleAll = sprintf($this->createRoleName($instance->getCode()), 'ALL');
            foreach ($actions as $action) {
                $actionParts = explode('.', $action);
                $action = array_pop($actionParts);

                if ($instance->hasRoute(strtolower($action))) {
                    $newRole = sprintf($this->createRoleName($instance->getCode()), strtoupper($action));
                    $map[$roleAll][] = $newRole;
                }
            }
        }

        return $map;
    }
}
