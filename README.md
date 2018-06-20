# Instalation

- Composer
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

# Entity 

- Extend User entity
- Extend UserGroup Enity


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

- 
