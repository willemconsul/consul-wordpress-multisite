# Consul Core Plugin

Gedeelde WordPress plugin voor Consul Infra Multisite. Deze plugin bevat centraal beheerde functionaliteit voor alle sites.

## Features

### Phase 1: Branding (Actief)
- **Consul Infra huisstijl**: Primaire/secundaire kleuren, typografie
- **Per-site aanpassingen**: Customizer instellingen voor logo en kleuren
- **CSS Custom Properties**: Dynamisch gegenereerde CSS variabelen
- **Brand Helper Functions**: `consul_get_brand_color()`, etc.

### Phase 2: ACF Setup (In Development)
- Advanced Custom Fields integratie
- JSON folder configuratie
- Field group management

### Phase 3: Custom Post Types (In Development)
- 12Tender: Tenders
- Civiele Toekomstbouwers: Events, Projects
- MKI: Kennisartikelen

### Phase 4: Herbruikbare Blokken (In Development)
- Calendly CTA-blok
- Feature blocks
- Call-to-action varianten

## Installatie

Plugin is automatisch netwerk-geactiveerd in DDEV omgeving.

```bash
# Plugin activeren (mocht nodig zijn)
ddev wp plugin activate consul-core --network --path=public
```

## Configuratie

### Kleuren aanpassen
1. Ga naar WordPress Admin
2. Customizer > Consul Branding
3. Pas primaire/secundaire kleuren aan
4. Voeg logo toe

### CSS Variabelen
De volgende CSS variabelen zijn beschikbaar:
- `--consul-primary`: Primaire kleur
- `--consul-secondary`: Secundaire kleur
- `--consul-dark`: Donkergrijs
- `--consul-light`: Lichtgrijs
- `--consul-accent`: Accent kleur

### Helper Functions
```php
// Get brand color
echo consul_get_brand_color('primary'); // #004A90
echo consul_get_brand_color('secondary'); // #FF6B35
```

## Bestandsstructuur

```
consul-core/
├── consul-core.php           # Main plugin file
├── includes/
│   ├── branding.php         # Branding setup
│   ├── post-types.php       # Custom post types
│   ├── taxonomies.php       # Custom taxonomies
│   └── acf-setup.php        # ACF configuratie
├── assets/
│   ├── css/
│   │   └── branding.css     # Brand styling
│   ├── img/                 # Logo's en assets
│   └── js/                  # JavaScript utilities
├── acf-json/                # ACF field group exports
└── README.md
```

## Ontwikkeling

### Voeg een Custom Post Type toe
Edit `includes/post-types.php` en voeg registratie toe:

```php
register_post_type('my_cpt', array(
    'label' => 'My Custom Type',
    'public' => true,
    'supports' => array('title', 'editor'),
));
```

### Voeg ACF Field Group toe
Export vanuit WordPress Admin naar `acf-json/` folder.

### Commit Changes
```bash
git add public/wp-content/plugins/consul-core/
git commit -m "feat: voeg [feature] toe aan consul-core"
git push
```

## Support

Vragen? Check de documentatie in `docs/beheer/` of neem contact op met het team.

---

**Version:** 1.0.0  
**Requires WordPress:** 6.0+  
**Requires PHP:** 8.2+  
**Network:** Ja (Multisite)
