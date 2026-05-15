## Korte samenvatting aanbevolen aanpak

Gebruik deze vaste keten:

```text
Lokaal DDEV → Git → Dev/Staging → Productie
```

Voor jouw project wordt dit concreet:

```text
Projectslug: consul-wp-ms
WordPress: Multisite
Lokale omgeving: DDEV
Lokale structuur: subdomeinen
Sites: 12t, ctb, mki
Code-editor: VS Code
Versiebeheer: Git
Dev en productie: strikt gescheiden
```

Aanbevolen lokale URL’s:

```text
https://consul-wp-ms.ddev.site
https://12t.consul-wp-ms.ddev.site
https://ctb.consul-wp-ms.ddev.site
https://mki.consul-wp-ms.ddev.site
```

Aanbevolen live mapping:

| Lokale site                  | Productie                                               |
| ---------------------------- | ------------------------------------------------------- |
| `12t.consul-wp-ms.ddev.site` | `12tender.nl`                                           |
| `ctb.consul-wp-ms.ddev.site` | `civieletoekomstbouwers.nl`                             |
| `mki.consul-wp-ms.ddev.site` | `mkikennisinstituut.nl` of `www2.mkikennisinstituut.nl` |

DDEV is hiervoor passend, omdat DDEV lokale PHP- en WordPress-omgevingen via Docker beheert en projecten versieerbaar, deelbaar en uitbreidbaar maakt zonder dat je zelf complexe Docker-configuratie hoeft te onderhouden. ([docs.ddev.com][1])

---

# 1. Uitgangspunten

## Technische keuzes

Gebruik:

```text
WordPress Multisite
DDEV lokaal
Subdomeinen
VS Code
Git
Dev/Staging gescheiden van productie
WP-CLI
Automatische scripts waar veilig
Handmatige controle bij database en domeinen
```

Niet gebruiken als standaard:

```text
FTP voor codewijzigingen
Handmatige database-aanpassingen
Lokale database rechtstreeks naar productie pushen
Productie als testomgeving
Subdirectories
```

## Belangrijk principe

Code mag je automatiseren.

Database, domeinen en productiecontent moet je gecontroleerd behandelen.

Vooral bij Multisite is dit belangrijk, omdat URL’s en instellingen verspreid zitten over meerdere tabellen. WP-CLI `search-replace` verwerkt serialized data en heeft bij Multisite `--network` nodig als je netwerkbreed zoekt. ([WordPress Developer Resources][2])

---

# 2. Rollen

## Developer of technisch beheerder

Voor jou of een technische collega.

Verantwoordelijk voor:

* DDEV;
* VS Code;
* Git;
* thema’s;
* eigen plugin `consul-core`;
* deployments;
* updates;
* back-ups;
* migraties;
* testautomatisering;
* documentatiegeneratie.

## Functioneel beheerder

Kan dezelfde persoon zijn.

Verantwoordelijk voor:

* gebruikers;
* rechten;
* redactieworkflow;
* acceptatie op dev/staging;
* controle van content;
* controle van formulieren;
* controle van Calendly-links.

## Redacteur

Gebruikt alleen WordPress.

Verantwoordelijk voor:

* pagina’s;
* berichten;
* media;
* concepten;
* publicatie volgens afspraak;
* geen technische instellingen.

---

# 3. Lokale setup met DDEV

## Stap 3.1, projectmap maken

```bash
mkdir consul-wp-ms
cd consul-wp-ms
```

## Stap 3.2, DDEV configureren

```bash
ddev config --project-type=wordpress --docroot=public --project-name=consul-wp-ms
```

## Stap 3.3, DDEV-configuratie aanpassen

Open:

```text
.ddev/config.yaml
```

Gebruik minimaal:

```yaml
name: consul-wp-ms
type: wordpress
docroot: public
php_version: "8.2"
webserver_type: nginx-fpm
database:
  type: mariadb
  version: "10.11"
additional_hostnames:
  - 12t.consul-wp-ms
  - ctb.consul-wp-ms
  - mki.consul-wp-ms
```

Daarna:

```bash
ddev restart
```

Controleer:

```bash
ddev describe
```

Verwachte lokale URL’s:

```text
https://consul-wp-ms.ddev.site
https://12t.consul-wp-ms.ddev.site
https://ctb.consul-wp-ms.ddev.site
https://mki.consul-wp-ms.ddev.site
```

---

# 4. WordPress lokaal installeren

## Stap 4.1, DDEV starten

```bash
ddev start
```

## Stap 4.2, WordPress downloaden

```bash
ddev wp core download --path=public --locale=nl_NL
```

## Stap 4.3, wp-config.php maken

```bash
ddev wp config create \
  --path=public \
  --dbname=db \
  --dbuser=db \
  --dbpass=db \
  --dbhost=db
```

