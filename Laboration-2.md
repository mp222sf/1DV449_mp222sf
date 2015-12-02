# Rapport Laboration 2
Namn: Mattias Pavic

LNU-namn: mp222sf

Datum: 2015-12-02

## Säkerhetsproblem

### Cross Site Scripting (XSS)
Ett säkerhetsproblem som finns i applikationen är att vem som helst kan skicka med HTML-kod eller JavaScript-kod när man postar ett meddelande i applikationen. 

Det kan leda till att någon lägger in skadlig JavaScript-kod på sidan som exekveras för alla som besöker den. Då kan bedragaren t.ex. få tillgång till andra användares cookies, sessions och annan känslig information [3].

En lösning på problemet är att undvika alla form av HTML-innehåll när något skrivs in i t.ex. ett formulär. Ett annat sätt att lösa det på är att använda sig av en "white list" som bestämmer vad som ska få skrivas in. Den skall validera längd, tecken, formatet och affärsregler innan den accepterar data [4, s.9].


### Sensitive Data Exposure
Ett problem är att applikationen inte använder sig av SSL på alla autentiserade sidor. En angripare kan då enkelt övervaka nätverkstrafiken och ta användares session cookies. Angriparen använder sedan denna cookie och tar över den oskyldiga användarens session. Detta leder till att angriparen kan få tillgång till användarens privata data [4]. 

Den viktigaste åtgärd man ska göra vad det gäller detta problem är att använda sig av SSL [4]. 
SSL står för Secure Sockets Layer och är en teknologi som skapar en krypterad länk mellan server och klient. Det tillåter därför känslig data så som kreditkortnummer, personnummer och inloggningsinformation att överföras säkert. [5]

Som användare kan man ta hjälp av HTTP Strict Transport Security (HSTS) som hjälper en webbläsare att endast kommunicera genom HTTPS, istället för HTTP. HSTS varnar när man angett en sida som använder sig av HTTP och försöker direkt ändra så att HTTPS används. [6]


### Cross Site Request Forgery
Angripare kan ta över din session-cookie som är inloggad i applikationen och kan sedan skicka förfrågningar till applikationen via cookien. Dessa förfrågningar skickas från din webbläsare och servern som tar mot förfrågningarna tror att det är du, eftersom att det är din cookie. [4, s.14]

Lösningen är att skicka med ett såkallat "token", som kan vara en slumpgenererad sträng, när inloggning sker. "Token:et" placeras i koden, exempelvis i ett formulär, och när sedan en post skicka så ska det skickas med. Detta gör att någon som inte har det unika "token:et" inte kan göra några postningar. [4, s.14]


