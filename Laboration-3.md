# Laboration 3

## Reflektionsfrågor

### Vad finns det för krav du måste anpassa dig efter i de olika API:erna?
När det gäller Sveriges Radios API så är man tvungen att använda sig av XML, JSON eller JSONP. Man kan också bara göra GET-förfrågningar mot API:et. Det finns kanske inte direkt krav, men SR vill att man gör så få förfrågningar som möjligt mot deras API. 
I Google Maps API är man tvugen att använda sig utav en "API key" för att kunna skicka förfrågningar mot API:et. "Keyn" får man genom att registerar sig som en utvecklare så att Google Maps har koll på vem som använder deras tjänst. Man är också tvungen att använda Javacript för att få API:et att fungera.

### Hur och hur länga cachar du ditt data för att slippa anropa API:erna i onödan?
Jag har valt att cacha mitt data var femte minut. Tycker det är fel att cacha det längre än så här med tanke på att användarna hela tiden ska få vara uppdaterade. Det är klart att man tjänar på att ha längre cachningstid då det blir färre förfrågningar mot API:et, men tyvärr anser jag att det inte är möjligt att ha det längre i en sådan här applikation. Maximalt kan det bli 288 förfrågningar på en dag vilket ändå får ses som bra.

### Vad finns det för risker kring säkerhet och stabilitet i din applikation?
- Vem som helst kan få tillgång till min API-nyckel.
- Går något av API:erna ner så fungerar inte applikationen fullt ut.

### Hur har du tänkt kring säkerheten i din applikation?
- Försökt ta bort möjligheten till XSS.
- Har velat använda HTTPS, dock inte haft möjlighet till det.
- Querystringen för kategori i URL:en tar endast tal mellan 0 och 3. Om inte detta uppfylls så returneras standardkategorin (Alla kategorier).
- API-keyn för Google Maps är synlig. Har inte hittat något sätt att dölja den.

### Hur har du tänkt kring optimeringen i din applikation?
- Försökt ha få CSS- och Scriptfiler.
- Cachat filer som inte kommer ändras så ofta.
- Lagt CSS-filerna tidigt i koden.
- Lagt Script-filerna längst ner i koden.

## Körbar applikation
http://pavic10.byethost17.com/projects/1dv449-laboration-3/index.php
