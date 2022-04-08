# **Arvustused 08.11.2021**

## **Joonas**
```
ee, selle testtöö nüüd unustame ilusalt ära..Väga algaja  (ta vist ise ka tunnistab, et asi tegelt üldse ei tööta "I have added POST methode for inserting "tree" and "fruit" vlaues via procedure that is currently not working ")
Tehtud tööl väga vähe ühist ärinõudega.   et kuidas andmeid sisestama peab ja kuidas väljastama peab

no see on nii pooleli, algusfaasis, et raske midagi kirjutada..
Nähtu põhjal võib nu sellised soovitused anda
mysql configuratsiooni andmeid oleks võinud olla konfigureeritavad ühest failist.. näiteks teha uus fail config.php ja see includega mõlemasse faili sisse pärida.
get.php failis oleks võinud sarnaselt post.php failile kasutatud olla mysqli prepared statementi. kuna kasutatud pole, siis on võimalik sqlinjection (https://github.com/sqlmapproject/sqlmap programmi abil saab selles ise veenduda).
Mysql protseduuride kohta ei oska kommenteerida, et miks ei tööta.. kuna endal puudub nendega kokkupuude.. üldiselt peaks ülesande lahendamiseks piisama kahest andmebaasitabelist organization (id, name)  ja relations (parenti_id, child_id)
```