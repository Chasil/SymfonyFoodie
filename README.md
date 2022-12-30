# README - Foode - Projekt Rozgrzewkowy  #

Celem projektu rozgrzewkowego jest spokojne rozpoczęcie współpracy i wdrożenie nowego pracownika w rytm pracy firmy oraz zapoznanie się z narzędziami i współpracownikami.

## Foode - Serwis Kulinarny ##

### Założenia projektu: ###

* prosty serwis przedstawiający przepisy kulinarne
* serwis podzielony na część publiczną (dla wszystkich użytkowników) i część prywatną dostępną po zalogowaniu (dla administratora)
* przepisy:
    - pobierane przepisów z serwisu https://www.themealdb.com/api.php - synchronizacja wywoływana z linii poleceń (manualnie lub cyklicznie z CRON)
    - dane przepisu: nazwa, krótki opis, kategoria, lista składników, opis przygotowania, zdjęcie, lista tagów
* część publiczna (szablon w katalogu "Foode - template"):
    - lista przepisów: wszystkie, dla kategorii, dla tagu, dla wyszukiwanej frazy, stronicowanie
    - lista kategorii
    - lista ostatni dodanych przepisów
    - lista popularnych tagów
    - szczegóły przepisu: szczegóły przepisu, lista składników, podobne przepisy (losowe z tej samej kategorii)
* część prywatna:
    - logowanie administratora (e-mail, hasło)
    - tworzenie administratora - manualnie z linii poleceń (command)
    - lista przepisów
    - podgląd i edycja przepisu
    - wyłączenie publikacji przepisu
    - usuwanie przepisu z wszystkimi powiązanymi danymi

### Założenia techniczne: ###
* PHP 8.x - framework Symfony 6.x
* MySql 8.x
* deploy i publikacja na serwerze - hosting MyDevil (dane dostępowe zostaną wysłane na maila)

### Czas wykonania: ###
* ok. 5 - 6 dni roboczych lub 30 - 40h (tyle ile się uda zrobić :D)