## Stap 4.4, WordPress installeren

```bash
ddev wp core install \
  --path=public \
  --url=https://consul-wp-ms.ddev.site \
  --title="Consul WP Multisite" \
  --admin_user=admin \
  --admin_password=admin \
  --admin_email=willem@consulinfra.nl
```

Lokaal mag `admin/admin` tijdelijk, maar nooit op dev, staging of productie.

---

# 5. Multisite met subdomeinen activeren

## Stap 5.1, Multisite toestaan

Voeg in `public/wp-config.php` toe, boven:

```php
/* That's all, stop editing! */
```

Deze regel:

```php
define('WP_ALLOW_MULTISITE', true);
```

## Stap 5.2, netwerk installeren

Ga in WordPress naar:

```text
Tools > Network Setup
```

Kies:

```text
Sub-domains
```

Niet kiezen:

```text
Sub-directories
```

## Stap 5.3, wp-config.php aanvullen

WordPress geeft daarna extra regels. Die zullen ongeveer lijken op:

```php
define('MULTISITE', true);
define('SUBDOMAIN_INSTALL', true);
define('DOMAIN_CURRENT_SITE', 'consul-wp-ms.ddev.site');
define('PATH_CURRENT_SITE', '/');
define('SITE_ID_CURRENT_SITE', 1);
define('BLOG_ID_CURRENT_SITE', 1);
```

Gebruik de regels die WordPress zelf toont.

Laat bestaande salts staan.

## Stap 5.4, opnieuw inloggen

Log uit en opnieuw in.

Ga daarna naar:

```text
My Sites > Network Admin
```

---

# 6. Subsites aanmaken

Maak de drie sites via WP-CLI:

```bash
ddev wp site create \
  --path=public \
  --slug=12t \
  --title="12Tender" \
  --email=willem@consulinfra.nl
```

```bash
ddev wp site create \
  --path=public \
  --slug=ctb \
  --title="Civiele Toekomstbouwers" \
  --email=willem@consulinfra.nl
```

```bash
ddev wp site create \
  --path=public \
  --slug=mki \
  --title="MKI Kennis Instituut" \
  --email=willem@consulinfra.nl
```

Controleer:

```bash
ddev wp site list --path=public
```

Verwacht resultaat:

```text
https://consul-wp-ms.ddev.site/
https://12t.consul-wp-ms.ddev.site/
https://ctb.consul-wp-ms.ddev.site/
https://mki.consul-wp-ms.ddev.site/
```

Omdat je subdomeinen gebruikt, worden het dus geen URL’s zoals:

```text
consul-wp-ms.ddev.site/12t
```

---

# 7. Aanbevolen bestandsstructuur

Gebruik deze structuur:

```text
consul-wp-ms/
├── .ddev/
│   └── config.yaml
├── docs/
│   ├── redacteuren/
│   ├── beheer/
│   └── releases/
├── scripts/
│   ├── setup-local.sh
│   ├── test-all.sh
│   ├── docs-generate.sh
│   ├── git-safe-commit.sh
│   ├── deploy-dev.sh
│   └── deploy-production.sh
├── public/
│   ├── wp-admin/
│   ├── wp-content/
│   │   ├── themes/
│   │   │   ├── consul-base/
│   │   │   ├── consul-12t/
│   │   │   ├── consul-ctb/
│   │   │   └── consul-mki/
│   │   ├── plugins/
│   │   │   └── consul-core/
│   │   └── uploads/
│   └── wp-config.php
├── tests/
│   ├── smoke/
│   └── e2e/
├── .gitignore
├── README.md
└── CHANGELOG.md
```

## Eigen plugin

Maak één gedeelde plugin:

```text
public/wp-content/plugins/consul-core/
```

Daarin zet je:

* custom post types;
* taxonomieën;
* ACF JSON;
* gedeelde blocks;
* Calendly CTA-blok;
* UTM-opslag;
* kleine helperfuncties;
* multisite-instellingen.

Contenttypes horen bij voorkeur in een plugin, niet in een thema. Anders verdwijnen ze functioneel als je ooit van thema wisselt.

---

# 8. Git-inrichting

## Stap 8.1, repository starten

```bash
git init
```

## Stap 8.2, .gitignore maken

Gebruik:

```gitignore
.ddev/.global_commands
.ddev/.homeadditions
.ddev/.importdb*
public/wp-content/uploads/
public/wp-content/cache/
public/wp-content/upgrade/
public/wp-content/backups/
public/wp-content/ai1wm-backups/
*.sql
*.sql.gz
.env
.DS_Store
node_modules/
vendor/
```

Zet wel in Git:

```text
.ddev/config.yaml
docs/
scripts/
tests/
public/wp-content/themes/consul-base/
public/wp-content/themes/consul-12t/
public/wp-content/themes/consul-ctb/
public/wp-content/themes/consul-mki/
public/wp-content/plugins/consul-core/
README.md
CHANGELOG.md
```

