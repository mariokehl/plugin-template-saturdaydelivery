# Produktinformationen

Mit diesem Plugin ermöglichst du es deinen Kund:innen, einen Wunschtermin für die Lieferung aus bis zu sieben Samstagen auszuwählen.

Für diesen Service kannst du einen Zuschlag definieren, welcher bei Auswahl der Wochenendzustellung automatisch auf die Versandkosten addiert wird. Die Benutzereingaben werden anschließend als Auftragseigenschaft angelegt und können dort von nachfolgenden Versand-Plugins ausgelesen werden (eine Liste der aktuell kompatiblen Versand-Plugins findest du am Ende dieses Dokuments).

## Installationsanleitung

Für die Auswahl der Samstagszustellung musst du die entsprechenden Werte in der Plugin-Konfiguration hinterlegen.

1. Öffne das Menü **Plugins » Plugin-Set-Übersicht**.
2. Wähle das gewünschte Plugin-Set aus.
3. Klicke auf **Termin-/Samstagszustellung buchen**.<br>→ Eine neue Ansicht öffnet sich.
4. Wähle den Bereich **Allgemein** aus der Liste.
5. Trage eine kommaseparierte Liste von IDs bei _Erlaubte Versandprofile_ und den _Zuschlag (Brutto)_ ein.
7. **Speichere** die Einstellungen.

<div class="alert alert-info" role="alert">
    Wenn du dem Eingabefeld für erlaubte Versandprofile nichts einträgst, dann wird die Samstagszustellung bei allen Versandoptionen angezeigt.
</div>

Danach die Container-Verknüpfungen anlegen, so dass der Bereich Samstagszustellung auch im Frontend deines plentyShop angezeigt wird:

1. Wechsel zum Untermenü **Container-Verknüpfungen**.
2. Verknüpfe den Inhalt **Saturday Delivery CSS** mit dem Container **Ceres::Template.Style**
3. Verknüpfe den Inhalt **Display Date Picker for Saturday Delivery** mit dem Container **Ceres::Checkout.AfterShippingProfileList** zur Anzeige in der Kasse (_Checkout: After shipping method_)

### Weitere Konfigurationsoptionen

| Einstellung                        | Beschreibung |
|------------------------------------|---------------|
| Vorlaufzeit in Tagen | Zeitspanne zwischen Versanddatum und Lieferung. Beispiel: Bei einer Vorlaufzeit von 2 Tagen ist am Freitag erst der Samstag der Folgewoche auswählbar. Am Mittwoch wäre hingegen der kommende Samstag möglich. |
| Anzahl Auswahloptionen | Bestimme, wieviele Samstag du vorausschauend anbieten möchtest. Hinweis: die maximale Anzahl ist auf 7 begrenzt. |

Tabelle 1: Weitere Konfigurationsoptionen

### Kompatible Versand-Plugins

Folgende Plugins sind nach derzeitigem Kenntnisstand kompatibel und werten den Termin bei Übergabe an den Versand-Dienstleister aus:

* [GO! Express](https://marketplace.plentymarkets.com/goexpress_55126) (ab Version 1.0.9)
* [eCourier](https://marketplace.plentymarkets.com/bambooecourier_55144), z.B. für Versand mit DER KURIER (ab Version 1.0.4)

Externe Entwickler:innen können mich gerne bezüglich technischer Spezifikation kontaktieren, so dass diese Liste erweitert werden kann.


<sub><sup>Jeder einzelne Kauf hilft bei der ständigen Weiterentwicklung und der Umsetzung von Userwünschen. Vielen Dank!</sup></sub>
