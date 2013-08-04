
SkeletonPackage
===============

SkeletonPackage je kostra balíčku pro *Skeleton21*.

Balíček slouží k verzování a distribuci části funkčnosti webové aplikace včetně presenterů, šablon, rout, migrací atp.

Od běžného composerového balíčku se liší v tom, že jednotlivé části jsou při instalaci rozkopírovány na příslušná místa
skeletonové aplikace.

Pokud to není vyloženě nutné, nevytvářejte balíček pro Skeleton21, ale normální composerový balíček.


Vytvoření nového balíčku:
--------
- Naklonujte tento repozitář
- Změňte jméno a popis balíčku v souboru `composer.json`
- Změňte namespace a výchozí parametry routeru v `app\Router.php`.
- Změňte namespace třídy `Package` a namespace routeru v adresáři `app\Package.php`.
- Případně upravte další nastavení v metodě `Package::register()`.


Instalace balíčku:
--------
Balíčky se instalují *Composerem* pomocí *SkeletonPackageInstalleru*. Prostě balíček přidejte do závislostí projektu.
V projektu balíček aktivujete zavoláním registrátoru (např. `\PackageNs\Package::register()`) v metodě `AppNamespace\Configurator::onAfter()`


Běžná struktura balíčku:
--------
	- app 					(podobné jako je běžná struktura skeletonní aplikace)
		- components
		- presenters
		- templates
		- *SomeModule*
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
--------
- Routy: Routy balíčku se registrují až po routách projektu, což zajistí, že je lze v projektu přetížit. (Canonicalizace přetížených rout zatím není vyřešena)
- Presentery: `PresenterFactory` hledá třídu presenteru nejdríve ve jmenném prostoru aplikace, až poté ve jmenném prostoru balíčku (ten se v `PresenterFactory` musí registrovat)
- Šablony: `TemplateFactory` hledá šablony nejdříve v adresáři aplikace, pak teprve v adresáři balíčku
- Komponenty: komponenty lze přetížit jejich nahrazením v `ServiceContaineru`
- Helpery: (zatím nelze)


Testy:
------
- testy jsou psány pro PHPUnit a měly by rozšiřovat `Tests\Unit\TestCase` nebo `Tests\Selenium\SeleniumTestCase`
- prozatím je počítáno se spouštěním testů z prostředí Skeletonu


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