Zet niet in Git:

```text
uploads
database
back-ups
wachtwoorden
productie-configuratie
persoonlijke exports
```

---

# 9. Git-proces zoveel mogelijk automatiseren

## Aanbevolen branches

Gebruik eenvoudig:

```text
main        productie
develop     dev/staging
feature/*   nieuwe wijziging
hotfix/*    spoedfix
```

## Werkwijze

Voor normale wijzigingen:

```bash
git checkout develop
git pull
git checkout -b feature/korte-omschrijving
```

Na werk:

```bash
git add .
git commit -m "Korte duidelijke omschrijving"
git push
```

Daarna:

```text
Pull request naar develop
Testen op dev/staging
Na akkoord merge naar main
Deploy naar productie
```

## Automatiseren met script

Maak:

```text
scripts/git-safe-commit.sh
```

Voorbeeld:

```bash
#!/bin/bash
set -e

echo "Controle: Git status"
git status --short

echo "Controle: tests draaien"
bash scripts/test-all.sh

echo "Bestanden klaarzetten"
git add .

echo "Commit maken"
git commit -m "$1"

echo "Push naar huidige branch"
git push
```

Gebruik:

```bash
bash scripts/git-safe-commit.sh "Wijzig Calendly CTA blok"
```

Dit voorkomt dat iemand commit zonder tests.

## Automatische bescherming

Stel in GitHub, GitLab of Bitbucket in:

* `main` mag niet direct gepusht worden;
* pull request verplicht;
* tests moeten slagen;
* minimaal één review voor productie;
* geen force push op `main`;
* tags gebruiken voor releases.

Voorbeeld release-tag:

```bash
git tag v1.0.0
git push origin v1.0.0
```

---

# 10. Automatisch testen klaarzetten en draaien

## Doel

Niet alles hoeft enterprise te worden. Begin met eenvoudige tests die de grootste fouten vinden.

## Testniveaus

Gebruik drie lagen:

```text
1. Technische checks
2. WordPress smoke tests
3. Browsertests voor belangrijke pagina’s
```

## 10.1 Technische checks

Controleer minimaal:

* PHP syntax;
* aanwezigheid van verplichte thema’s;
* aanwezigheid van plugin `consul-core`;
* WordPress laadt;
* Multisite-sites bestaan.

Voorbeeld script:

```text
scripts/test-all.sh
```

```bash
#!/bin/bash
set -e

echo "PHP syntax controleren"
find public/wp-content/themes public/wp-content/plugins/consul-core -name "*.php" -print0 | xargs -0 -n1 php -l

echo "WordPress core controleren"
ddev wp core version --path=public

echo "Multisite sites controleren"
ddev wp site list --path=public

echo "Plugins controleren"
ddev wp plugin list --path=public

echo "Thema's controleren"
ddev wp theme list --path=public

echo "Cache legen"
ddev wp cache flush --path=public

echo "Alle basistests geslaagd"
```

## 10.2 Smoke tests per lokale site

Controleer dat elke site een HTTP 200 teruggeeft.

Maak:

```text
scripts/test-smoke.sh
```

```bash
#!/bin/bash
set -e

URLS=(
  "https://consul-wp-ms.ddev.site"
  "https://12t.consul-wp-ms.ddev.site"
  "https://ctb.consul-wp-ms.ddev.site"
  "https://mki.consul-wp-ms.ddev.site"
)

for URL in "${URLS[@]}"; do
  echo "Test $URL"
  curl -k -I "$URL" | grep "200"
done

echo "Smoke tests geslaagd"
```

Voeg toe aan `test-all.sh`:

```bash
bash scripts/test-smoke.sh
```

## 10.3 Browsertests met Playwright

Gebruik Playwright alleen voor kernflows:

* homepage opent;
* contactpagina opent;
* Calendly CTA is zichtbaar;
* formulierpagina opent;
* geen duidelijke 404;
* geen loginpagina op publieke pagina’s.

Voorbeeld doelen:

| Site | Te testen                          |
| ---- | ---------------------------------- |
| 12t  | homepage, contact, Calendly CTA    |
| ctb  | homepage, events, contact          |
| mki  | homepage, kennisbank, Calendly CTA |

Gebruik Playwright pas na de basischecks. Begin niet met een grote testset.

## 10.4 Tests automatisch draaien bij Git

Laat tests draaien:

* vóór commit lokaal;
* bij pull request;
* vóór deployment naar productie.

Minimale regel:

```text
Geen succesvolle tests, geen merge naar main.
```

---

# 11. Automatisch documentatie actualiseren

## Doel

Niet-technische collega’s moeten altijd actuele Nederlandstalige instructies hebben.

