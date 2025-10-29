# Google Places & GloriaFood Plugin voor WordPress

Een WordPress plugin die openingstijden, reviews en menu's weergeeft via de Google Places API en GloriaFood API.

## Functies

- Toon openingstijden van je bedrijf
- Geef aan of je zaak nu open of gesloten is
- Toon Google reviews
- Toon GloriaFood menu
- Automatische caching voor betere performance
- Veilige opslag van API keys in WordPress database

## Installatie

1. Upload de `google-places-plugin` map naar `/wp-content/plugins/`
2. Activeer de plugin via het 'Plugins' menu in WordPress
3. Ga naar **Instellingen > Google Places** om je API keys in te stellen

## Configuratie

### API Keys instellen

1. Ga in WordPress naar **Instellingen > Google Places**
2. Voer de volgende gegevens in:
   - **Google Places API Key**: Jouw Google Places API key
   - **Google Place ID**: De Place ID van je bedrijf
   - **GloriaFood API Key**: Jouw GloriaFood API key (indien van toepassing)
3. Klik op **Instellingen opslaan**

### Google Places API Key verkrijgen

1. Ga naar [Google Cloud Console](https://console.cloud.google.com/)
2. Maak een nieuw project aan of selecteer een bestaand project
3. Activeer de **Places API (New)**
4. Ga naar **Credentials** en maak een API key aan
5. Kopieer de API key en plak deze in de plugin instellingen

### Google Place ID vinden

1. Ga naar [Google Place ID Finder](https://developers.google.com/maps/documentation/places/web-service/place-id)
2. Zoek je bedrijf op
3. Kopieer de Place ID en plak deze in de plugin instellingen

## Gebruik

De plugin biedt de volgende shortcodes:

### Openingstijden
```
[openingstijden]
```
Toont een lijst met de openingstijden van je bedrijf.

### Open/Gesloten status
```
[open_now]
```
Toont of je zaak nu open of gesloten is, en wanneer deze weer opengaat.

### Google Reviews
```
[gplaces_reviews]
```
Toont de Google reviews van je bedrijf.

### GloriaFood Menu
```
[gloriafood_menu]
```
Toont het menu uit GloriaFood.

## Caching

De plugin gebruikt automatische caching om API calls te beperken:
- Openingstijden: 1 uur cache
- Reviews: 1 uur cache

Cache bestanden worden opgeslagen in de WordPress content directory.

## Beveiliging

- API keys worden veilig opgeslagen in de WordPress database
- Alle bestanden zijn beschermd tegen directe toegang
- Geen hardcoded API keys in de code
- `.gitignore` voorkomt dat gevoelige bestanden op GitHub komen

## Vereisten

- WordPress 5.0 of hoger
- PHP 7.4 of hoger
- Google Places API key met Places API (New) enabled
- GloriaFood account en API key (optioneel)

## Support

Voor vragen of problemen, neem contact op met de auteur.

## Auteur

Harm van 't Leven

## Versie

1.0

## Licentie

Dit is een custom plugin ontwikkeld voor specifiek gebruik.