### Data tillgängligt för allmänheten
Sidan (http://localhost:3000/message/data) där alla meddelandena sparas kan kommas åt utan att vara inloggad. Detta gör att vem som helst får tillgång till meddelandena vilket inte är bra. Tanken med applikationen är att användare/konkurrenter som inte har inloggningsuppgifter inte ska ha tillgång till den. [4, s.13]

Följderna till detta problem blir ju att all hemlig data öppnas upp för allmänheten. För att lösa problemet måste man göra så att vem som helst inte får åtkomst till datan. Ett sätt kan vara att börja med att inte ge något åtkomst till sidorna i applikationen, för att sedan öppna upp det som ska vara tillgängligt. [4, s.13]

 
## Prestandaproblem

### Separata scripts och stylesheets
Applikationen som testas byggs upp med flera script och stylesheets. Bara startsidan till applikationen innehåller fem stylesheets och fyra scripts som måste laddas in. Ju fler script och stylesheets som behövs och måste laddas ner, desto fler förfrågningar måste skickas. Varje förfrågning tar tid vilket gör att det tar lång tid att ladda ner en hel sida [1, s.16]. 

Lösningen är att slå ihop alla scripten till ett script och att göra likadant med alla stylesheets. Då behövs bara en förfrågan skickas för att kunna hämta scripten och en förfrågan för att hämta alla stylesheets. Det som är dåligt med den här lösningen är att det blir svårare för utvecklare att arbeta med. Det finns en anledning till att man delar upp kod i olika filer, främst för att separera delar som inte hör ihop varandra och även för att enklare förstå koden som är skriven [1, s.16].


### Kort eller ingen "Expires-time"
Ett ganska stort problem med applikationens prestanda är att alla komponenter hämtas varje gång en sida laddas om. Komponenterna är bland annat bilder, script och stylesheets som sällan ändras [1, s.22]. 

För att förbättra applikationens prestanda ska man använda sig utav "cache". Första gången en användare laddar ner sidan så bör de tidigare nämnda komponenterna cache:as. När sedan sidan laddas om så behöver inte klienten skicka HTTP-förfråningar till servern angående dessa komponenter, vilket gör att responstiden blir kortare [1, s.22]. Viktigt när man ska använda sig av cache är att ange värden för Cache-Control och Expires [1, s.23]. 

Förslag på filer som bör cache:as:
-	jquery.js
-	bootstrap.css
-	signin.css
-	Message.js
-	MessageBoard.js
-	b.jpg
-	logo.png


### Ingen GZIP-kompression
Ett problem med applikationen är att servern skickar tillbaka helt vanliga dokument vid förfrågningar av t.ex. HTML och Javascript-dokument. I vissa fall kan dessa dokument vara stora och ta ganska lång tid att hämta [2]

För att lösa ett sådant här problem kan man använda sig av kompression. Det gör man för att göra dokumentet som ska hämtas mindre. Klienten skickar med "Accept-encoding: gzip" vid sin HTTP-förfrågan mot servern. Om servern stödjer den här typen av kompression (gzip) så skickar den tillbaka en zippad fil till klienten med responsen "Content-encoding: gzip", så att klienten förstår att dokumentet är komprimerat. För att få servern att stödja detta måste man gå in och konfigurera den. Det görs på olika sätt för olika servrar [2]. 

Värt att nämna är att det även går att komprimera i formatet Deflate. Det fungerar också bra, men Gzip verkar vara mycket kraftfullare och används därför kanske oftare [2].


### Stylesheet mitt i koden
Problemet är att det ligger css-kod i mitten av sidans innehåll. Det gör att användaren kan uppleva att sidan laddas långsamt. I själva verket så tar det så här lång tid att ladda stylesheetet, men problemet är att det tar tid innan användaren får se något på skärmen. Skärmen är helt vit ända tills allt laddats klart [1, s.37-38, s,42].

Lösningen är att lägga alla css-kod på toppen av sidan, inuti head-taggen. Det gör att sidan laddas gradvis vilket gör att man slipper den vita sidan och därför upplever användaren att det går snabbare att ladda klart sidan [1, s,41].


### Scripts blockerar nedladdningar
Ett problem är att script ligger på fel plats i koden. En del Javascript-kod ligger inuti Head-taggen vilket gör att sidan laddas in långsammare. Problemet när script ska laddas in är att under tiden som detta sker så kan inget annat innehåll laddas in samtidigt. Det gör att sidan visar upp en helt vit sida ända tills scripten har laddats in [1, s.45, s.48].

Lösningen på det här problemet är att lägga alla script längst ned i koden (finns undantag). Det gör att allt annat innehåll inte blockeras när scriptet laddas in och visas mycket tidigare än om scriptet skulle laddas in i början av koden [1, s.49-50]. 


### Normalstora JS och CSS-dokument
Problemet är att man inte förminskar sin Javascript och CSS-kod. Applikationens prestanda blir sämre ju större filer/dokument som måste laddas in.

Därför kan man använda sig av något som kallas "minification", på svenska beskrivet som "förminska". Det hela går ut på att man tar hjälp av olika program (t.ex. JSMin, utvecklat av Douglas Crockford) som gör koden mindre. Det den egentligen gör är att ta bort onödiga tecken för att förminska storleken på dokumentet. Alla kommentarer tas bort, likaså alla onödiga blanksteg (mellanslag, ny rad och ny flik). I applikationen bör samtliga JS-filer använda sig av JSMin [1, s.70-73].

Om man använder sig av både JSMin och Gzip kompression, som jag skrev om tidigare i rapporten, så kan man minska en fil oerhört mycket. I exemplet i kurslitteraturen så ges ett exempel där en fil från start är 85kb och efter att man använt både JSMin och Gzip så minskar samma fil till 19kb [1, s.74-75].
 
## Egna reflektioner

Regel 8 i kurslitteraturen som säger att man bör göra Javascript och CSS extern har jag valt att inte använda mig av. Detta är för att jag inte ser fördelen med det i den här applikationen. En vanlig användare använder sig bara av en sida, som i stort sett aldrig laddas om. Hade däremot sidan laddats om ofta eller att det funnit andra sidor att vandra mellan så hade detta varit en fördel. Externt Javascript- och CSS-kod kan cachas, vilket gör att man slipper läsa in allt om och om igen.


 
## Referenser
[1] Steve Souders, High Performance Web Sites: Essential Knowledge for Frontend Engineers. Sebastopol: O'Reilly Media, Inc., 2007. http://www.it.iitb.ac.in/frg/wiki/images/4/44/Oreilly.Seve.Suoders.High.Performance.Web.Sites.Sep.2007.pdf

[2] Kalid Azad, "How To Optimize Your Site With GZIP Compression", Better Explained, okänt [Online] Tillgänglig: http://betterexplained.com/articles/how-to-optimize-your-site-with-gzip-compression/. [Hämtad: 30 november, 2015].

[3] Acunetix, "Cross-site Scripting (XSS) Attack", Acunetix, okänt [Online] Tillgänglig: https://www.acunetix.com/websitesecurity/cross-site-scripting/. [Hämtad: 2 december, 2015].

[4] OWASP, "OWASP Top 10 - 2013: The Ten Most Critical Web Application Security Risks", OWASP, okänt [Online] Tillgänglig: http://owasptop10.googlecode.com/files/OWASP%20Top%2010%20-%202013.pdf. [Hämtad: 30 november, 2015]

[5] DigiCert, "What Is SSL (Secure Sockets Layer) and What Are SSL Certificates?", DigiCert, okänt [Online] Tillgänglig: https://www.digicert.com/ssl.htm [Hämtad: 2 december, 2015]

[6] John Whitlock, "HTTP Strict Transport Security", Mozilla Developer Network, 28 oktober 2015 [Online] Tillgänglig: https://developer.mozilla.org/en-US/docs/Web/Security/HTTP_strict_transport_security [Hämtad: 2 december, 2015]