## Aanpak

Maak documentatie in Markdown in Git:

```text
docs/redacteuren/
docs/beheer/
docs/releases/
```

Voorbeelden:

```text
docs/redacteuren/01-inloggen.md
docs/redacteuren/02-pagina-aanpassen.md
docs/redacteuren/03-afbeeldingen-gebruiken.md
docs/redacteuren/04-calendly-gebruiken.md
docs/redacteuren/05-publiceren.md
docs/beheer/01-gebruikers-en-rollen.md
docs/beheer/02-updates.md
docs/beheer/03-staging-en-productie.md
docs/beheer/04-releaseproces.md
```

## Automatisch genereren

Maak een script:

```text
scripts/docs-generate.sh
```

Doel van dit script:

* lijst plugins exporteren;
* lijst thema’s exporteren;
* lijst subsites exporteren;
* korte technische status toevoegen;
* Markdown-documentatie bijwerken;
* datum toevoegen;
* alles in het Nederlands houden.

Voorbeeld:

```bash
#!/bin/bash
set -e

DATE=$(date +"%Y-%m-%d")

mkdir -p docs/beheer/generated

echo "# Technische status" > docs/beheer/generated/technische-status.md
echo "" >> docs/beheer/generated/technische-status.md
echo "Laatst bijgewerkt: $DATE" >> docs/beheer/generated/technische-status.md
echo "" >> docs/beheer/generated/technische-status.md

echo "## Sites" >> docs/beheer/generated/technische-status.md
ddev wp site list --path=public --fields=blog_id,url,last_updated --format=table >> docs/beheer/generated/technische-status.md

echo "" >> docs/beheer/generated/technische-status.md
echo "## Plugins" >> docs/beheer/generated/technische-status.md
ddev wp plugin list --path=public --format=table >> docs/beheer/generated/technische-status.md

echo "" >> docs/beheer/generated/technische-status.md
echo "## Thema's" >> docs/beheer/generated/technische-status.md
ddev wp theme list --path=public --format=table >> docs/beheer/generated/technische-status.md
```

## Automatisch meenemen in Git-proces

Voeg aan `git-safe-commit.sh` toe:

```bash
bash scripts/docs-generate.sh
bash scripts/test-all.sh
git add docs
git add .
git commit -m "$1"
git push
```

Zo wordt documentatie automatisch bijgewerkt bij technische wijzigingen.

## Voor niet-technische collega’s

Publiceer de documentatie op één plek:

* interne WordPress-pagina;
* Notion;
* SharePoint;
* Google Drive;
* of een beveiligde `/handleiding`-pagina op dev/staging.

Mijn advies:

```text
Markdown in Git als bron
Gepubliceerde handleiding op dev/staging voor collega’s
```

Zo blijft de bron beheersbaar en de uitleg toegankelijk.

---

# 12. Calendly opnemen

## Aanbevolen keuze

Gebruik Calendly als:

```text
CTA-blok voor afspraakplanning
```

Niet als vervanging voor:

```text
Gravity Forms
leadregistratie
eventinschrijving
nieuwsbriefinschrijving
CRM-logica
```

Calendly documenteert dat Calendly in WordPress kan worden opgenomen via embedmethoden, waaronder WordPress.org en WordPress.com. ([Calendly.com][3])

## Implementatie

Maak in `consul-core` een herbruikbaar blok:

```text
Calendly CTA
```

Velden:

| Veld          | Type                             |
| ------------- | -------------------------------- |
| Titel         | Tekst                            |
| Intro         | Tekstgebied                      |
| Calendly URL  | URL                              |
| Weergave      | Keuze: knop, popup, inline embed |
| CTA-label     | Tekst                            |
| Consenttekst  | Tekstgebied                      |
| Trackinglabel | Tekst                            |

## Per site

| Site                    | Calendly-gebruik                     |
| ----------------------- | ------------------------------------ |
| 12Tender                | Contactgesprek of adviesgesprek      |
| Civiele Toekomstbouwers | Intakegesprek of oriëntatiegesprek   |
| MKI Kennis Instituut    | Kennismakingsgesprek of kennissessie |

## AVG en cookies

Let op:

* Calendly is een externe dienst;
* benoem Calendly in privacyverklaring;
* neem Calendly op in cookieverklaring als scripts of tracking worden geladen;
* toon bij voorkeur eerst een knop en laad de embed pas na klik of consent;
* vraag geen gevoelige persoonsgegevens via Calendly-vragen;
* laad Calendly niet onnodig op elke pagina.

## Plugin of eigen blok

Mijn voorkeur:

```text
Eigen ACF-blok in consul-core
```

Alternatief:

```text
EMC, Easily Embed Calendly Scheduling
```

De EMC-plugin biedt shortcodes voor inline, popup of button embeds. ([WordPress.org][4])

