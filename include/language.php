<?php

# The following table is constructed by www/mysql/data/language/retrieve.sql
# It is hard coded for performance reasons
# It is a pain that php wants field names to be quoted
# Also we don't really want to have to open up the DB to get this info

function db_languages()
{
  $ret = json_decode(
'[
{"c":"eng","n":"English","l":1},
{"c":"fre","n":"French","l":1},
{"c":"ger","n":"German","l":1},
{"c":"spa","n":"Spanish; Castilian","l":1},
{"c":"afr","n":"Afrikaans","l":2},
{"c":"ara","n":"Arabic","l":2},
{"c":"baq","n":"Basque","l":2},
{"c":"ben","n":"Bengali","l":2},
{"c":"bos","n":"Bosnian","l":2},
{"c":"bul","n":"Bulgarian","l":2},
{"c":"cat","n":"Catalan; Valencian","l":2},
{"c":"chi","n":"Chinese","l":2},
{"c":"chi-cn","n":"Chinese Simplified","l":2},
{"c":"hrv","n":"Croatian","l":2},
{"c":"cze","n":"Czech","l":2},
{"c":"dan","n":"Danish","l":2},
{"c":"dut","n":"Dutch; Flemish","l":2},
{"c":"eng-au","n":"English (Australia)","l":2},
{"c":"eng-ca","n":"English (Canada)","l":2},
{"c":"eng-gb","n":"English (United Kingdom)","l":2},
{"c":"epo","n":"Esperanto","l":2},
{"c":"est","n":"Estonian","l":2},
{"c":"fao","n":"Faroese","l":2},
{"c":"fin","n":"Finnish","l":2},
{"c":"fre-ca","n":"French (Canada)","l":2},
{"c":"glg","n":"Galician","l":2},
{"c":"geo","n":"Georgian","l":2},
{"c":"gre","n":"Greek, Modern (1453-)","l":2},
{"c":"guj","n":"Gujarati","l":2},
{"c":"heb","n":"Hebrew","l":2},
{"c":"hin","n":"Hindi","l":2},
{"c":"hun","n":"Hungarian","l":2},
{"c":"ice","n":"Icelandic","l":2},
{"c":"ita","n":"Italian","l":2},
{"c":"jpn","n":"Japanese","l":2},
{"c":"khm","n":"Khmer (central)","l":2},
{"c":"kor","n":"Korean","l":2},
{"c":"lav","n":"Latvian","l":2},
{"c":"lit","n":"Lithuanian","l":2},
{"c":"may","n":"Malay","l":2},
{"c":"mon","n":"Mongolian","l":2},
{"c":"nor","n":"Norwegian","l":2},
{"c":"nob","n":"Norwegian BokmÃ¥","l":2},
{"c":"per","n":"Persian","l":2},
{"c":"pol","n":"Polish","l":2},
{"c":"por","n":"Portuguese","l":2},
{"c":"por-br","n":"Portuguese (Brazil)","l":2},
{"c":"rum","n":"Romanian; Moldavian; Moldovan","l":2},
{"c":"rus","n":"Russian","l":2},
{"c":"srp","n":"Serbian","l":2},
{"c":"srp-latn","n":"Serbian (Latin)","l":2},
{"c":"slo","n":"Slovak","l":2},
{"c":"slv","n":"Slovenian","l":2},
{"c":"swe","n":"Swedish","l":2},
{"c":"tha","n":"Thai","l":2},
{"c":"tur","n":"Turkish","l":2},
{"c":"ukr","n":"Ukrainian","l":2},
{"c":"vie","n":"Vietnamese","l":2},
{"c":"wel","n":"Welsh","l":2},
{"c":"abk","n":"Abkhazian","l":3},
{"c":"aar","n":"Afar","l":3},
{"c":"aka","n":"Akan","l":3},
{"c":"alb","n":"Albanian","l":3},
{"c":"amh","n":"Amharic","l":3},
{"c":"arg","n":"Aragonese","l":3},
{"c":"arm","n":"Armenian","l":3},
{"c":"asm","n":"Assamese","l":3},
{"c":"ava","n":"Avaric","l":3},
{"c":"ave","n":"Avestan","l":3},
{"c":"aym","n":"Aymara","l":3},
{"c":"aze","n":"Azerbaijani","l":3},
{"c":"bam","n":"Bambara","l":3},
{"c":"bak","n":"Bashkir","l":3},
{"c":"bel","n":"Belarusian","l":3},
{"c":"bih","n":"Bihari languages","l":3},
{"c":"bis","n":"Bislama","l":3},
{"c":"bre","n":"Breton","l":3},
{"c":"bur","n":"Burmese","l":3},
{"c":"cha","n":"Chamorro","l":3},
{"c":"che","n":"Chechen","l":3},
{"c":"nya","n":"Chichewa; Chewa; Nyanja","l":3},
{"c":"chv","n":"Chuvash","l":3},
{"c":"cor","n":"Cornish","l":3},
{"c":"cos","n":"Corsican","l":3},
{"c":"cre","n":"Cree","l":3},
{"c":"div","n":"Divehi; Dhivehi; Maldivian","l":3},
{"c":"dzo","n":"Dzongkha","l":3},
{"c":"ewe","n":"Ewe","l":3},
{"c":"fij","n":"Fijian","l":3},
{"c":"ful","n":"Fulah","l":3},
{"c":"gla","n":"Gaelic; Scottish Gaelic","l":3},
{"c":"lug","n":"Ganda","l":3},
{"c":"grn","n":"Guarani","l":3},
{"c":"hat","n":"Haitian; Haitian Creole","l":3},
{"c":"hau","n":"Hausa","l":3},
{"c":"her","n":"Herero","l":3},
{"c":"hmo","n":"Hiri Motu","l":3},
{"c":"ido","n":"Ido","l":3},
{"c":"ibo","n":"Igbo","l":3},
{"c":"ind","n":"Indonesian","l":3},
{"c":"ina","n":"Interlingua","l":3},
{"c":"ile","n":"Interlingue; Occidental","l":3},
{"c":"iku","n":"Inuktitut","l":3},
{"c":"ipk","n":"Inupiaq","l":3},
{"c":"gle","n":"Irish","l":3},
{"c":"jav","n":"Javanese","l":3},
{"c":"kal","n":"Kalaallisut; Greenlandic","l":3},
{"c":"kan","n":"Kannada","l":3},
{"c":"kau","n":"Kanuri","l":3},
{"c":"kas","n":"Kashmiri","l":3},
{"c":"kaz","n":"Kazakh","l":3},
{"c":"kik","n":"Kikuyu; Gikuyu","l":3},
{"c":"kin","n":"Kinyarwanda","l":3},
{"c":"kir","n":"Kirghiz; Kyrgyz","l":3},
{"c":"kom","n":"Komi","l":3},
{"c":"kon","n":"Kongo","l":3},
{"c":"kua","n":"Kuanyama; Kwanyama","l":3},
{"c":"kur","n":"Kurdish","l":3},
{"c":"lao","n":"Lao","l":3},
{"c":"lat","n":"Latin","l":3},
{"c":"lim","n":"Limburgan; Limburger; Limburgish","l":3},
{"c":"lin","n":"Lingala","l":3},
{"c":"lub","n":"Luba-Katanga","l":3},
{"c":"ltz","n":"Luxembourgish; Letzeburgesch","l":3},
{"c":"mac","n":"Macedonian","l":3},
{"c":"mlg","n":"Malagasy","l":3},
{"c":"mal","n":"Malayalam","l":3},
{"c":"mlt","n":"Maltese","l":3},
{"c":"glv","n":"Manx","l":3},
{"c":"mao","n":"Maori","l":3},
{"c":"mar","n":"Marathi","l":3},
{"c":"mah","n":"Marshallese","l":3},
{"c":"nau","n":"Nauru","l":3},
{"c":"nav","n":"Navajo; Navaho","l":3},
{"c":"nde","n":"Ndebele, North; North Ndebele","l":3},
{"c":"nbl","n":"Ndebele, South; South Ndebele","l":3},
{"c":"ndo","n":"Ndonga","l":3},
{"c":"nep","n":"Nepali","l":3},
{"c":"sme","n":"Northern Sami","l":3},
{"c":"nno","n":"Norwegian Nynorsk; Nynorsk, Norwegian","l":3},
{"c":"oci","n":"Occitan (post 1500)","l":3},
{"c":"oji","n":"Ojibwa","l":3},
{"c":"chu","n":"Old Church Slavic","l":3},
{"c":"ori","n":"Oriya","l":3},
{"c":"orm","n":"Oromo","l":3},
{"c":"oss","n":"Ossetian; Ossetic","l":3},
{"c":"pli","n":"Pali","l":3},
{"c":"pan","n":"Panjabi; Punjabi","l":3},
{"c":"pus","n":"Pushto; Pashto","l":3},
{"c":"que","n":"Quechua","l":3},
{"c":"roh","n":"Romansh","l":3},
{"c":"run","n":"Rundi","l":3},
{"c":"smo","n":"Samoan","l":3},
{"c":"sag","n":"Sango","l":3},
{"c":"san","n":"Sanskrit","l":3},
{"c":"srd","n":"Sardinian","l":3},
{"c":"sna","n":"Shona","l":3},
{"c":"iii","n":"Sichuan Yi; Nuosu","l":3},
{"c":"snd","n":"Sindhi","l":3},
{"c":"sin","n":"Sinhala; Sinhalese","l":3},
{"c":"som","n":"Somali","l":3},
{"c":"sot","n":"Sotho, Southern","l":3},
{"c":"sun","n":"Sundanese","l":3},
{"c":"swa","n":"Swahili","l":3},
{"c":"ssw","n":"Swati","l":3},
{"c":"tgl","n":"Tagalog","l":3},
{"c":"tah","n":"Tahitian","l":3},
{"c":"tgk","n":"Tajik","l":3},
{"c":"tam","n":"Tamil","l":3},
{"c":"tat","n":"Tatar","l":3},
{"c":"tel","n":"Telugu","l":3},
{"c":"tib","n":"Tibetan","l":3},
{"c":"tir","n":"Tigrinya","l":3},
{"c":"ton","n":"Tonga (Tonga Islands)","l":3},
{"c":"tso","n":"Tsonga","l":3},
{"c":"tsn","n":"Tswana","l":3},
{"c":"tuk","n":"Turkmen","l":3},
{"c":"twi","n":"Twi","l":3},
{"c":"uig","n":"Uighur; Uyghur","l":3},
{"c":"urd","n":"Urdu","l":3},
{"c":"uzb","n":"Uzbek","l":3},
{"c":"ven","n":"Venda","l":3},
{"c":"vol","n":"VolapÃ¼v","l":3},
{"c":"wln","n":"Walloon","l":3},
{"c":"fry","n":"Western Frisian","l":3},
{"c":"wol","n":"Wolof","l":3},
{"c":"xho","n":"Xhosa","l":3},
{"c":"yid","n":"Yiddish","l":3},
{"c":"yor","n":"Yoruba","l":3},
{"c":"zha","n":"Zhuang; Chuang","l":3},
{"c":"zul","n":"Zulu","l":3},
{"c":"ace","n":"Achinese","l":4},
{"c":"ach","n":"Acoli","l":4},
{"c":"ada","n":"Adangme","l":4},
{"c":"ady","n":"Adyghe; Adygei","l":4},
{"c":"afh","n":"Afrihili","l":4},
{"c":"afa","n":"Afro-Asiatic languages","l":4},
{"c":"ain","n":"Ainu","l":4},
{"c":"akk","n":"Akkadian","l":4},
{"c":"ale","n":"Aleut","l":4},
{"c":"alg","n":"Algonquian languages","l":4},
{"c":"tut","n":"Altaic languages","l":4},
{"c":"anp","n":"Angika","l":4},
{"c":"apa","n":"Apache languages","l":4},
{"c":"arp","n":"Arapaho","l":4},
{"c":"arw","n":"Arawak","l":4},
{"c":"rup","n":"Aromanian; Arumanian; Macedo-Romanian","l":4},
{"c":"art","n":"Artificial languages","l":4},
{"c":"ast","n":"Asturian; Bable; Leonese; Asturleonese","l":4},
{"c":"ath","n":"Athapascan languages","l":4},
{"c":"aus","n":"Australian languages","l":4},
{"c":"map","n":"Austronesian languages","l":4},
{"c":"awa","n":"Awadhi","l":4},
{"c":"ban","n":"Balinese","l":4},
{"c":"bat","n":"Baltic languages","l":4},
{"c":"bal","n":"Baluchi","l":4},
{"c":"bai","n":"Bamileke languages","l":4},
{"c":"bad","n":"Banda languages","l":4},
{"c":"bnt","n":"Bantu languages","l":4},
{"c":"bas","n":"Basa","l":4},
{"c":"btk","n":"Batak languages","l":4},
{"c":"bej","n":"Beja; Bedawiyet","l":4},
{"c":"bem","n":"Bemba","l":4},
{"c":"ber","n":"Berber languages","l":4},
{"c":"bho","n":"Bhojpuri","l":4},
{"c":"bik","n":"Bikol","l":4},
{"c":"bin","n":"Bini; Edo","l":4},
{"c":"byn","n":"Blin; Bilin","l":4},
{"c":"zbl","n":"Blissymbols; Blissymbolics; Bliss","l":4},
{"c":"bra","n":"Braj","l":4},
{"c":"bug","n":"Buginese","l":4},
{"c":"bua","n":"Buriat","l":4},
{"c":"cad","n":"Caddo","l":4},
{"c":"cau","n":"Caucasian languages","l":4},
{"c":"ceb","n":"Cebuano","l":4},
{"c":"cel","n":"Celtic languages","l":4},
{"c":"cai","n":"Central American Indian languages","l":4},
{"c":"chg","n":"Chagatai","l":4},
{"c":"cmc","n":"Chamic languages","l":4},
{"c":"chr","n":"Cherokee","l":4},
{"c":"chy","n":"Cheyenne","l":4},
{"c":"chb","n":"Chibcha","l":4},
{"c":"chn","n":"Chinook jargon","l":4},
{"c":"chp","n":"Chipewyan; Dene Suline","l":4},
{"c":"cho","n":"Choctaw","l":4},
{"c":"chk","n":"Chuukese","l":4},
{"c":"syc","n":"Classical Syriac","l":4},
{"c":"cop","n":"Coptic","l":4},
{"c":"mus","n":"Creek","l":4},
{"c":"crp","n":"Creoles and pidgins","l":4},
{"c":"cpe","n":"Creoles and pidgins, English based","l":4},
{"c":"cpf","n":"Creoles and pidgins, French-based","l":4},
{"c":"cpp","n":"Creoles and pidgins, Portuguese-based","l":4},
{"c":"crh","n":"Crimean Tatar; Crimean Turkish","l":4},
{"c":"cus","n":"Cushitic languages","l":4},
{"c":"dak","n":"Dakota","l":4},
{"c":"dar","n":"Dargwa","l":4},
{"c":"del","n":"Delaware","l":4},
{"c":"din","n":"Dinka","l":4},
{"c":"doi","n":"Dogri","l":4},
{"c":"dgr","n":"Dogrib","l":4},
{"c":"dra","n":"Dravidian languages","l":4},
{"c":"dua","n":"Duala","l":4},
{"c":"dum","n":"Dutch, Middle (ca.1050-1350)","l":4},
{"c":"dyu","n":"Dyula","l":4},
{"c":"frs","n":"Eastern Frisian","l":4},
{"c":"efi","n":"Efik","l":4},
{"c":"egy","n":"Egyptian (Ancient)","l":4},
{"c":"eka","n":"Ekajuk","l":4},
{"c":"elx","n":"Elamite","l":4},
{"c":"enm","n":"English, Middle (1100-1500)","l":4},
{"c":"ang","n":"English, Old (ca.450-1100)","l":4},
{"c":"myv","n":"Erzya","l":4},
{"c":"ewo","n":"Ewondo","l":4},
{"c":"fan","n":"Fang","l":4},
{"c":"fat","n":"Fanti","l":4},
{"c":"fil","n":"Filipino; Pilipino","l":4},
{"c":"fiu","n":"Finno-Ugrian languages","l":4},
{"c":"fon","n":"Fon","l":4},
{"c":"frm","n":"French, Middle (ca.1400-1600)","l":4},
{"c":"fro","n":"French, Old (842-ca.1400)","l":4},
{"c":"fur","n":"Friulian","l":4},
{"c":"gaa","n":"Ga","l":4},
{"c":"car","n":"Galibi Carib","l":4},
{"c":"gay","n":"Gayo","l":4},
{"c":"gba","n":"Gbaya","l":4},
{"c":"gez","n":"Geez","l":4},
{"c":"nds","n":"German (low); Saxon (low)","l":4},
{"c":"gmh","n":"German, Middle High (ca.1050-1500)","l":4},
{"c":"goh","n":"German, Old High (ca.750-1050)","l":4},
{"c":"gem","n":"Germanic languages","l":4},
{"c":"gil","n":"Gilbertese","l":4},
{"c":"gon","n":"Gondi","l":4},
{"c":"gor","n":"Gorontalo","l":4},
{"c":"got","n":"Gothic","l":4},
{"c":"grb","n":"Grebo","l":4},
{"c":"grc","n":"Greek, Ancient (to 1453)","l":4},
{"c":"gwi","n":"Gwich\'in","l":4},
{"c":"hai","n":"Haida","l":4},
{"c":"haw","n":"Hawaiian","l":4},
{"c":"hil","n":"Hiligaynon","l":4},
{"c":"him","n":"Himachali; Western Pahari","l":4},
{"c":"hit","n":"Hittite","l":4},
{"c":"hmn","n":"Hmong; Mong","l":4},
{"c":"hup","n":"Hupa","l":4},
{"c":"iba","n":"Iban","l":4},
{"c":"ijo","n":"Ijo languages","l":4},
{"c":"ilo","n":"Iloko","l":4},
{"c":"smn","n":"Inari Sami","l":4},
{"c":"inc","n":"Indic languages","l":4},
{"c":"ine","n":"Indo-European languages","l":4},
{"c":"inh","n":"Ingush","l":4},
{"c":"ira","n":"Iranian languages","l":4},
{"c":"mga","n":"Irish, Middle (900-1200)","l":4},
{"c":"sga","n":"Irish, Old (to 900)","l":4},
{"c":"iro","n":"Iroquoian languages","l":4},
{"c":"jrb","n":"Judeo-Arabic","l":4},
{"c":"jpr","n":"Judeo-Persian","l":4},
{"c":"kbd","n":"Kabardian","l":4},
{"c":"kab","n":"Kabyle","l":4},
{"c":"kac","n":"Kachin; Jingpho","l":4},
{"c":"xal","n":"Kalmyk; Oirat","l":4},
{"c":"kam","n":"Kamba","l":4},
{"c":"kaa","n":"Kara-Kalpak","l":4},
{"c":"krc","n":"Karachay-Balkar","l":4},
{"c":"krl","n":"Karelian","l":4},
{"c":"kar","n":"Karen languages","l":4},
{"c":"csb","n":"Kashubian","l":4},
{"c":"kaw","n":"Kawi","l":4},
{"c":"kha","n":"Khasi","l":4},
{"c":"khi","n":"Khoisan languages","l":4},
{"c":"kho","n":"Khotanese; Sakan","l":4},
{"c":"kmb","n":"Kimbundu","l":4},
{"c":"tlh","n":"Klingon; tlhIngan-Hol","l":4},
{"c":"kok","n":"Konkani","l":4},
{"c":"kos","n":"Kosraean","l":4},
{"c":"kpe","n":"Kpelle","l":4},
{"c":"kro","n":"Kru languages","l":4},
{"c":"kum","n":"Kumyk","l":4},
{"c":"kru","n":"Kurukh","l":4},
{"c":"kut","n":"Kutenai","l":4},
{"c":"lad","n":"Ladino","l":4},
{"c":"lah","n":"Lahnda","l":4},
{"c":"lam","n":"Lamba","l":4},
{"c":"day","n":"Land Dayak languages","l":4},
{"c":"lez","n":"Lezghian","l":4},
{"c":"jbo","n":"Lojban","l":4},
{"c":"dsb","n":"Lower Sorbian","l":4},
{"c":"loz","n":"Lozi","l":4},
{"c":"lua","n":"Luba-Lulua","l":4},
{"c":"lui","n":"Luiseno","l":4},
{"c":"smj","n":"Lule Sami","l":4},
{"c":"lun","n":"Lunda","l":4},
{"c":"luo","n":"Luo (Kenya and Tanzania)","l":4},
{"c":"lus","n":"Lushai","l":4},
{"c":"mad","n":"Madurese","l":4},
{"c":"mag","n":"Magahi","l":4},
{"c":"mai","n":"Maithili","l":4},
{"c":"mak","n":"Makasar","l":4},
{"c":"mnc","n":"Manchu","l":4},
{"c":"mdr","n":"Mandar","l":4},
{"c":"man","n":"Mandingo","l":4},
{"c":"mni","n":"Manipuri","l":4},
{"c":"mno","n":"Manobo languages","l":4},
{"c":"arn","n":"Mapudungun; Mapuche","l":4},
{"c":"chm","n":"Mari","l":4},
{"c":"mwr","n":"Marwari","l":4},
{"c":"mas","n":"Masai","l":4},
{"c":"myn","n":"Mayan languages","l":4},
{"c":"men","n":"Mende","l":4},
{"c":"mic","n":"Mi\'kmaq; Micmac","l":4},
{"c":"min","n":"Minangkabau","l":4},
{"c":"mwl","n":"Mirandese","l":4},
{"c":"moh","n":"Mohawk","l":4},
{"c":"mdf","n":"Moksha","l":4},
{"c":"mkh","n":"Mon-Khmer languages","l":4},
{"c":"lol","n":"Mongo","l":4},
{"c":"mos","n":"Mossi","l":4},
{"c":"mul","n":"Multiple languages","l":4},
{"c":"mun","n":"Munda languages","l":4},
{"c":"nqo","n":"N\'Ko","l":4},
{"c":"nah","n":"Nahuatl languages","l":4},
{"c":"nap","n":"Neapolitan","l":4},
{"c":"nwc","n":"Nepal Bhasa (classical)","l":4},
{"c":"new","n":"Nepal Bhasa; Newari","l":4},
{"c":"nia","n":"Nias","l":4},
{"c":"nic","n":"Niger-Kordofanian languages","l":4},
{"c":"ssa","n":"Nilo-Saharan languages","l":4},
{"c":"niu","n":"Niuean","l":4},
{"c":"zxx","n":"No linguistic content; Not applicable","l":4},
{"c":"nog","n":"Nogai","l":4},
{"c":"non","n":"Norse, Old","l":4},
{"c":"nai","n":"North American Indian languages","l":4},
{"c":"frr","n":"Northern Frisian","l":4},
{"c":"nub","n":"Nubian languages","l":4},
{"c":"nym","n":"Nyamwezi","l":4},
{"c":"nyn","n":"Nyankole","l":4},
{"c":"nyo","n":"Nyoro","l":4},
{"c":"nzi","n":"Nzima","l":4},
{"c":"arc","n":"Official Aramaic (700-300 BCE)","l":4},
{"c":"osa","n":"Osage","l":4},
{"c":"oto","n":"Otomian languages","l":4},
{"c":"pal","n":"Pahlavi","l":4},
{"c":"pau","n":"Palauan","l":4},
{"c":"pam","n":"Pampanga; Kapampangan","l":4},
{"c":"pag","n":"Pangasinan","l":4},
{"c":"pap","n":"Papiamento","l":4},
{"c":"paa","n":"Papuan languages","l":4},
{"c":"nso","n":"Pedi; Sepedi; Northern Sotho","l":4},
{"c":"peo","n":"Persian, Old (ca.600-400 B.C.)","l":4},
{"c":"phi","n":"Philippine languages","l":4},
{"c":"phn","n":"Phoenician","l":4},
{"c":"pon","n":"Pohnpeian","l":4},
{"c":"pra","n":"Prakrit languages","l":4},
{"c":"pro","n":"ProvenÃ§, Old (to 1500);Occitan, Old (to 1500)","l":4},
{"c":"raj","n":"Rajasthani","l":4},
{"c":"rap","n":"Rapanui","l":4},
{"c":"rar","n":"Rarotongan; Cook Islands Maori","l":4},
{"c":"qaa-qtz","n":"Reserved for local use","l":4},
{"c":"roa","n":"Romance languages","l":4},
{"c":"rom","n":"Romany","l":4},
{"c":"sal","n":"Salishan languages","l":4},
{"c":"sam","n":"Samaritan Aramaic","l":4},
{"c":"smi","n":"Sami languages","l":4},
{"c":"sad","n":"Sandawe","l":4},
{"c":"sat","n":"Santali","l":4},
{"c":"sas","n":"Sasak","l":4},
{"c":"sco","n":"Scots","l":4},
{"c":"sel","n":"Selkup","l":4},
{"c":"sem","n":"Semitic languages","l":4},
{"c":"srr","n":"Serer","l":4},
{"c":"shn","n":"Shan","l":4},
{"c":"scn","n":"Sicilian","l":4},
{"c":"sid","n":"Sidamo","l":4},
{"c":"sgn","n":"Sign Languages","l":4},
{"c":"bla","n":"Siksika","l":4},
{"c":"sit","n":"Sino-Tibetan languages","l":4},
{"c":"sio","n":"Siouan languages","l":4},
{"c":"sms","n":"Skolt Sami","l":4},
{"c":"den","n":"Slave (Athapascan)","l":4},
{"c":"sla","n":"Slavic languages","l":4},
{"c":"sog","n":"Sogdian","l":4},
{"c":"son","n":"Songhai languages","l":4},
{"c":"snk","n":"Soninke","l":4},
{"c":"wen","n":"Sorbian languages","l":4},
{"c":"sai","n":"South American Indian languages","l":4},
{"c":"alt","n":"Southern Altai","l":4},
{"c":"sma","n":"Southern Sami","l":4},
{"c":"srn","n":"Sranan Tongo","l":4},
{"c":"suk","n":"Sukuma","l":4},
{"c":"sux","n":"Sumerian","l":4},
{"c":"sus","n":"Susu","l":4},
{"c":"gsw","n":"Swiss German; Alemannic; Alsatian","l":4},
{"c":"syr","n":"Syriac","l":4},
{"c":"tai","n":"Tai languages","l":4},
{"c":"tmh","n":"Tamashek","l":4},
{"c":"ter","n":"Tereno","l":4},
{"c":"tet","n":"Tetum","l":4},
{"c":"tig","n":"Tigre","l":4},
{"c":"tem","n":"Timne","l":4},
{"c":"tiv","n":"Tiv","l":4},
{"c":"tli","n":"Tlingit","l":4},
{"c":"tpi","n":"Tok Pisin","l":4},
{"c":"tkl","n":"Tokelau","l":4},
{"c":"tog","n":"Tonga (Nyasa)","l":4},
{"c":"tsi","n":"Tsimshian","l":4},
{"c":"tum","n":"Tumbuka","l":4},
{"c":"tup","n":"Tupi languages","l":4},
{"c":"ota","n":"Turkish, Ottoman (1500-1928)","l":4},
{"c":"tvl","n":"Tuvalu","l":4},
{"c":"tyv","n":"Tuvinian","l":4},
{"c":"udm","n":"Udmurt","l":4},
{"c":"uga","n":"Ugaritic","l":4},
{"c":"umb","n":"Umbundu","l":4},
{"c":"mis","n":"Uncoded languages","l":4},
{"c":"und","n":"Undetermined","l":4},
{"c":"hsb","n":"Upper Sorbian","l":4},
{"c":"vai","n":"Vai","l":4},
{"c":"vot","n":"Votic","l":4},
{"c":"wak","n":"Wakashan languages","l":4},
{"c":"war","n":"Waray","l":4},
{"c":"was","n":"Washo","l":4},
{"c":"wal","n":"Wolaitta; Wolaytta","l":4},
{"c":"sah","n":"Yakut","l":4},
{"c":"yao","n":"Yao","l":4},
{"c":"yap","n":"Yapese","l":4},
{"c":"ypk","n":"Yupik languages","l":4},
{"c":"znd","n":"Zande languages","l":4},
{"c":"zap","n":"Zapotec","l":4},
{"c":"zza","n":"Zaza; Dimili; Kirdki; Kirmanjki; Zazaki","l":4},
{"c":"zen","n":"Zenaga","l":4},
{"c":"zun","n":"Zuni","l":4}
]');
  return $ret;
}

