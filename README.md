# README - Foode - Projekt Rozgrzewkowy  #

Celem projektu rozgrzewkowego jest spokojne rozpoczęcie współpracy i wdrożenie nowego pracownika w rytm pracy firmy oraz zapoznanie się z narzędziami i współpracownikami.

## Foode - Serwis Kulinarny ##

### Założenia projektu: ###

* prosty serwis przedstawiający przepisy kulinarne
* serwis podzielony na część publiczną (dla wszystkich użytkowników) i część prywatną dostępną po zalogowaniu (dla administratora)
* przepisy:
    - pobierane przepisów z serwisu https://www.themealdb.com/api.php i zapisywanie w bazie danych - synchronizacja wywoływana z linii poleceń (manualnie lub cyklicznie z CRON)
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
* deploy i publikacja na serwerze pod adresem: https://foode-mateusz-w.develop.dev2.mohi.to/
    - hosting MyDevil
    - dane dostępowe do serwera i bazy danych zostaną wysłane na maila

### Czas wykonania: ###
* ok. 5 - 6 dni roboczych lub 30 - 40h (tyle ile się uda zrobić :D)

### Przykłady ###
* W panelu admina można sobie zaimportować po API przepisy np.:
* https://www.themealdb.com/api/json/v1/1/search.php?s=Sushi
* https://www.themealdb.com/api/json/v1/1/search.php?s=Pad See Ew
* https://www.themealdb.com/api/json/v1/1/search.php?s=Burek
* https://www.themealdb.com/api/json/v1/1/search.php?s=Flamiche

### Pobieranie danych z API z CLI #1 ###
* Funkcjonalność pozwala na pobranie wszystkich przepisów, których nie posiadamy, z danej litery alfabetu na raz:

```
php bin/console api:get-api-recipies
```

* po odpaleniu funkcja poprosi o podanie litery, po której zaciągnie przepisy z podsumowaniem o ilości pobrany

### Pobieranie danych z API z CLI #2 ###
* Funkcjonalność pozwala na pobranie wszystkich przepisów, których nie posiadamy poprzez odpalenie skyptu np. w cron:

```
php bin/console app:get-next-letter-api-recipies
```

* crona można odpalić by działał np. co 1 minutę w określonym czasie. Po aktualizacji przepisów jednej litery
* zapisuje w cache kolejną i zaciąga w kolejnym odpaleniu crona kolejne przepisy do końca alfabetu