---

# 13. Basisplugins

## Must-have

Gebruik als uitgangspunt:

```text
GeneratePress Premium
GenerateBlocks Pro
ACF Pro
Gravity Forms Elite
The Events Calendar
Event Tickets Plus
Rank Math Pro
Relevanssi Premium
Redirection
Complianz
Post SMTP
Wordfence
UpdraftPlus Premium
WP Rocket of LiteSpeed Cache
ShortPixel of Imagify
WP Activity Log
Admin Columns Pro
Calendly via eigen ACF-blok of EMC-plugin
```

## Mijn voorkeur per functie

| Functie                | Keuze                                    |
| ---------------------- | ---------------------------------------- |
| Thema                  | GeneratePress Premium                    |
| Blokken                | GenerateBlocks Pro                       |
| Contentvelden          | ACF Pro                                  |
| Formulieren            | Gravity Forms Elite                      |
| Events                 | The Events Calendar + Event Tickets Plus |
| Afspraakplanning       | Calendly via eigen ACF-blok              |
| SEO                    | Rank Math Pro                            |
| Zoekfunctie            | Relevanssi Premium                       |
| Redirects              | Redirection                              |
| Consent                | Complianz                                |
| CRM of e-mailmarketing | FluentCRM of externe tool                |
| SMTP                   | Post SMTP                                |
| Security               | Wordfence                                |
| Back-ups               | Hostingback-ups + UpdraftPlus Premium    |
| Performance            | WP Rocket of LiteSpeed Cache             |
| Media                  | ShortPixel of Imagify                    |
| Logging                | WP Activity Log                          |

## Netwerkactiveren of per site activeren

Netwerkactiveren alleen als de plugin overal nodig is.

Waarschijnlijk netwerkactiveren:

```text
consul-core
ACF Pro
GenerateBlocks Pro
Post SMTP
Wordfence
WP Activity Log
Complianz, alleen als centraal beleid gewenst is
```

Per site activeren of configureren:

```text
Gravity Forms
The Events Calendar
Event Tickets Plus
Rank Math
Relevanssi
Redirection
Calendly-blokinstellingen
FluentCRM
```

---

# 14. Opzet staging en productie

Deze stap staat bewust na de basisinrichting en updateprocessen. Eerst moet de lokale basis stabiel zijn, daarna richt je dev/staging en productie strak in.

## 14.1 Omgevingen

Gebruik minimaal:

```text
Lokaal: DDEV
Dev/Staging: online acceptatieomgeving
Productie: live websites
```

Gebruik niet:

```text
Dev en productie op dezelfde database
Dev en productie met dezelfde uploads zonder beleid
Dev zonder wachtwoord
Dev met indexatie aan
Dev met productie-analytics
```

## 14.2 Dev/Staging-domeinen

Kies bijvoorbeeld:

```text
12t.dev.consulinfra.nl
ctb.dev.consulinfra.nl
mki.dev.consulinfra.nl
```

Of een andere duidelijke dev-structuur.

Dev/Staging moet hebben:

* eigen database;
* eigen uploads;
* eigen `wp-config.php`;
* eigen domeinen;
* SSL;
* noindex;
* basic auth;
* test-SMTP;
* test-analytics of geen analytics;
* test-Calendly-links, of duidelijk gemarkeerde afspraken;
* geen echte marketingpixels;
* geen actieve advertentiecampagnes.

## 14.3 Productiedomeinen

Gebruik:

```text
12tender.nl
civieletoekomstbouwers.nl
mkikennisinstituut.nl
```

Of voor MKI:

```text
www2.mkikennisinstituut.nl
```

Productie moet hebben:

* eigen database;
* eigen uploads;
* echte SMTP;
* echte cookieconsent;
* echte analytics;
* echte formulieren;
* echte Calendly-links;
* back-ups;
* monitoring;
* SSL;
* indexering aan.

## 14.4 Wildcard subdomeinen en serverconfiguratie

Omdat je Multisite met subdomeinen gebruikt, moet de externe server dit ondersteunen.

Nodig:

* wildcard DNS voor dev/staging;
* serverconfiguratie voor subdomeinen;
* SSL voor wildcard of losse domeinen;
* correcte mapping naar dezelfde WordPress-installatie.

WordPress beschrijft dat wildcard subdomains nuttig zijn bij domeingebaseerde Multisite-netwerken, omdat nieuwe subsites dan niet afzonderlijk hoeven te worden geconfigureerd. ([WordPress Developer Resources][5])

## 14.5 Productiedomeinen mappen

Per site:

```text
Network Admin > Sites > Edit Site > Site Address
```

Mapping:

```text
12t.consul-wp-ms.ddev.site → 12tender.nl
ctb.consul-wp-ms.ddev.site → civieletoekomstbouwers.nl
mki.consul-wp-ms.ddev.site → mkikennisinstituut.nl
```

