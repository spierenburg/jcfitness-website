# Instagram Feed Setup — JC Fitness Website

De website toont standaard 3 Instagram embeds als fallback. Zodra de API-token is ingesteld, schakelt het automatisch over naar een dynamische feed met de laatste 6 posts.

---

## Stap 1: Facebook App aanmaken (~5 min)

1. Ga naar https://developers.facebook.com
2. Log in met het Facebook account dat gekoppeld is aan de Instagram business pagina
3. Klik **"Create App"** → kies **"Business"**
4. Geef het een naam, bijv. "JC Fitness Website"
5. Voeg het product **"Instagram Basic Display"** toe

## Stap 2: Instagram Basic Display instellen (~5 min)

1. In de app: **Instagram Basic Display → Basic Display**
2. Vul de volgende URLs in (mogen dummy zijn):
   - Valid OAuth Redirect URI: `https://jcfitness.nl/`
   - Deauthorize Callback URL: `https://jcfitness.nl/`
   - Data Deletion Request URL: `https://jcfitness.nl/`
3. Voeg het Instagram account toe als **"Instagram Test User"**
4. Ga naar **Roles → Instagram Testers**
5. Accepteer de uitnodiging in de Instagram app (Instellingen → Website → Apps en websites → Tester-uitnodigingen)

## Stap 3: Token genereren (~5 min)

1. In de app: **Instagram Basic Display → Generate Token**
2. Log in met het `@jc.fitnessnl` Instagram account
3. Geef toestemming
4. Kopieer de gegenereerde token

## Stap 4: Token invullen (~1 min)

### Op TransIP (productie):
1. Open het bestand `instagram.php` op de server
2. Vervang `INSTAGRAM_ACCESS_TOKEN_HIER` (regel 6) met de gekopieerde token
3. Sla op — klaar!

### Op Netlify (demo):
1. Ga naar https://app.netlify.com
2. Open het project → **Site Settings → Environment Variables**
3. Voeg toe: `INSTAGRAM_ACCESS_TOKEN` = de gekopieerde token
4. Redeploy de site

## Stap 5: Klaar!

De feed laadt nu automatisch de laatste 6 Instagram posts. De token is 60 dagen geldig en wordt automatisch verlengd door het script.

---

## Hoe het werkt

- **Met token:** De website haalt automatisch de laatste 6 posts op via de Instagram API. Posts worden 15 minuten gecached voor snelheid.
- **Zonder token:** De website toont 3 handmatig ingestelde Instagram embeds als fallback.

## Fallback embeds bijwerken

Als je de handmatige embeds wil vervangen (bijv. met nieuwere posts):

1. Ga naar een post op instagram.com
2. Kopieer de URL (bijv. `https://www.instagram.com/p/DFoGwSpobXa/`)
3. Open `index.html`, zoek de `instagram-embeds` sectie
4. Vervang de URL in de `iframe src`, het format is: `https://www.instagram.com/p/POST_ID/embed`

## Bestanden

| Bestand | Doel |
|---------|------|
| `instagram.php` | PHP script voor TransIP — haalt posts op en cached ze |
| `netlify/functions/instagram.js` | Netlify Function — zelfde functie voor Netlify hosting |
| `.htaccess` | Blokkeert directe toegang tot het cache-bestand |
| `instagram-cache.json` | Wordt automatisch aangemaakt — cache van de feed |

## Troubleshooting

- **Feed laadt niet:** Controleer of de token correct is ingevuld en niet verlopen
- **Lege feed:** Het Instagram account moet publiek zijn
- **Token verlopen:** Genereer een nieuwe token via developers.facebook.com en vervang de oude
