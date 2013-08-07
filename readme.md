
SkeletonPackage
===============

SkeletonPackage je kostra balíčku pro *Skeleton21*.

Balíček slouží k verzování a distribuci části funkčnosti webové aplikace včetně presenterů, šablon, rout, migrací atp.

Od běžného composerového balíčku se liší v tom, že jednotlivé části jsou při instalaci rozkopírovány na příslušná místa
skeletonové aplikace.

Pokud to není vyloženě nutné, nevytvářejte balíček pro Skeleton21, ale běžný composerový balíček.


Vytvoření nového balíčku:
-------------------------
- Naklonujte tento repozitář
- Změňte jméno a popis balíčku v souboru `composer.json`
- Změňte namespace a výchozí parametry routeru v `app\Router.php`
- Změňte namespace třídy `Package` a namespace routeru v souboru `app\Package.php`
- Případně upravte další nastavení v metodě `Package::register()` (registrace služeb, repozitářů atp.)
- Upravte toto readme, ale uveďte odkaz na původní verzi (https://github.com/Clevis/SkeletonPackage/blob/master/readme.md)


Instalace balíčku:
------------------
Balíčky se instalují *Composerem* pomocí *SkeletonPackageInstalleru*. Ten se stará o zkopírování všech souborů na správná místa.

Balíček přidejte do závislostí v souboru `composer.json` v projektu.

V projektu balíček aktivujete zavoláním registrátoru v metodě `onInitModules()` v `Configuratoru`:

	public function onInitModules()
	{
		MyPackageNamespace\Package::register($this);
	}


Struktura balíčku:
------------------

	- app 					(stejné jako je běžná struktura skeletonní aplikace)
		- components
		- presenters
		- templates
		- *SomeModule*		(modul)
			- components
        	- presenters
        	- templates
	- migrations
		- data   			(obsahuje testovací data pro integrační a seleniové testy)
		- struct 			(obsahuje struktury a *provozní data*)
	- tests
		- cases
			- Selenium
			- Unit
	- www					(assets)


Rozšiřitelnost:
---------------
- Routy:
	- routy balíčku se registrují až po routách projektu
	- to zajistí, že je lze v projektu přetížit
	- (canonicalizace přetížených rout zatím není vyřešena)
- Presentery:
	- `PresenterFactory` hledá třídu presenteru nejdríve ve jmenném prostoru aplikace, až poté ve jmenném prostoru balíčku (ten se v `PresenterFactory` musí registrovat)
	- presentery v balíčku musí dědit od třídy `Clevis\Skeleton\BasePresenter`
	- presentery by neměly rozšiřovat presenter z jiného balíčku (raději použijte kompozici nebo traity)
- Šablony: `TemplateFactory` hledá šablony nejdříve v adresáři aplikace, pak teprve v adresáři balíčku
	- aplikační šablona tedy může rozšiřovat šablonu z balíčku, aniž by bylo třeba cokoliv nastavovat
	- pro zjednodušení jsou šablony z balíčku zkopírovány do adresáře `/app[/Module]/templates/{Presenter}/package/`
- Komponenty:
	- služby definované aplikací mají přednost před službami v balíčku
	- službu z balíčku tedy lze přebít uvedením služby v konfiguráku aplikace
- Helpery:
	- (zatím není nijak vyřešeno)


Testy:
------
- Testy v balíčku by měly dědit od `Tests\Unit\TestCase` nebo `Test\Selenium\SeleniumTestCase` (nejspíše se změní)
- Prozatím je počítáno pouze se spouštěním testů z prostředí *Skeletonu*


Migrace:
--------
- Balíček nesmí měnit nebo odebírat sloupce/tabulky/indexy atd., které sám nepřidal
- Obecné pravidlo migrací je, že migrace se nesmí nikdy editovat
- Migrace z balíčku je možné editovat právě jednou po připojení balíčku k projektu, aby se zabránilo případným kolizím
	s migracemi z jiných balíčků či z projektu (jméno souboru se ale nesmí měnit)
- Instalátor balíčku při jeho aktualizaci již jednou nainstalované migrace nepřepisuje
- Migraci ale nelze odstranit (installer by ji opětovně přidal)
- Pokud je třeba migraci vyřadit, je možné ji přepsat prázdným příkazem (např. `SELECT 1;`)
- Při odinstalování balíčku zůstávají jeho migrace na místě. Migrace jsou nevratné a odebrat je nelze. Pokud je to nutné,
	je třeba stav databáze změnit reverzní migrací