## 14.6 Belangrijke waarschuwing

Gebruik geen platte SQL find and replace.

Niet doen:

```sql
UPDATE wp_posts SET post_content = REPLACE(...)
```

Wel doen:

```bash
wp search-replace "oude-url" "nieuwe-url" --network --dry-run
```

Daarna pas:

```bash
wp search-replace "oude-url" "nieuwe-url" --network --precise --recurse-objects
```

Altijd eerst op dev/staging testen.

---

# 15. Deploymentproces

## 15.1 Code deployment

Code gaat via Git.

Aanbevolen:

```text
develop → dev/staging
main → productie
```

Deployment naar dev:

```bash
bash scripts/deploy-dev.sh
```

Deployment naar productie:

```bash
bash scripts/deploy-production.sh
```

Voorbeeld `deploy-dev.sh`:

```bash
#!/bin/bash
set -e

git fetch origin
git checkout develop
git pull origin develop

wp cache flush
wp rewrite flush --hard

echo "Dev deployment klaar"
```

Voorbeeld `deploy-production.sh`:

```bash
#!/bin/bash
set -e

git fetch origin
git checkout main
git pull origin main

wp cache flush
wp rewrite flush --hard

echo "Productie deployment klaar"
```

## 15.2 Database deployment

Database nooit automatisch van lokaal naar productie pushen.

Wel toegestaan:

* productie naar lokaal halen voor testen;
* productie naar dev/staging kopiëren;
* gecontroleerde migratie van nieuwe site;
* gerichte configuratiewijzigingen met WP-CLI;
* contentinvoer door redacteuren op productie.

## 15.3 Media deployment

Uploads niet via Git.

Gebruik:

* hoster sync;
* rsync;
* migratietool;
* handmatige upload bij kleine aantallen;
* duidelijke afspraken per omgeving.

Voor Multisite extra opletten: uploads zijn per site gescheiden.

---

# 16. Per update

## Voor plugin-, thema- of WordPress-updates

Gebruik altijd:

```text
Dev/Staging eerst
Productie daarna
```

## Stappen

1. Maak back-up van dev/staging.
2. Update op dev/staging.
3. Draai automatische tests:

```bash
bash scripts/test-all.sh
```

4. Test handmatig:

   * homepage;
   * contactformulier;
   * Calendly CTA;
   * events;
   * zoekfunctie;
   * belangrijke subsites.
5. Maak productieback-up.
6. Update productie.
7. Leeg cache.
8. Test productie.
9. Werk logboek bij.

## Update-logboek

Zet in:

```text
docs/releases/update-log.md
```

Voorbeeld:

```text
Datum: 2026-05-15
Uitvoerder: Willem
Omgeving: productie
Type: pluginupdates
Sites getest: 12t, ctb, mki
Automatische tests: geslaagd
Handmatige controle: geslaagd
Rollback nodig: nee
Opmerkingen: geen
```

---

# 17. Dagelijks gebruik door redacteuren

## Redacteuren gebruiken alleen WordPress

Niet gebruiken:

```text
VS Code
Git
DDEV
hostingpaneel
database
FTP
pluginbeheer
thema-editor
```

Wel gebruiken:

```text
Pagina’s
Berichten
Media
Formulieren, indien toegestaan
Events, indien toegestaan
SEO-velden
Calendly CTA-blok
Concepten
Voorbeeldweergave
Publiceren
```

## Publicatieproces

Voor gewone content:

```text
Concept → Controle → Publiceren
```

Voor belangrijke pagina’s:

```text
Concept → Inhoudelijke controle → SEO-controle → AVG/cookiecheck → Publiceren
```

## Calendly gebruiken door redacteuren

Redacteur mag:

* Calendly CTA-blok toevoegen;
* titel aanpassen;
* intro aanpassen;
* CTA-label aanpassen;
* juiste Calendly-link kiezen.

Redacteur mag niet:

* nieuwe scripts handmatig plakken;
* Calendly tracking aanpassen;
* cookie-instellingen wijzigen;
* globale embedcode in thema of header plaatsen.

## Afbeeldingen

Afspraken:

* duidelijke bestandsnaam;
* alt-tekst verplicht;
* geen enorme uploads;
* geen stockfoto’s zonder rechten;
* geen gevoelige documenten in media uploaden;
* oude media niet zomaar verwijderen.

Goede bestandsnaam:

```text
12tender-aanbestedingsadvies-2026.jpg
```

Slechte bestandsnaam:

```text
IMG_8372.JPG
```

---

# 18. Onderhoud systeem door jou of collega

## Dagelijks

Controleer automatisch of handmatig:

* uptime;
* back-upstatus;
* securitymeldingen;
* formulierinzendingen;
* SMTP-log;
* foutmeldingen;
* verlopen SSL niet aanstaande.