function getLanguageName(&$languages, $code)
{
  foreach ($languages as $language) {
	if ($language->c == $code) {
	  return $language->n;
  } }
  return $code;
}

function annotation_language_code($code,$optional)
{
  if ($optional) {
    echo '
<select name="language_code[]" size=4 multiple="multiple">';
    $mylanguage = '';
  } else {
    echo '
<select id=language_code name="language_code">';
    $mylanguage = $code;

    if (!isset($mylanguage)) {
      $mylanguage = $_SESSION['imageMAT_language_code'];
    }
    switch ($mylanguage) {
    case 'eng':
    case 'fre':
    case 'ger':
    case 'spa':
      break;
    default:
      $mylanguage = 'eng';
  } }
?>
<option value="eng"<?php if ($mylanguage == 'eng') echo ' selected'?>>
English
</option>
<option value="fre"<?php if ($mylanguage == 'fre') echo ' selected'?>>
French
</option>
<option value="ger"<?php if ($mylanguage == 'ger') echo ' selected'?>>
German
</option>
<option value="spa"<?php if ($mylanguage == 'spa') echo ' selected'?>>
Spanish
</option>
</select>
<?php
}

function language_group($level)
{
  switch ($level) {
    case 1:
    echo 'Common Languages';
    break;
  case 2:
    echo 'Other Languages';
    break;
  case 3:
    echo 'Rare Languages';
    break;
  case 4:
    echo 'Historic Languages';
    break;
  default:
    echo 'Level ' . $level;
  }
}

