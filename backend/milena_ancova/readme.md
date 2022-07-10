# **Arvustused 04.07.2022**

## **Joonas**
```
Kood töötab nagu nõutud annab õige väljundi
Magentot tunneb.. moodul on tehtud magento moodi.. kasutades uuemaid tehnoloogiaid (db_schema.xml, data patch, webapi endpoindid). andmebaasi päring.
Kui norida tahaks, siis Organization, OrganizationList loomisel oleks võinud kasutada läbi magento enda funktsionaalsuse (factory). mitte php moodi


Silma jäi palju hooletus-, lohakusvigu
    Lohakusvead failide formaatimisel
        osades failides rea algused formaaditud tab-klahviga
        osades 4 tühikut, osades 3 tühikut.

    Funktsioonide nimede formaat ei ole läbivalt samasugune.. Enamasti camelcase.. mis on magento standard, osades snake case, osadel pooleks (Näiteks: getRelation_type). muutujad kõik on snake cases.. magento moodi oleks õige camel cases
    Oli ka koodi mida enam ei kasutatud. $total_pages_sql = "SELECT COUNT(*) FROM table";

    endpoindi json väljundis
        peaks võtme nimetus relation_type asemel olema relationship_type

Kokkuvõttes ütleks et ta on kesktasemega magento arendaja.
```