## Wekelijks

* updates bekijken;
* staging bijwerken;
* tests draaien;
* plugins controleren;
* formulieren testen;
* Calendly CTA’s testen;
* back-upmeldingen controleren.

## Maandelijks

* gebruikers en rechten controleren;
* ongebruikte plugins verwijderen;
* ongebruikte thema’s verwijderen;
* redirects controleren;
* performance controleren;
* zoekfunctie controleren;
* events controleren;
* restoretest plannen;
* documentatie nalopen.

## Per kwartaal

* volledige restoretest;
* rechtenaudit;
* pluginlijst opschonen;
* securityreview;
* performancecheck;
* controle privacyverklaring;
* controle cookieverklaring;
* controle Calendly-verwerking.

---

# 19. Automatiseringsoverzicht

## Automatiseren

Wel automatiseren:

* lokale setup;
* documentatie-export;
* pluginlijst-export;
* themalijst-export;
* subsite-overzicht;
* Git commit-flow;
* tests;
* deployments van code naar dev;
* deployments van code naar productie na akkoord;
* back-upmeldingen;
* uptime monitoring.

## Niet volledig automatiseren

Niet volledig automatiseren:

* database push naar productie;
* domeinmigraties;
* search-replace zonder dry-run;
* pluginupdates direct naar productie;
* verwijderen van sites;
* gebruikersrechten wijzigen;
* Calendly consent-instellingen.

---

# 20. Belangrijkste valkuilen

## 1. Subdomeinen lokaal niet meteen goed instellen

Omdat je subdomeinen wilt, moet je vanaf dag één subdomeinen gebruiken. Later omzetten van subdirectories naar subdomeinen geeft onnodige migratierisico’s.

## 2. Dev en productie vermengen

Nooit dezelfde database gebruiken. Nooit dev laten indexeren. Nooit productie-analytics op dev laten meedraaien.

## 3. Database migreren alsof het gewone tekst is

WordPress gebruikt serialized data. Gebruik WP-CLI of een betrouwbare migratietool. Eerst dry-run, daarna pas uitvoeren.

## 4. Te veel plugins netwerkactiveren

Network activated betekent overal actief. Dat maakt storingen breder en debugging lastiger.

## 5. Calendly overal embedden

Een inline Calendly embed op elke pagina is slecht voor performance en consentbeheer. Gebruik liever knop, popup of laden na consent.

## 6. Redacteuren te veel rechten geven

Redacteuren hoeven geen pluginbeheer, thema-instellingen of Super Admin-rechten.

## 7. Tests pas achteraf bedenken

Zet tests meteen klaar, ook als ze eerst eenvoudig zijn. Een simpele homepage- en contactpagina-check voorkomt al veel fouten.

## 8. Documentatie handmatig laten verouderen

Documentatie moet deels automatisch worden bijgewerkt vanuit WordPress-status, pluginlijsten, thema’s en subsites.

---

# 21. Aanbevolen workflow

## Nieuwe functionaliteit

```text
Feature branch maken
↓
Aanpassen in VS Code
↓
Lokaal testen met DDEV
↓
Automatische tests draaien
↓
Documentatie automatisch bijwerken
↓
Commit via git-safe-commit
↓
Pull request naar develop
↓
Deploy naar dev/staging
↓
Acceptatie
↓
Merge naar main
↓
Productieback-up
↓
Deploy naar productie
↓
Controle
```

## Nieuwe site binnen Multisite

```text
Subsite lokaal aanmaken
↓
Thema koppelen
↓
Basisinstellingen doen
↓
Calendly CTA instellen indien nodig
↓
Tests toevoegen
↓
Dev/staging publiceren
↓
Redactie vult content
↓
Acceptatie
↓
Productiedomein koppelen
↓
URL-migratie zorgvuldig uitvoeren
↓
SSL en redirects testen
↓
Livegang
```

## Kleine contentwijziging

```text
Redacteur past content aan in WordPress
↓
Voorbeeld bekijken
↓
Controle door tweede persoon indien nodig
↓
Publiceren
```

Geen Git, DDEV of developer nodig.

## Pluginupdate

```text
Update op dev/staging
↓
Automatische tests
↓
Handmatige controle
↓
Productieback-up
↓
Update productie
↓
Controle
↓
Logboek
```

---

# 22. Praktische checklist

## Eenmalige lokale setup

* [ ] Projectmap `consul-wp-ms` aangemaakt.
* [ ] DDEV geïnstalleerd.
* [ ] DDEV project geconfigureerd.
* [ ] `additional_hostnames` toegevoegd.
* [ ] WordPress geïnstalleerd.
* [ ] Multisite geactiveerd.
* [ ] Subdomains gekozen.
* [ ] Sites `12t`, `ctb`, `mki` aangemaakt.
* [ ] VS Code workspace ingericht.
* [ ] Git repository aangemaakt.
* [ ] `.gitignore` toegevoegd.
* [ ] `docs/`, `scripts/` en `tests/` aangemaakt.

