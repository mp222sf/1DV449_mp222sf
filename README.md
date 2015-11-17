# 1DV449
## Reflektionsfrågor - Laboration 1

#### Finns det några etiska aspekter vid webbskrapning. Kan du hitta något rättsfall?
Börja med att kolla vad som sägs i robots.txt för att få information om vad som tillåts.
Man bör även berätta för servern vem man är.
Rättsfall: eBay v. Bidder's Edge, 2000. BE samlade info från flera olika aktionssajter.

#### Finns det några riktlinjer för utvecklare att tänka på om man vill vara "en god skrapare" mot serverägarna?
Försöka skicka så få förfrågningar till servern som möjligt. Vet man att man kommer att återanvända informationen man hämtat från en sida så kan man enkelt spara den (cache? session?) istället för att göra två förfrågningar till servern.

#### Begränsningar i din lösning- vad är generellt och vad är inte generellt i din kod?
Ändras namnen på de olika kategorierna (kalender, biograf, restaurang) så lär inte scriptet fungera lika bra. När man bokar ett bord så är det viktigt att användarnamn och lösenord inte ändrat.

#### Vad kan robots.txt spela för roll?
I robots.txt kan det finnas information om vad ägaren av sidan vill att man ska få tillgång till om man är icke-människa, vilket gäller för t.ex. Google-botar och andra sökmotorer. Sidor kanske inte vill att man ska skrapa från deras sidor. Det kan därför stå i robots.txt vad som inte är tillåtet att skrapa. Bryter man mot dessa regler finns risken för att man blir bannad.

## Kuriosa
* Fotbollslag: .... (lägg av) ;)