function select_language($val, $rows=6, $annotation_id=null)
{
  $table = db_languages();

  echo '
<select name="language_code';
  if (isset($annotation_id)) {
    echo '[' . $annotation_id . ']';
  }
  echo '" size=' . $rows . '>';
  echo '<option value=""';
  if (!isset($val) || $val == '') {
    echo ' selected';
  }
  echo '></option>';
  $last_level = null;
  foreach($table as $row) {
    $level         = $row->l;
    if ($level != $last_level) {
      if (isset($last_level)) {
        echo '
</optgroup>';
      }
      echo '
<optgroup label="';
      language_group($level);
      echo '">';
      $last_level = $level;
    }
    $language_code = $row->c;
    echo '
<option value=' . $language_code;
    if ($language_code == $val) {
      echo ' selected';
    }
    echo '>' . htmlspecialchars($row->n) . '</option>';
  }
  echo '
</optgroup>
</select>';
  return;
}

function select_multiple_languages($setarray, $rows=6, $annotation_id='')
{
  $table = db_languages();

  if (isset($setarray) && count($setarray) > 0) {
    $selected = array_flip($setarray);
  } /* else {
    $selected = array ();
    if (isset($_SESSION['imageMAT_language_code'])) {
      $selected[$_SESSION['imageMAT_language_code']] = '';
  } } */

  echo '
<select name="language_codes[' . $annotation_id .']" size=' . $rows . ' multiple="multiple">';
  $last_level = null;
  foreach($table as $row) {
    $level         = $row->l;
    if ($level != $last_level) {
      if (isset($last_level)) {
        echo '
</optgroup>';
      }
      echo '
<optgroup label="';
      language_group($level);
      echo '">';
      $last_level = $level;
    }
    $language_code = $row->c;
    echo '
<option value=' . $language_code;
    if (isset($selected) && isset($selected[$language_code])) {
      echo ' selected';
    }
    echo '>' . htmlspecialchars($row->n) . '</option>';
  }
  echo '
</optgroup>
</select>';
}

function reads_languages()
{
  global $gUserid;

  $query = 
'select name
  from usersoflanguages,languages
 where usersoflanguages.language_code = languages.language_code
   and usersoflanguages.user_id = ' . DBstring($gUserid);

  $msg = null;
  $ret = DBquery($query);
  if (!$ret) {
    return null;
  }
  while ($row = DBfetch($ret)) {
    if (!isset($msg)) {
      $msg = '';
    } else {
      $msg .= ', ';
    }
    $msg .= $row['name'];
  }
  return $msg;
}

?>
