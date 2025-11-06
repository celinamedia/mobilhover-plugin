# Mobilhover-plugin
Et simpelt, interaktivt WordPress-plugin udviklet som en del af Storyscaping-projektet for **Race for Oceans Technology**.  
Plugin’et skaber en hover-animation, hvor en telefon bevæger sig og afslører tekst. 

# Brug af AI
### Anvendte AI værktøjer:
- **Claude.ai**
    - Anvendt til at opbygge den grundlæggende struktur og layout i pluginet

- **ChatGPT**
    - Benyttet til fejlfinding og debugging af PHP- og CSS-filer
    - Givet forslag til optimering og semantisk strukturering af koden
    - Hjælp til udarbejdelse af README.md-struktur og dokumentation

### Manuel tilpasning og egen indsats

Efterfølgende er al kode blevet manuelt gennemgået, testet og tilpasset.
Kommentarer genereret af Claude.ai er omskrevet og udvidet, så de afspejler funktionaliteten mere præcist og i mit eget sprog.
Ændringer og egen tilpasning kan dokumenteres via commits i det tilknyttede GitHub-repository.
Ingen AI-genereret kode er anvendt uden manuel evaluering og test i WordPress-miljøet.

# Formål
Formålet med dette plugin er at tilføje et visuelt og interaktivt element til Race for Oceans Technologys WordPress-site.

Det er udviklet som en del af Storyscaping-forløbet for at vise forståelse for WordPress-pluginstruktur, animationer og brugerinteraktion i frontend.

# Struktur i php fil
I mit plugin fungerer `phone-animation.php` som hovedfilen, der håndterer indlæsning af stylesheet og genereringen af selve HTML-strukturen via en shortcode.


Øverst i filen defineres pluginets grundlæggende information:
```PHP
/**
 * Plugin Name: Phone Hover Animation
 * Plugin URI: https://rfo.mikkelsdesign.dk/
 * Description: Animated phone that rises on hover to reveal text below
 * Version: 1.1.3
 * Author: Celina Bækgaard
 * Author URI: https://github.com/celinamedia
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */
```
Jeg har løbende opdateret `Version:` nummeret efter hvert commit til GitHub.

Funktionen `plugin_dir_url(__FILE__)` bliver brugt til at finde den korrekte sti til pluginets filer, fx billeder og CSS.
Den fungerer på samme måde som plugins_url(), men tager automatisk udgangspunkt i den aktuelle PHP-fil.
På den måde kan jeg hente filer direkte fra pluginets mappe, uanset hvor pluginet er installeret i WordPress.
``` PHP
/* Definerer konstanter til pluginet */
define('PHONE_ANIMATION_VERSION', '1.1.3');
define('PHONE_ANIMATION_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('PHONE_ANIMATION_PLUGIN_URL', plugin_dir_url(__FILE__));
```


## Funktionens opbygning
Funktionen starter med at definere et array af standardværdier ved hjælp af shortcode_atts().


```PHP
/* Shortcode til phone animation */
function phone_animation_shortcode($atts) {
    /* Sætter standardværdier for shortcode */
    $atts = shortcode_atts(array(
        'link' => '#',
        'title' => 'Capture Plastic Waste',
        'subtitle' => '',
        'phone_image' => '',
    ), $atts, 'phone_animation'); 
```
Denne PHP-funktion bruges til at kombinere de værdier, der er skrevet i shortcoden, med de standardværdier, jeg har angivet i pluginet. 

### Valg af billede
---
Efter at shortcode-attributterne er defineret, tjekker funktionen, om brugeren har angivet et billede.
Hvis ikke, bruges et standardbillede fra pluginets mappe.

``` PHP
    /* vælger telefonbillede */
    if (!empty($atts['phone_image'])) {
        /* Bruger billedet fra linket */
        $phone_src = esc_url($atts['phone_image']);
    } else {
        /* Bruger standardbilledet */
        $phone_src = esc_url(PHONE_ANIMATION_PLUGIN_URL . 'assets/images/iphoneRFOT.png');
    }
```
Her bruges funktionen `esc_url()`, som er en indbygget WordPress-funktion til at rense og validere URL’er, før de vises i HTML.

### Output buffering og HTML-struktur
---
Efter at shortcode-attributterne og billedet er defineret, starter funktionen en output-buffer med `ob_start()`.
Det betyder, at alt HTML-indhold midlertidigt bliver gemt, i stedet for at blive vist med det samme.
```PHP
    ob_start();
    //HTML indhold
    return ob_get_clean();
```
`ob_get_clean()` henter derefter hele HTML-strukturen som én samlet tekststreng og sender den tilbage til WordPress.

Her kunne man også have brugt variablen `$content` til at samle HTML’en, men jeg har i stedet valgt `ob_start()` for at gøre koden mere overskuelig.

HTML-strukturen er bygget op i flere lag for at gøre animationen nem at styre med CSS.
`phone-animation-wrapper` fungerer som det ydre container-element, der samler hele animationen.
Inde i den ligger `phone-container`,som opdeler layoutet i to hoveddele:
1. `text-wrapper` og `hidden-text`, som indeholder titlen og den tekst, der bliver synlig ved hover.
2. `phone-wrapper`, som indeholder linket og billedet af telefonen.
```HTML
    <div class="phone-animation-wrapper">
        <div class="phone-container">
            <div class="text-wrapper">
                <div class="hidden-text"> ```
                    <h3><?php echo esc_html($atts['title']); ?></h3>
                    <p><?php echo esc_html($atts['subtitle']); ?></p>
                </div>
            </div>
            <div class="phone-wrapper">
                <a href="<?php echo esc_url($atts['link']); ?>" class="phone-link">
                    <img src="<?php echo $phone_src; ?>" alt="Phone with the text Capture Plastic Waste" class="phone-image">
                </a>
            </div>
        </div>
    </div>
```

### Shortcode
---
Til sidst registreres funktionen som en shortcode i WordPress.
Det gør jeg med `add_shortcode()`, som forbinder navnet på shortcoden med selve PHP-funktionen.
``` PHP
add_shortcode('phone_animation', 'phone_animation_shortcode');
```
`('phone_animation')` er navnet på den shortcode, man skriver i WordPress-editoren.
`('phone_animation')` Er navnet på den funktion, der skal køres, når shortcoden kaldes.