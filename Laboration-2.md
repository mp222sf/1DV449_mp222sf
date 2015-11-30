# Rapport Laboration 2
Mattias Pavic
mp222sf
2015-12-03

## Säkerhetsproblem

## Prestandaproblem

### Separata scripts och stylesheets
Applikationen som testas byggs upp med flera script och stylesheets. Bara startsidan till applikationen innehåller fem stylesheets och fyra scripts som måste laddas in. Ju fler script och stylesheets som behövs och måste laddas ner, desto fler förfrågningar måste skickas. Varje förfrågning tar tid vilket gör att det tar lång tid att ladda ner en hel sida [1, s.16]. 

Lösningen är att slå ihop alla scripten till ett script och att göra likadant med alla stylesheets. Då behövs bara en förfrågan skickas för att kunna hämta scripten och en förfrågan för att hämta alla stylesheets. Det som är dåligt med den här lösningen är att det blir svårare för utvecklare att arbeta med. Det finns en anledning till att man delar upp kod i olika filer, främst för att separera delar som inte hör ihop varandra och även för att enklare förstå koden som är skriven [1, s.16].

### Kort eller ingen "Expires-time"
Ett ganska stort problem med applikationens prestanda är att alla komponenter hämtas varje gång en sida laddas om. Komponenterna är bland annat bilder, script och stylesheets som sällan ändras [1, s.22]. 

För att förbättra applikationens prestanda ska man använda sig utav "cache". Första gången en användare laddar ner sidan så bör de tidigare nämnda komponenterna cache:as. När sedan sidan laddas om så behöver inte klienten skicka HTTP-förfråningar till servern angående dessa komponenter, vilket gör att responstiden blir kortare [1, s.22]. Viktigt när man ska använda sig av cache är att ange värden för Cache-Control och Expires [1, s.23]. 

Förslag på filer som bör cache:as:
•	jquery.js
•	bootstrap.css
•	signin.css
•	Message.js
•	MessageBoard.js
•	b.jpg
•	logo.png


### Ingen GZIP-kompression
Ett problem med applikationen är att servern skickar tillbaka helt vanliga dokument vid förfrågningar av t.ex. HTML och Javascript-dokument. I vissa fall kan dessa dokument vara stora och ta ganska lång tid att hämta [2]

För att lösa ett sådant här problem kan man använda sig av kompression. Det gör man för att göra dokumentet som ska hämtas mindre. Klienten skickar med "Accept-encoding: gzip" vid sin HTTP-förfrågan mot servern. Om servern stödjer den här typen av kompression (gzip) så skickar den tillbaka en zippad fil till klienten med responsen "Content-encoding: gzip", så att klienten förstår att dokumentet är komprimerat. För att få servern att stödja detta måste man gå in och konfigurera den. Det görs på olika sätt för olika servrar [2]. 

Värt att nämna är att det även går att komprimera i formatet Deflate. Det fungerar också bra, men Gzip verkar vara mycket kraftfullare och används därför kanske oftare [2].

### Stylesheet mitt i koden
Problemet är att det ligger css-kod i mitten av sidans innehåll. Det gör att användaren kan uppleva att sidan laddas långsamt. I själva verket så tar det så här lång tid att ladda stylesheetet, men problemet är att det tar tid innan användaren får se något på skärmen. Skärmen är helt vit enda tills allt laddats klart.

Lösningen är att lägga alla css-kod på toppen av sidan, inuti head-taggen. Det gör att sidan laddas gradvis vilket gör att man slipper den vita sidan och därför upplever användaren att det går snabbare att ladda klart sidan.

### Scripts blockerar nedladdningar
Ett problem är att script ligger på fel plats i koden. En del Javascript-kod ligger inuti Head-taggen vilket gör att sidan laddas in långsammare. Problemet när script ska laddas in är att under tiden som detta sker så kan inget annat laddas in samtidigt. Det gör att sidan visar upp en helt vit sida ända tills scripten har laddats in.

Lösningen på det här problemet är att lägga alla script längst ned i koden (finns undantag). Det gör att allt annat innehåll inte blockeras när scriptet laddas in och visas mycket tidigare än om scriptet skulle laddas in i början av koden. 

### Normalstora JS och CSS-dokument
Problemet är att man inte förminskar sin Javascript och CSS-kod. Prestandan blir sämre ju större filer/dokument som måste laddas in.

Därför kan man använda sig av något som kallas "minification", på svenska beskrivet som "förminska". Det hela går ut på att man tar hjälp av olika program (t.ex. JSMin, utvecklat av Douglas Crockford) som gör koden mindre. Det den egentligen gör är att ta bort onödiga tecken för att förminska storleken på dokumentet. Alla kommentarer tas bort, likaså alla onödiga blanksteg (mellanslag, ny rad och ny flik). I applikationen bör samtliga JS-filer använda sig av JSMin.

Om man använder sig av både JSMin och Gzip kompression, som jag skrev om tidigare i rapporten, så kan man minska en fil oerhört mycket. I exemplet i kurslitteraturen så ges ett exempel där en fil från start är 85kb och efter att man använt både JSMin och Gzip så minskar samma fil till 19kb.



## Egna reflektioner
CDN - inte ha med.

Regel 8 i kurslitteraturen som säger att man bör göra Javascript och CSS extern har jag valt att inte använda mig av. Detta är för att jag inte ser fördelen med det i den här applikationen. En vanlig användare använder sig bara av en sida, som i stort sett aldrig laddas om. Hade däremot sidan laddats om ofta eller att det funnit andra sidor att vandra mellan så hade detta varit en fördel. Externt Javascript- och CSS-kod kan cachas, vilket gör att man slipper läsa in allt om och om igen.

## Referenser
[1] Steve Souders, High Performance Web Sites: Essential Knowledge for Frontend Engineers. Sebastopol: O'Reilly Media, Inc., 2007. http://www.it.iitb.ac.in/frg/wiki/images/4/44/Oreilly.Seve.Suoders.High.Performance.Web.Sites.Sep.2007.pdf

[2] Kalid Azad, "How To Optimize Your Site With GZIP Compression", Better Explained, okänt [Online] Tillgänglig: http://betterexplained.com/articles/how-to-optimize-your-site-with-gzip-compression/. [Hämtad: 30 november, 2015].
