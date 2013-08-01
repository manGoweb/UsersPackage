
SkeletonPackage
===============

SkeletonPackage je kostra balíčku pro *Skeleton21*

Balíček slouží k verzování a distribuci části funkčnosti webové aplikace včetně presenterů, šablon, rout, migrací atp.


Vytvoření nového balíčku:
--------
- Naklonujte tento repozitář
- Změňte jméno a popis balíčku v souboru `composer.json`
- Změňte namespace třídy `Package` v adresáři `app`.
- Případně upravte další nastavení v metodě `Package::register()`.


Instalace balíčku:
--------
Balíčky se instalují *Composerem* pomocí *SkeletonPackageInstalleru*. Prostě balíček přidejte do závislostí projektu.
V projektu balíček aktivujete zavoláním registrátoru (např. `\PackageNs\Package::register()`) v metodě `AppNamespace\Configurator::onAfter()`


Běžná struktura balíčku:
--------
- Package					(změňte na skutečné jméno balíčku)
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


Rozšiřitelnost:
--------
- Routy: Routy balíčku se registrují až po routách projektu, což zajistí, že je lze v projektu přetížit. (Canonicalizace přetížených rout zatím není vyřešena)
- Presentery: `PresenterFactory` hledá třídu presenteru nejdríve ve jmenném prostoru aplikace, až poté ve jmenném prostoru balíčku (ten se v `PresenterFactory` musí registrovat)
- Šablony: `TemplateFactory` hledá šablony nejdříve v adresáři aplikace, pak teprve v adresáři balíčku
- Komponenty: komponenty lze přetížit jejich nahrazením v `ServiceContaineru`
- Helpery: (zatím nelze)


Migrace:
--------
- Balíček nesmí měnit nebo odebírat sloupce/tabulky/indexy atd., které sám nepřidal
- Obecné pravidlo migrací je, že se nesmí nikdy editovat
- Migrace z balíčku je možné editovat právě jednou po připojení balíčku k projektu, aby se zabránilo případným kolizím
	s migracemi z jiných balíčků či z projektu
- Migraci ale nelze odstranit (installer by ji opětovně přidal). Pokud je třaba migraci vyřadit, je možné ji přepsat prázdným příkazem (např. `SELECT 1;`)
- Instalátor balíčku při jeho aktualizaci již jednou nainstalované migrace nepřepisuje
