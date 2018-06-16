# Instalation

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

Extend User entity

ActionLog

Whats available

- TemplateType
- Better styling 
- Enabled Datepickers (CSS / JS)