## Git en automatisering

* [ ] Branches `main` en `develop` ingericht.
* [ ] `feature/*` werkwijze afgesproken.
* [ ] Pull requests verplicht.
* [ ] Direct pushen naar `main` geblokkeerd.
* [ ] `scripts/test-all.sh` toegevoegd.
* [ ] `scripts/test-smoke.sh` toegevoegd.
* [ ] `scripts/docs-generate.sh` toegevoegd.
* [ ] `scripts/git-safe-commit.sh` toegevoegd.
* [ ] Tests draaien vóór commit.
* [ ] Tests draaien vóór merge naar `main`.

## Dev/Staging

* [ ] Dev/Staging heeft eigen database.
* [ ] Dev/Staging heeft eigen uploads.
* [ ] Dev/Staging heeft eigen domeinen.
* [ ] Dev/Staging heeft SSL.
* [ ] Dev/Staging staat op noindex.
* [ ] Dev/Staging is afgeschermd.
* [ ] Test-SMTP ingesteld.
* [ ] Test-analytics of geen analytics ingesteld.
* [ ] Test-Calendly of duidelijke testlabels ingesteld.

## Productie

* [ ] Productiedomeinen gekoppeld.
* [ ] DNS ingesteld.
* [ ] SSL actief.
* [ ] Domeinmapping gecontroleerd.
* [ ] Productie heeft eigen database.
* [ ] Productie heeft eigen uploads.
* [ ] Echte SMTP ingesteld.
* [ ] Cookieconsent ingesteld.
* [ ] Analytics ingesteld.
* [ ] Monitoring ingesteld.
* [ ] Back-ups ingesteld.
* [ ] Indexering aan.

## Per update

* [ ] Dev/Staging-back-up gemaakt.
* [ ] Update op dev/staging uitgevoerd.
* [ ] Automatische tests geslaagd.
* [ ] Handmatige controle gedaan.
* [ ] Productieback-up gemaakt.
* [ ] Productie bijgewerkt.
* [ ] Cache geleegd.
* [ ] Productie getest.
* [ ] Logboek bijgewerkt.

## Per domeinwijziging of migratie

* [ ] Volledige back-up gemaakt.
* [ ] Oude URL genoteerd.
* [ ] Nieuwe URL genoteerd.
* [ ] DNS voorbereid.
* [ ] SSL voorbereid.
* [ ] Search-replace dry-run uitgevoerd.
* [ ] Search-replace definitief uitgevoerd.
* [ ] Media gecontroleerd.
* [ ] Menu’s gecontroleerd.
* [ ] Formulieren gecontroleerd.
* [ ] Calendly CTA’s gecontroleerd.
* [ ] Redirects getest.
* [ ] Cache geleegd.
* [ ] Sitemap opnieuw gegenereerd.

## Voor redacteuren

* [ ] Nederlandstalige handleiding beschikbaar.
* [ ] Inloginstructie beschikbaar.
* [ ] Handleiding pagina aanpassen beschikbaar.
* [ ] Handleiding afbeelding uploaden beschikbaar.
* [ ] Handleiding Calendly CTA gebruiken beschikbaar.
* [ ] Publicatieproces afgesproken.
* [ ] Rollen goed ingesteld.
* [ ] Controlepersoon bekend.
* [ ] Afspraken over verwijderen bekend.

---

## Definitieve aanbeveling

Gebruik **DDEV + WordPress Multisite + subdomeinen + Git + VS Code** als technische basis. Richt vanaf het begin `consul-wp-ms` in met de lokale subdomeinen `12t`, `ctb` en `mki`. Houd dev/staging en productie strikt gescheiden. Automatiseer Git, tests, documentatie en code-deployments. Houd database-migraties, URL-wijzigingen en domeinmapping bewust gecontroleerd met back-up, dry-run en handmatige eindcontrole.

[1]: https://docs.ddev.com/en/stable/ "Get Started with DDEV - DDEV Docs"
[2]: https://developer.wordpress.org/cli/commands/search-replace/ "wp search-replace – WP-CLI Command | Developer.WordPress.org"
[3]: https://calendly.com/help/how-to-add-calendly-to-a-wordpress-site "How to add Calendly to a WordPress site"
[4]: https://wordpress.org/plugins/embed-calendly-scheduling/ "EMC – Easily Embed Calendly Scheduling - WordPress.org"
[5]: https://developer.wordpress.org/advanced-administration/server/subdomains-wildcard/ "Configuring Wildcard Subdomains - WordPress Developer Resources"
