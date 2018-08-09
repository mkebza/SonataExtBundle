# Instalation

- Composer

          {
            "type": "vcs",
            "url": "https://github.com/mkebza/CommandSchedulerBundle.git"
          }
 "jmose/command-scheduler-bundle": "dev-sonata-ext"no

- Odstranit routing z scheduler bundle
- zeregistrovat form theme
- zaregistrovat bundle pro orm
- migrace

```yaml
twig:
    form_themes:
        - '@MKebzaSonataExt/fields.html.twig'
        
doctrine:
    orm:
        mappings:
            MKebzaSonataExtBundle: ~
            
        resolve_target_entities:
            MKebza\SonataExt\ActionLog\ActionLogUserInterface: App\Entity\User
            

```

# Extra roles
        ROLE_ADMIN_GRANT_PERMISSION: ~
        ROLE_ALLOW_IMPERSONATE: ~
        ROLE_DEVELOPER:
            


# Entity 

- Extend User entity
- Extend UserGroup Enity


Custom dashboard
add routing

```yaml
sonata_admin_dashboard:
    path: /admin/dashboard
    controller: MKebzaSonataExtBundle:Dashboard:dashboard


```

Whats available

- TemplateType
- Better styling 
- Enabled Datepickers (CSS / JS) by default
- ActionLog
- Integraované FOS User Bundle
- Integrated Scheduler Bundle

Modifications:

- ROLE_SONATA_ADMIN_* replaced to ROLE_ADMIN_*

Commands:

- sonata-ext:export-roles
